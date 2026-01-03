<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogCategoryTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:blog_category.view', only: ['index']),
            new Middleware('permission:blog_category.create', only: ['create', 'store']),
            new Middleware('permission:blog_category.update', only: ['edit', 'update']),
            new Middleware('permission:blog_category.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the blog categories.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = BlogCategory::select(['id', 'is_active'])->with('translations:id,blog_category_id,language_id,name')->get();
            $languages = Language::select(['id', 'name'])->where('is_active', true)->get()->keyBy('id');

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('name', function($category) use ($languages) {
                    $names = [];
                    foreach ($languages as $lang) {
                        $translation = $category->translations->where('language_id', $lang->id)->first();
                        $names[] = $lang->name . ': ' . ($translation?->name ?? '-');
                    }
                    return implode('<br>', $names);
                })
                ->addColumn('status_badge', function ($category) {
                    return $category->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($category) {
                    $buttons = '';

                    if (auth('admin')->user()->can('blog_category.update')) {
                        $buttons .= '<a href="' . route('admin.blog.categories.edit', $category->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('blog_category.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $category->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['name', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.blog.categories.index');
    }

    /**
     * Show the form for creating a new blog category.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $categories = BlogCategory::select(['id'])->where('is_active', true)->with(['translations:id,blog_category_id,name'])->get();
        return view('admin.blog.categories.create', compact('languages', 'categories'));
    }

    /**
     * Store a newly created blog category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:blog_categories,id',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.slug' => 'required|string|max:255|unique:blog_category_translations,slug,NULL,id,language_id,translations.*.language_id',
        ]);

        DB::beginTransaction();
        try {
            $category = BlogCategory::create([
                'parent_id' => $validated['parent_id'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                BlogCategoryTranslation::create([
                    'blog_category_id' => $category->id,
                    'language_id' => $translationData['language_id'],
                    'name' => $translationData['name'],
                    'slug' => $translationData['slug'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.blog.categories.index')
                ->with('success', 'Blog category created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create blog category: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified blog category.
     */
    public function edit(BlogCategory $category)
    {
        $category = BlogCategory::select(['id', 'parent_id', 'is_active'])->where('id', $category->id)->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $categories = BlogCategory::select(['id'])->where('is_active', true)->where('id', '!=', $category->id)->with(['translations:id,blog_category_id,name'])->get();
        $category->load('translations');
        return view('admin.blog.categories.edit', compact('category', 'languages', 'categories'));
    }

    /**
     * Update the specified blog category in storage.
     */
    public function update(Request $request, BlogCategory $category)
    {
        $category = BlogCategory::select(['id'])->where('id', $category->id)->firstOrFail();

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:blog_categories,id',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.slug' => 'required|string|max:255|unique:blog_category_translations,slug,NULL,id,language_id,translations.*.language_id',
        ]);

        DB::beginTransaction();
        try {
            $category->update([
                'parent_id' => $validated['parent_id'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                BlogCategoryTranslation::updateOrCreate(
                    [
                        'blog_category_id' => $category->id,
                        'language_id' => $translationData['language_id'],
                    ],
                    [
                        'name' => $translationData['name'],
                        'slug' => $translationData['slug'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.blog.categories.index')
                ->with('success', 'Blog category updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update blog category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified blog category from storage.
     */
    public function destroy(BlogCategory $category)
    {
        $category = BlogCategory::select(['id'])->where('id', $category->id)->firstOrFail();

        try {
            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Blog category deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete blog category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete blog categories
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No category IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = BlogCategory::whereIn('id', $ids)->count();
            BlogCategory::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount category(s) deleted successfully."
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