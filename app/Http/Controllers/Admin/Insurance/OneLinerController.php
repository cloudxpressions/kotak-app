<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Language;
use App\Models\OneLiner;
use App\Models\OneLinerTranslation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OneLinerController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:one_liner.view', only: ['index']),
            new Middleware('permission:one_liner.create', only: ['create', 'store']),
            new Middleware('permission:one_liner.update', only: ['edit', 'update']),
            new Middleware('permission:one_liner.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the one liners.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $oneLiners = OneLiner::select(['id', 'order_no', 'is_active'])
                ->with([
                    'translations:id,one_liner_id,language_code,content',
                    'chapter:id,exam_id',
                    'chapter.exam:id,code'
                ])
                ->get();
            
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($oneLiners)
                ->addIndexColumn()
                ->addColumn('content', function($oneLiner) use ($languages) {
                    $contents = [];
                    foreach ($languages as $lang) {
                        $translation = $oneLiner->translations->where('language_code', $lang->code)->first();
                        $contents[] = $lang->name . ': ' . (strlen($translation?->content ?? '') > 50 ? substr($translation?->content ?? '-', 0, 50) . '...' : $translation?->content ?? '-');
                    }
                    return implode('<br>', $contents);
                })
                ->addColumn('exam', function($oneLiner) {
                    return $oneLiner->chapter->exam->code ?? '-';
                })
                ->addColumn('status_badge', function ($oneLiner) {
                    return $oneLiner->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($oneLiner) {
                    $buttons = '';

                    if (auth('admin')->user()->can('one_liner.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.one_liners.edit', $oneLiner->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('one_liner.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $oneLiner->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['content', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.one_liners.index');
    }

    /**
     * Show the form for creating a new one liner.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $chapters = Chapter::select(['id'])->where('is_active', true)->with(['translations:id,chapter_id,language_code,title', 'exam:id,code'])->get();
        
        return view('admin.insurance.one_liners.create', compact('languages', 'chapters'));
    }

    /**
     * Store a newly created one liner in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.content' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $oneLiner = OneLiner::create([
                'chapter_id' => $validated['chapter_id'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                OneLinerTranslation::create([
                    'one_liner_id' => $oneLiner->id,
                    'language_code' => $translationData['language_code'],
                    'content' => $translationData['content'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.one_liners.index')
                ->with('success', 'One liner created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create one liner: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified one liner.
     */
    public function edit(OneLiner $oneLiner)
    {
        $oneLiner = OneLiner::select(['id', 'chapter_id', 'order_no', 'is_active'])
            ->where('id', $oneLiner->id)
            ->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $chapters = Chapter::select(['id'])->where('is_active', true)->with(['translations:id,chapter_id,language_code,title', 'exam:id,code'])->get();
        $oneLiner->load('translations');
        
        return view('admin.insurance.one_liners.edit', compact('oneLiner', 'languages', 'chapters'));
    }

    /**
     * Update the specified one liner in storage.
     */
    public function update(Request $request, OneLiner $oneLiner)
    {
        $oneLiner = OneLiner::select(['id'])->where('id', $oneLiner->id)->firstOrFail();

        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.content' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $oneLiner->update([
                'chapter_id' => $validated['chapter_id'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                OneLinerTranslation::updateOrCreate(
                    [
                        'one_liner_id' => $oneLiner->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'content' => $translationData['content'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.one_liners.index')
                ->with('success', 'One liner updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update one liner: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified one liner from storage.
     */
    public function destroy(OneLiner $oneLiner)
    {
        $oneLiner = OneLiner::select(['id'])->where('id', $oneLiner->id)->firstOrFail();

        try {
            $oneLiner->delete();
            return response()->json([
                'success' => true,
                'message' => 'One liner deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete one liner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete one liners
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No one liner IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = OneLiner::whereIn('id', $ids)->count();
            OneLiner::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount one liner(s) deleted successfully."
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