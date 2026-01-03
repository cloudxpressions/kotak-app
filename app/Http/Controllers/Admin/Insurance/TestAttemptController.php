<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TestAttemptController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:test_attempt.view', only: ['index']),
            new Middleware('permission:test_attempt.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the test attempts.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $testAttempts = TestAttempt::select(['id', 'user_id', 'test_id', 'score', 'started_at', 'submitted_at'])
                ->with([
                    'user:id,name',
                    'test:id,exam_id',
                    'test.exam:id,code'
                ])
                ->get();

            return DataTables::of($testAttempts)
                ->addIndexColumn()
                ->addColumn('user', function($testAttempt) {
                    return $testAttempt->user->name ?? '-';
                })
                ->addColumn('test', function($testAttempt) {
                    return $testAttempt->test->exam->code ?? '-';
                })
                ->addColumn('status_badge', function ($testAttempt) {
                    return $testAttempt->submitted_at
                        ? '<span class="badge bg-success-lt">Completed</span>'
                        : '<span class="badge bg-warning-lt">In Progress</span>';
                })
                ->addColumn('action', function ($testAttempt) {
                    $buttons = '';

                    if (auth('admin')->user()->can('test_attempt.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $testAttempt->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.test_attempts.index');
    }

    /**
     * Remove the specified test attempt from storage.
     */
    public function destroy(TestAttempt $testAttempt)
    {
        $testAttempt = TestAttempt::select(['id'])->where('id', $testAttempt->id)->firstOrFail();

        try {
            $testAttempt->delete();
            return response()->json([
                'success' => true,
                'message' => 'Test attempt deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete test attempt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete test attempts
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No test attempt IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = TestAttempt::whereIn('id', $ids)->count();
            TestAttempt::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount test attempt(s) deleted successfully."
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