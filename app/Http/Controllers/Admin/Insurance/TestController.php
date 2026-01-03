<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Exam;
use App\Models\Language;
use App\Models\Test;
use App\Models\TestTranslation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TestController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:test.view', only: ['index']),
            new Middleware('permission:test.create', only: ['create', 'store']),
            new Middleware('permission:test.update', only: ['edit', 'update']),
            new Middleware('permission:test.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the tests.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tests = Test::select(['id', 'type', 'total_questions', 'duration_minutes', 'is_active'])
                ->with([
                    'translations:id,test_id,language_code,title',
                    'exam:id,code'
                ])
                ->get();
            
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($tests)
                ->addIndexColumn()
                ->addColumn('title', function($test) use ($languages) {
                    $titles = [];
                    foreach ($languages as $lang) {
                        $translation = $test->translations->where('language_code', $lang->code)->first();
                        $titles[] = $lang->name . ': ' . ($translation?->title ?? '-');
                    }
                    return implode('<br>', $titles);
                })
                ->addColumn('exam', function($test) {
                    return $test->exam->code ?? '-';
                })
                ->addColumn('type_badge', function($test) {
                    $type = ucfirst($test->type);
                    $color = match($test->type) {
                        'mock' => 'bg-blue-lt',
                        'practice' => 'bg-green-lt',
                        'live' => 'bg-red-lt',
                        'chapter' => 'bg-orange-lt',
                        default => 'bg-gray-lt'
                    };
                    return "<span class=\"badge $color\">$type</span>";
                })
                ->addColumn('status_badge', function ($test) {
                    return $test->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($test) {
                    $buttons = '';

                    if (auth('admin')->user()->can('test.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.tests.edit', $test->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('test.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $test->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['title', 'type_badge', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.tests.index');
    }

    /**
     * Show the form for creating a new test.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        $chapters = Chapter::select(['id'])->where('is_active', true)->with(['translations:id,chapter_id,language_code,title', 'exam:id,code'])->get();
        
        return view('admin.insurance.tests.create', compact('languages', 'exams', 'chapters'));
    }

    /**
     * Store a newly created test in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'type' => 'required|in:mock,practice,live,chapter',
            'total_questions' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $test = Test::create([
                'exam_id' => $validated['exam_id'],
                'chapter_id' => $validated['chapter_id'] ?? null,
                'type' => $validated['type'],
                'total_questions' => $validated['total_questions'],
                'duration_minutes' => $validated['duration_minutes'],
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                TestTranslation::create([
                    'test_id' => $test->id,
                    'language_code' => $translationData['language_code'],
                    'title' => $translationData['title'],
                    'description' => $translationData['description'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.tests.index')
                ->with('success', 'Test created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create test: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified test.
     */
    public function edit(Test $test)
    {
        $test = Test::select(['id', 'exam_id', 'chapter_id', 'type', 'total_questions', 'duration_minutes', 'is_active'])
            ->where('id', $test->id)
            ->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        $chapters = Chapter::select(['id'])->where('is_active', true)->with(['translations:id,chapter_id,language_code,title', 'exam:id,code'])->get();
        $test->load('translations');
        
        return view('admin.insurance.tests.edit', compact('test', 'languages', 'exams', 'chapters'));
    }

    /**
     * Update the specified test in storage.
     */
    public function update(Request $request, Test $test)
    {
        $test = Test::select(['id'])->where('id', $test->id)->firstOrFail();

        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'type' => 'required|in:mock,practice,live,chapter',
            'total_questions' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $test->update([
                'exam_id' => $validated['exam_id'],
                'chapter_id' => $validated['chapter_id'] ?? null,
                'type' => $validated['type'],
                'total_questions' => $validated['total_questions'],
                'duration_minutes' => $validated['duration_minutes'],
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                TestTranslation::updateOrCreate(
                    [
                        'test_id' => $test->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'title' => $translationData['title'],
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.tests.index')
                ->with('success', 'Test updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update test: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified test from storage.
     */
    public function destroy(Test $test)
    {
        $test = Test::select(['id'])->where('id', $test->id)->firstOrFail();

        try {
            $test->delete();
            return response()->json([
                'success' => true,
                'message' => 'Test deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete test: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete tests
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No test IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = Test::whereIn('id', $ids)->count();
            Test::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount test(s) deleted successfully."
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