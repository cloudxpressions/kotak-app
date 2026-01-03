<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ConceptController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:concept.view', only: ['index']),
            new Middleware('permission:concept.create', only: ['create', 'store']),
            new Middleware('permission:concept.update', only: ['edit', 'update']),
            new Middleware('permission:concept.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the concepts.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $concepts = Concept::select(['id', 'order_no', 'is_active'])
                ->with([
                    'translations:id,concept_id,language_code,title',
                    'chapter:id,exam_id',
                    'chapter.exam:id,code'
                ])
                ->get();
            
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($concepts)
                ->addIndexColumn()
                ->addColumn('title', function($concept) use ($languages) {
                    $titles = [];
                    foreach ($languages as $lang) {
                        $translation = $concept->translations->where('language_code', $lang->code)->first();
                        $titles[] = $lang->name . ': ' . ($translation?->title ?? '-');
                    }
                    return implode('<br>', $titles);
                })
                ->addColumn('exam', function($concept) {
                    return $concept->chapter->exam->code ?? '-';
                })
                ->addColumn('status_badge', function ($concept) {
                    return $concept->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($concept) {
                    $buttons = '';

                    if (auth('admin')->user()->can('concept.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.concepts.edit', $concept->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('concept.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $concept->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['title', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.concepts.index');
    }

    /**
     * Show the form for creating a new concept.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $chapters = Chapter::select(['id'])->where('is_active', true)->with(['translations:id,chapter_id,language_code,title', 'exam:id,code'])->get();
        
        return view('admin.insurance.concepts.create', compact('languages', 'chapters'));
    }

    /**
     * Store a newly created concept in storage.
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
            'translations.*.content_html' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $concept = Concept::create([
                'chapter_id' => $validated['chapter_id'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                ConceptTranslation::create([
                    'concept_id' => $concept->id,
                    'language_code' => $translationData['language_code'],
                    'title' => $translationData['title'],
                    'content_html' => $translationData['content_html'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.concepts.index')
                ->with('success', 'Concept created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create concept: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified concept.
     */
    public function edit(Concept $concept)
    {
        $concept = Concept::select(['id', 'chapter_id', 'order_no', 'is_active'])
            ->where('id', $concept->id)
            ->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $chapters = Chapter::select(['id'])->where('is_active', true)->with(['translations:id,chapter_id,language_code,title', 'exam:id,code'])->get();
        $concept->load('translations');
        
        return view('admin.insurance.concepts.edit', compact('concept', 'languages', 'chapters'));
    }

    /**
     * Update the specified concept in storage.
     */
    public function update(Request $request, Concept $concept)
    {
        $concept = Concept::select(['id'])->where('id', $concept->id)->firstOrFail();

        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.content_html' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $concept->update([
                'chapter_id' => $validated['chapter_id'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                ConceptTranslation::updateOrCreate(
                    [
                        'concept_id' => $concept->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'title' => $translationData['title'],
                        'content_html' => $translationData['content_html'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.concepts.index')
                ->with('success', 'Concept updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update concept: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified concept from storage.
     */
    public function destroy(Concept $concept)
    {
        $concept = Concept::select(['id'])->where('id', $concept->id)->firstOrFail();

        try {
            $concept->delete();
            return response()->json([
                'success' => true,
                'message' => 'Concept deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete concept: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete concepts
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No concept IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = Concept::whereIn('id', $ids)->count();
            Concept::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount concept(s) deleted successfully."
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