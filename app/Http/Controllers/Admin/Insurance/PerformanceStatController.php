<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\PerformanceStat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PerformanceStatController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:performance_stat.view', only: ['index']),
            new Middleware('permission:performance_stat.update', only: ['edit', 'update']),
            new Middleware('permission:performance_stat.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the performance stats.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $performanceStats = PerformanceStat::select(['id', 'user_id', 'exam_id', 'total_tests', 'avg_score', 'accuracy', 'last_test_at'])
                ->with([
                    'user:id,name',
                    'exam:id,code'
                ])
                ->get();

            return DataTables::of($performanceStats)
                ->addIndexColumn()
                ->addColumn('user', function($performanceStat) {
                    return $performanceStat->user->name ?? '-';
                })
                ->addColumn('exam', function($performanceStat) {
                    return $performanceStat->exam->code ?? '-';
                })
                ->addColumn('action', function ($performanceStat) {
                    $buttons = '';

                    if (auth('admin')->user()->can('performance_stat.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.performance_stats.edit', $performanceStat->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('performance_stat.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $performanceStat->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.insurance.performance_stats.index');
    }

    /**
     * Show the form for editing the specified performance stat.
     */
    public function edit(PerformanceStat $performanceStat)
    {
        $performanceStat = PerformanceStat::select(['id', 'user_id', 'exam_id', 'total_tests', 'avg_score', 'accuracy', 'last_test_at'])
            ->where('id', $performanceStat->id)
            ->firstOrFail();

        $users = User::select(['id', 'name'])->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        
        return view('admin.insurance.performance_stats.edit', compact('performanceStat', 'users', 'exams'));
    }

    /**
     * Update the specified performance stat in storage.
     */
    public function update(Request $request, PerformanceStat $performanceStat)
    {
        $performanceStat = PerformanceStat::select(['id'])->where('id', $performanceStat->id)->firstOrFail();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
            'total_tests' => 'required|integer|min:0',
            'avg_score' => 'required|numeric|min:0|max:100',
            'accuracy' => 'required|numeric|min:0|max:100',
            'last_test_at' => 'nullable|date',
        ]);

        try {
            $performanceStat->update([
                'user_id' => $validated['user_id'],
                'exam_id' => $validated['exam_id'],
                'total_tests' => $validated['total_tests'],
                'avg_score' => $validated['avg_score'],
                'accuracy' => $validated['accuracy'],
                'last_test_at' => $validated['last_test_at'] ?? null,
            ]);

            return redirect()->route('admin.insurance.performance_stats.index')
                ->with('success', 'Performance stat updated successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update performance stat: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified performance stat from storage.
     */
    public function destroy(PerformanceStat $performanceStat)
    {
        $performanceStat = PerformanceStat::select(['id'])->where('id', $performanceStat->id)->firstOrFail();

        try {
            $performanceStat->delete();
            return response()->json([
                'success' => true,
                'message' => 'Performance stat deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete performance stat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete performance stats
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No performance stat IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = PerformanceStat::whereIn('id', $ids)->count();
            PerformanceStat::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount performance stat(s) deleted successfully."
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