<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use App\Models\BlogTagTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BlogTagController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:blog_tags.view', only: ['index']),
            new Middleware('permission:blog_tags.create', only: ['create', 'store']),
            new Middleware('permission:blog_tags.update', only: ['edit', 'update']),
            new Middleware('permission:blog_tags.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the blog tags.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tags = BlogTag::select(['id', 'is_active'])->with('translations:id,blog_tag_id,language_id,name')->get();
            $languages = Language::select(['id', 'name'])->where('is_active', true)->get()->keyBy('id');

            return DataTables::of($tags)
                ->addIndexColumn()
                ->addColumn('name', function($tag) use ($languages) {
                    $names = [];
                    foreach ($languages as $lang) {
                        $translation = $tag->translations->where('language_id', $lang->id)->first();
                        $names[] = $lang->name . ': ' . ($translation?->name ?? '-');
                    }
                    return implode('<br>', $names);
                })
                ->addColumn('status_badge', function ($tag) {
                    return $tag->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($tag) {
                    $buttons = '';

                    if (auth('admin')->user()->can('blog_tags.update')) {
                        $buttons .= '<a href="' . route('admin.blog.tags.edit', $tag->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('blog_tags.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $tag->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['name', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.blog.tags.index');
    }

    /**
     * Show the form for creating a new blog tag.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        return view('admin.blog.tags.create', compact('languages'));
    }

    /**
     * Store a newly created blog tag in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.slug' => 'required|string|max:255|unique:blog_tag_translations,slug,NULL,id,language_id,translations.*.language_id',
        ]);

        DB::beginTransaction();
        try {
            $tag = BlogTag::create([
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                BlogTagTranslation::create([
                    'blog_tag_id' => $tag->id,
                    'language_id' => $translationData['language_id'],
                    'name' => $translationData['name'],
                    'slug' => $translationData['slug'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.blog.tags.index')
                ->with('success', 'Blog tag created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create blog tag: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified blog tag.
     */
    public function edit(BlogTag $tag)
    {
        $tag = BlogTag::select(['id', 'is_active'])->where('id', $tag->id)->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $tag->load('translations');
        return view('admin.blog.tags.edit', compact('tag', 'languages'));
    }

    /**
     * Update the specified blog tag in storage.
     */
    public function update(Request $request, BlogTag $tag)
    {
        $tag = BlogTag::select(['id'])->where('id', $tag->id)->firstOrFail();

        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.slug' => 'required|string|max:255|unique:blog_tag_translations,slug,NULL,id,language_id,translations.*.language_id',
        ]);

        DB::beginTransaction();
        try {
            $tag->update([
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                BlogTagTranslation::updateOrCreate(
                    [
                        'blog_tag_id' => $tag->id,
                        'language_id' => $translationData['language_id'],
                    ],
                    [
                        'name' => $translationData['name'],
                        'slug' => $translationData['slug'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.blog.tags.index')
                ->with('success', 'Blog tag updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update blog tag: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified blog tag from storage.
     */
    public function destroy(BlogTag $tag)
    {
        $tag = BlogTag::select(['id'])->where('id', $tag->id)->firstOrFail();

        try {
            $tag->delete();
            return response()->json([
                'success' => true,
                'message' => 'Blog tag deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete blog tag: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete blog tags
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No tag IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = BlogTag::whereIn('id', $ids)->count();
            BlogTag::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount tag(s) deleted successfully."
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Bulk deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}