<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Language;
use App\Models\ShortSimple;
use App\Models\ShortSimpleTranslation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShortSimpleController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:short_simple.view', only: ['index']),
            new Middleware('permission:short_simple.create', only: ['create', 'store']),
            new Middleware('permission:short_simple.update', only: ['edit', 'update']),
            new Middleware('permission:short_simple.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the short simples.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $shortSimples = ShortSimple::select(['id', 'order_no', 'is_active'])
                ->with([
                    'translations:id,short_simple_id,language_code,title',
                    'chapter:id,exam_id',
                    'chapter.exam:id,code'
                ])
                ->get();
            
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($shortSimples)
                ->addIndexColumn()
                ->addColumn('title', function($shortSimple) use ($languages) {
                    $titles = [];
                    foreach ($languages as $lang) {
                        $translation = $shortSimple->translations->where('language_code', $lang->code)->first();
                        $titles[] = $lang->name . ': ' . ($translation?->title ?? '-');
                    }
                    return implode('<br>', $titles);
                })
                ->addColumn('exam', function($shortSimple) {
                    return $shortSimple->chapter->exam->code ?? '-';
                })
                ->addColumn('status_badge', function ($shortSimple) {
                    return $shortSimple->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($shortSimple) {
                    $buttons = '';

                    if (auth('admin')->user()->can('short_simple.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.short_simples.edit', $shortSimple->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('short_simple.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $shortSimple->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['title', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.short_simples.index');
    }

    /**
     * Show the form for creating a new short simple.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $chapters = Chapter::select(['id'])->where('is_active', true)->with(['translations:id,chapter_id,language_code,title', 'exam:id,code'])->get();
        
        return view('admin.insurance.short_simples.create', compact('languages', 'chapters'));
    }

    /**
     * Store a newly created short simple in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.content' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $shortSimple = ShortSimple::create([
                'chapter_id' => $validated['chapter_id'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                ShortSimpleTranslation::create([
                    'short_simple_id' => $shortSimple->id,
                    'language_code' => $translationData['language_code'],
                    'title' => $translationData['title'],
                    'content' => $translationData['content'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.short_simples.index')
                ->with('success', 'Short simple created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create short simple: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified short simple.
     */
    public function edit(ShortSimple $shortSimple)
    {
        $shortSimple = ShortSimple::select(['id', 'chapter_id', 'order_no', 'is_active'])
            ->where('id', $shortSimple->id)
            ->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $chapters = Chapter::select(['id'])->where('is_active', true)->with(['translations:id,chapter_id,language_code,title', 'exam:id,code'])->get();
        $shortSimple->load('translations');
        
        return view('admin.insurance.short_simples.edit', compact('shortSimple', 'languages', 'chapters'));
    }

    /**
     * Update the specified short simple in storage.
     */
    public function update(Request $request, ShortSimple $shortSimple)
    {
        $shortSimple = ShortSimple::select(['id'])->where('id', $shortSimple->id)->firstOrFail();

        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.content' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $shortSimple->update([
                'chapter_id' => $validated['chapter_id'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                ShortSimpleTranslation::updateOrCreate(
                    [
                        'short_simple_id' => $shortSimple->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'title' => $translationData['title'],
                        'content' => $translationData['content'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.short_simples.index')
                ->with('success', 'Short simple updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update short simple: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified short simple from storage.
     */
    public function destroy(ShortSimple $shortSimple)
    {
        $shortSimple = ShortSimple::select(['id'])->where('id', $shortSimple->id)->firstOrFail();

        try {
            $shortSimple->delete();
            return response()->json([
                'success' => true,
                'message' => 'Short simple deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete short simple: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete short simples
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No short simple IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = ShortSimple::whereIn('id', $ids)->count();
            ShortSimple::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount short simple(s) deleted successfully."
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