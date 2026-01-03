<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:audit.view', only: ['index']),
            new Middleware('permission:audit.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $activities = Activity::with('causer')->latest();

            // Apply filters if provided
            if ($request->has('log_name') && $request->get('log_name')) {
                $activities->where('log_name', $request->get('log_name'));
            }

            if ($request->has('description') && $request->get('description')) {
                $activities->where('description', 'LIKE', '%' . $request->get('description') . '%');
            }

            if ($request->has('causer_id') && $request->get('causer_id')) {
                $activities->where('causer_id', $request->get('causer_id'));
            }

            return DataTables::of($activities)
                ->addColumn('user', function ($activity) {
                    return $activity->causer ? $activity->causer->name : 'System';
                })
                ->addColumn('changes', function ($activity) {
                    $oldValues = $activity->properties->get('old') ?? [];
                    $attributes = $activity->properties->get('attributes') ?? [];
                    
                    $changes = [];
                    foreach ($oldValues as $field => $oldValue) {
                        $newValue = $attributes[$field] ?? 'N/A';
                        $changes[] = "<strong>{$field}:</strong> {$oldValue} → {$newValue}";
                    }
                    
                    // Add new attributes
                    foreach ($attributes as $field => $newValue) {
                        if (!isset($oldValues[$field])) {
                            $changes[] = "<strong>{$field}:</strong> New → {$newValue}";
                        }
                    }
                    
                    return count($changes) > 0 ? implode('<br>', $changes) : 'No changes';
                })
                ->addColumn('actions', function ($activity) {
                    return '
                        <button type="button" 
                                class="btn btn-sm btn-icon btn-danger delete-btn" 
                                data-id="' . $activity->id . '" 
                                title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </button>
                    ';
                })
                ->editColumn('created_at', function ($activity) {
                    return $activity->created_at->format('Y-m-d H:i:s');
                })
                ->editColumn('description', function ($activity) {
                    $badgeClass = match(strtolower($activity->description)) {
                        'created' => 'bg-success-lt',
                        'updated' => 'bg-warning-lt',
                        'deleted' => 'bg-danger-lt',
                        default => 'bg-primary-lt',
                    };

                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($activity->description) . '</span>';
                })
                ->editColumn('log_name', function ($activity) {
                    return '<span class="badge bg-blue-lt">' . ($activity->log_name ?? 'default') . '</span>';
                })
                ->rawColumns(['user', 'changes', 'actions', 'description', 'log_name'])
                ->make(true);
        }

        // Get unique log names for filter dropdown
        $logNames = Activity::select('log_name')->distinct()->whereNotNull('log_name')->pluck('log_name');

        return view('admin.system.activity-log.index', compact('logNames'));
    }

    /**
     * Remove the specified activity log from storage.
     */
    public function destroy(Activity $activity)
    {
        try {
            $activity->delete();
            return response()->json([
                'success' => true,
                'message' => 'Activity log deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete activity log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete activity logs
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_log,id'
        ]);

        try {
            Activity::whereIn('id', $request->ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' activity logs deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete activity logs: ' . $e->getMessage()
            ], 500);
        }
    }
}