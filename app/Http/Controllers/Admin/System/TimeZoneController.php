<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\TimeZone;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class TimeZoneController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:timezone.view', only: ['index']),
            new Middleware('permission:timezone.create', only: ['create', 'store']),
            new Middleware('permission:timezone.update', only: ['edit', 'update']),
            new Middleware('permission:timezone.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of time zones with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $timeZones = TimeZone::query();

            return DataTables::of($timeZones)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%")
                              ->orWhere('timezone', 'LIKE', "%{$search}%")
                              ->orWhere('offset', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('status_badge', function ($timeZone) {
                    return $timeZone->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($timeZone) {
                    $buttons = '';

                    if (auth('admin')->user()->can('timezone.update')) {
                        $buttons .= '<a href="' . route('admin.system.time-zones.edit', $timeZone->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('timezone.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $timeZone->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.time-zones.index');
    }

    /**
     * Show the form for creating a new time zone
     */
    public function create()
    {
        return view('admin.system.time-zones.create');
    }

    /**
     * Store a newly created time zone
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'timezone' => 'required|string|max:255',
            'offset' => 'nullable|string|max:10',
            'utc_offset_minutes' => 'required|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $timeZone = TimeZone::create(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            return redirect()->route('admin.system.time-zones.index')
                ->with('success', 'Time zone created successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create time zone: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified time zone
     */
    public function edit(TimeZone $timeZone)
    {
        return view('admin.system.time-zones.edit', compact('timeZone'));
    }

    /**
     * Update the specified time zone
     */
    public function update(Request $request, TimeZone $timeZone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'timezone' => 'required|string|max:255|unique:time_zones,timezone,' . $timeZone->id,
            'offset' => 'nullable|string|max:10',
            'utc_offset_minutes' => 'required|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $timeZone->update(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            return redirect()->route('admin.system.time-zones.index')
                ->with('success', 'Time zone updated successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update time zone: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified time zone
     */
    public function destroy(TimeZone $timeZone)
    {
        try {
            $timeZone->delete();
            return response()->json([
                'success' => true,
                'message' => 'Time zone deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete time zone: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete time zones
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No time zone IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $timeZones = TimeZone::whereIn('id', $ids)->get();
            $deletedCount = $timeZones->count();

            foreach ($timeZones as $timeZone) {
                $timeZone->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount time zone(s) deleted successfully."
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