<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Question;
use App\Models\QuestionTranslation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class QuestionController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:question.view', only: ['index']),
            new Middleware('permission:question.create', only: ['create', 'store']),
            new Middleware('permission:question.update', only: ['edit', 'update']),
            new Middleware('permission:question.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the questions.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $questions = Question::select(['id', 'difficulty', 'correct_option', 'is_active'])
                ->with([
                    'translations:id,question_id,language_code,question_text'
                ])
                ->get();
            
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($questions)
                ->addIndexColumn()
                ->addColumn('question_text', function($question) use ($languages) {
                    $questions = [];
                    foreach ($languages as $lang) {
                        $translation = $question->translations->where('language_code', $lang->code)->first();
                        $questions[] = $lang->name . ': ' . (strlen($translation?->question_text ?? '') > 50 ? substr($translation?->question_text ?? '-', 0, 50) . '...' : $translation?->question_text ?? '-');
                    }
                    return implode('<br>', $questions);
                })
                ->addColumn('difficulty_badge', function($question) {
                    $difficulty = ucfirst($question->difficulty);
                    $color = match($question->difficulty) {
                        'easy' => 'bg-success-lt',
                        'medium' => 'bg-warning-lt',
                        'hard' => 'bg-danger-lt',
                        default => 'bg-gray-lt'
                    };
                    return "<span class=\"badge $color\">$difficulty</span>";
                })
                ->addColumn('status_badge', function ($question) {
                    return $question->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($question) {
                    $buttons = '';

                    if (auth('admin')->user()->can('question.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.questions.edit', $question->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('question.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $question->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['question_text', 'difficulty_badge', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.questions.index');
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        
        return view('admin.insurance.questions.create', compact('languages'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'difficulty' => 'required|in:easy,medium,hard',
            'correct_option' => 'required|in:A,B,C,D',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.question_text' => 'required|string',
            'translations.*.option_a' => 'required|string',
            'translations.*.option_b' => 'required|string',
            'translations.*.option_c' => 'required|string',
            'translations.*.option_d' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $question = Question::create([
                'difficulty' => $validated['difficulty'],
                'correct_option' => $validated['correct_option'],
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                QuestionTranslation::create([
                    'question_id' => $question->id,
                    'language_code' => $translationData['language_code'],
                    'question_text' => $translationData['question_text'],
                    'option_a' => $translationData['option_a'],
                    'option_b' => $translationData['option_b'],
                    'option_c' => $translationData['option_c'],
                    'option_d' => $translationData['option_d'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.questions.index')
                ->with('success', 'Question created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create question: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question)
    {
        $question = Question::select(['id', 'difficulty', 'correct_option', 'is_active'])
            ->where('id', $question->id)
            ->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $question->load('translations');
        
        return view('admin.insurance.questions.edit', compact('question', 'languages'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Question $question)
    {
        $question = Question::select(['id'])->where('id', $question->id)->firstOrFail();

        $validated = $request->validate([
            'difficulty' => 'required|in:easy,medium,hard',
            'correct_option' => 'required|in:A,B,C,D',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.question_text' => 'required|string',
            'translations.*.option_a' => 'required|string',
            'translations.*.option_b' => 'required|string',
            'translations.*.option_c' => 'required|string',
            'translations.*.option_d' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $question->update([
                'difficulty' => $validated['difficulty'],
                'correct_option' => $validated['correct_option'],
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                QuestionTranslation::updateOrCreate(
                    [
                        'question_id' => $question->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'question_text' => $translationData['question_text'],
                        'option_a' => $translationData['option_a'],
                        'option_b' => $translationData['option_b'],
                        'option_c' => $translationData['option_c'],
                        'option_d' => $translationData['option_d'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.questions.index')
                ->with('success', 'Question updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update question: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(Question $question)
    {
        $question = Question::select(['id'])->where('id', $question->id)->firstOrFail();

        try {
            $question->delete();
            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete questions
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No question IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = Question::whereIn('id', $ids)->count();
            Question::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount question(s) deleted successfully."
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