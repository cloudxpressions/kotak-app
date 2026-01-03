<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterTranslation;
use App\Models\Exam;
use App\Models\InsuranceCategory;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ChapterController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:chapter.view', only: ['index']),
            new Middleware('permission:chapter.create', only: ['create', 'store']),
            new Middleware('permission:chapter.update', only: ['edit', 'update']),
            new Middleware('permission:chapter.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the chapters.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $chapters = Chapter::select(['id', 'order_no', 'is_active'])
                ->with([
                    'translations:id,chapter_id,language_code,title',
                    'exam:id,code',
                    'insuranceCategory:id,slug'
                ])
                ->get();
            
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($chapters)
                ->addIndexColumn()
                ->addColumn('title', function($chapter) use ($languages) {
                    $titles = [];
                    foreach ($languages as $lang) {
                        $translation = $chapter->translations->where('language_code', $lang->code)->first();
                        $titles[] = $lang->name . ': ' . ($translation?->title ?? '-');
                    }
                    return implode('<br>', $titles);
                })
                ->addColumn('exam', function($chapter) {
                    return $chapter->exam->code ?? '-';
                })
                ->addColumn('category', function($chapter) {
                    return $chapter->insuranceCategory->slug ?? '-';
                })
                ->addColumn('status_badge', function ($chapter) {
                    return $chapter->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($chapter) {
                    $buttons = '';

                    if (auth('admin')->user()->can('chapter.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.chapters.edit', $chapter->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('chapter.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $chapter->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['title', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.chapters.index');
    }

    /**
     * Show the form for creating a new chapter.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        $categories = InsuranceCategory::select(['id', 'slug'])->where('is_active', true)->with(['translations:id,insurance_category_id,language_code,name'])->get();
        
        return view('admin.insurance.chapters.create', compact('languages', 'exams', 'categories'));
    }

    /**
     * Store a newly created chapter in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'insurance_category_id' => 'required|exists:insurance_categories,id',
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $chapter = Chapter::create([
                'exam_id' => $validated['exam_id'],
                'insurance_category_id' => $validated['insurance_category_id'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                ChapterTranslation::create([
                    'chapter_id' => $chapter->id,
                    'language_code' => $translationData['language_code'],
                    'title' => $translationData['title'],
                    'description' => $translationData['description'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.chapters.index')
                ->with('success', 'Chapter created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create chapter: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified chapter.
     */
    public function edit(Chapter $chapter)
    {
        $chapter = Chapter::select(['id', 'exam_id', 'insurance_category_id', 'order_no', 'is_active'])
            ->where('id', $chapter->id)
            ->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        $categories = InsuranceCategory::select(['id', 'slug'])->where('is_active', true)->with(['translations:id,insurance_category_id,language_code,name'])->get();
        $chapter->load('translations');
        
        return view('admin.insurance.chapters.edit', compact('chapter', 'languages', 'exams', 'categories'));
    }

    /**
     * Update the specified chapter in storage.
     */
    public function update(Request $request, Chapter $chapter)
    {
        $chapter = Chapter::select(['id'])->where('id', $chapter->id)->firstOrFail();

        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'insurance_category_id' => 'required|exists:insurance_categories,id',
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $chapter->update([
                'exam_id' => $validated['exam_id'],
                'insurance_category_id' => $validated['insurance_category_id'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                ChapterTranslation::updateOrCreate(
                    [
                        'chapter_id' => $chapter->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'title' => $translationData['title'],
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.chapters.index')
                ->with('success', 'Chapter updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update chapter: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified chapter from storage.
     */
    public function destroy(Chapter $chapter)
    {
        $chapter = Chapter::select(['id'])->where('id', $chapter->id)->firstOrFail();

        try {
            $chapter->delete();
            return response()->json([
                'success' => true,
                'message' => 'Chapter deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete chapter: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete chapters
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No chapter IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = Chapter::whereIn('id', $ids)->count();
            Chapter::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount chapter(s) deleted successfully."
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