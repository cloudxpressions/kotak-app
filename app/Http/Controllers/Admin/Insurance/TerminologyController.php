<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Language;
use App\Models\Terminology;
use App\Models\TerminologyTranslation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TerminologyController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:terminology.view', only: ['index']),
            new Middleware('permission:terminology.create', only: ['create', 'store']),
            new Middleware('permission:terminology.update', only: ['edit', 'update']),
            new Middleware('permission:terminology.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the terminologies.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $terminologies = Terminology::select(['id', 'category', 'is_active'])
                ->with([
                    'translations:id,terminology_id,language_code,term',
                    'exam:id,code'
                ])
                ->get();
            
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($terminologies)
                ->addIndexColumn()
                ->addColumn('term', function($terminology) use ($languages) {
                    $terms = [];
                    foreach ($languages as $lang) {
                        $translation = $terminology->translations->where('language_code', $lang->code)->first();
                        $terms[] = $lang->name . ': ' . ($translation?->term ?? '-');
                    }
                    return implode('<br>', $terms);
                })
                ->addColumn('exam', function($terminology) {
                    return $terminology->exam->code ?? '-';
                })
                ->addColumn('status_badge', function ($terminology) {
                    return $terminology->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($terminology) {
                    $buttons = '';

                    if (auth('admin')->user()->can('terminology.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.terminologies.edit', $terminology->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('terminology.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $terminology->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['term', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.terminologies.index');
    }

    /**
     * Show the form for creating a new terminology.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        
        return view('admin.insurance.terminologies.create', compact('languages', 'exams'));
    }

    /**
     * Store a newly created terminology in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'category' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.term' => 'required|string|max:255',
            'translations.*.definition' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $terminology = Terminology::create([
                'exam_id' => $validated['exam_id'],
                'category' => $validated['category'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                TerminologyTranslation::create([
                    'terminology_id' => $terminology->id,
                    'language_code' => $translationData['language_code'],
                    'term' => $translationData['term'],
                    'definition' => $translationData['definition'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.terminologies.index')
                ->with('success', 'Terminology created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create terminology: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified terminology.
     */
    public function edit(Terminology $terminology)
    {
        $terminology = Terminology::select(['id', 'exam_id', 'category', 'is_active'])
            ->where('id', $terminology->id)
            ->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        $terminology->load('translations');
        
        return view('admin.insurance.terminologies.edit', compact('terminology', 'languages', 'exams'));
    }

    /**
     * Update the specified terminology in storage.
     */
    public function update(Request $request, Terminology $terminology)
    {
        $terminology = Terminology::select(['id'])->where('id', $terminology->id)->firstOrFail();

        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'category' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.term' => 'required|string|max:255',
            'translations.*.definition' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $terminology->update([
                'exam_id' => $validated['exam_id'],
                'category' => $validated['category'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                TerminologyTranslation::updateOrCreate(
                    [
                        'terminology_id' => $terminology->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'term' => $translationData['term'],
                        'definition' => $translationData['definition'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.terminologies.index')
                ->with('success', 'Terminology updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update terminology: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified terminology from storage.
     */
    public function destroy(Terminology $terminology)
    {
        $terminology = Terminology::select(['id'])->where('id', $terminology->id)->firstOrFail();

        try {
            $terminology->delete();
            return response()->json([
                'success' => true,
                'message' => 'Terminology deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete terminology: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete terminologies
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No terminology IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = Terminology::whereIn('id', $ids)->count();
            Terminology::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount terminology(s) deleted successfully."
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