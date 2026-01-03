<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExamController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:exam.view', only: ['index']),
            new Middleware('permission:exam.create', only: ['create', 'store']),
            new Middleware('permission:exam.update', only: ['edit', 'update']),
            new Middleware('permission:exam.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the exams.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $exams = Exam::select(['id', 'code', 'is_active'])->with('translations:id,exam_id,language_code,name')->get();
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($exams)
                ->addIndexColumn()
                ->addColumn('name', function($exam) use ($languages) {
                    $names = [];
                    foreach ($languages as $lang) {
                        $translation = $exam->translations->where('language_code', $lang->code)->first();
                        $names[] = $lang->name . ': ' . ($translation?->name ?? '-');
                    }
                    return implode('<br>', $names);
                })
                ->addColumn('status_badge', function ($exam) {
                    return $exam->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($exam) {
                    $buttons = '';

                    if (auth('admin')->user()->can('exam.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.exams.edit', $exam->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('exam.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $exam->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['name', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.exams.index');
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        return view('admin.insurance.exams.create', compact('languages'));
    }

    /**
     * Store a newly created exam in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:exams,code',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $exam = Exam::create([
                'code' => $validated['code'],
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                ExamTranslation::create([
                    'exam_id' => $exam->id,
                    'language_code' => $translationData['language_code'],
                    'name' => $translationData['name'],
                    'description' => $translationData['description'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.exams.index')
                ->with('success', 'Exam created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create exam: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit(Exam $exam)
    {
        $exam = Exam::select(['id', 'code', 'is_active'])->where('id', $exam->id)->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exam->load('translations');
        return view('admin.insurance.exams.edit', compact('exam', 'languages'));
    }

    /**
     * Update the specified exam in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $exam = Exam::select(['id'])->where('id', $exam->id)->firstOrFail();

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:exams,code,' . $exam->id,
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $exam->update([
                'code' => $validated['code'],
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                ExamTranslation::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'name' => $translationData['name'],
                        'description' => $translationData['description'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.exams.index')
                ->with('success', 'Exam updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update exam: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy(Exam $exam)
    {
        $exam = Exam::select(['id'])->where('id', $exam->id)->firstOrFail();

        try {
            $exam->delete();
            return response()->json([
                'success' => true,
                'message' => 'Exam deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete exam: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete exams
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No exam IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = Exam::whereIn('id', $ids)->count();
            Exam::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount exam(s) deleted successfully."
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