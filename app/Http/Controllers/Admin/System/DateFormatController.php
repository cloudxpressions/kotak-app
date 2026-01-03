<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\DateFormat;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class DateFormatController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:dateformat.view', only: ['index']),
            new Middleware('permission:dateformat.create', only: ['create', 'store']),
            new Middleware('permission:dateformat.update', only: ['edit', 'update']),
            new Middleware('permission:dateformat.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of date formats with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dateFormats = DateFormat::query();

            return DataTables::of($dateFormats)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('format', 'LIKE', "%{$search}%")
                              ->orWhere('normal_view', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('status_badge', function ($dateFormat) {
                    return $dateFormat->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($dateFormat) {
                    $buttons = '';

                    if (auth('admin')->user()->can('dateformat.update')) {
                        $buttons .= '<a href="' . route('admin.system.date-formats.edit', $dateFormat->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('dateformat.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $dateFormat->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.date-formats.index');
    }

    /**
     * Show the form for creating a new date format
     */
    public function create()
    {
        return view('admin.system.date-formats.create');
    }

    /**
     * Store a newly created date format
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|string|max:255',
            'normal_view' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $dateFormat = DateFormat::create(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            return redirect()->route('admin.system.date-formats.index')
                ->with('success', 'Date format created successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create date format: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified date format
     */
    public function edit(DateFormat $dateFormat)
    {
        return view('admin.system.date-formats.edit', compact('dateFormat'));
    }

    /**
     * Update the specified date format
     */
    public function update(Request $request, DateFormat $dateFormat)
    {
        $validated = $request->validate([
            'format' => 'required|string|max:255',
            'normal_view' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $dateFormat->update(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            return redirect()->route('admin.system.date-formats.index')
                ->with('success', 'Date format updated successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update date format: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified date format
     */
    public function destroy(DateFormat $dateFormat)
    {
        try {
            $dateFormat->delete();
            return response()->json([
                'success' => true,
                'message' => 'Date format deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete date format: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete date formats
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No date format IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $dateFormats = DateFormat::whereIn('id', $ids)->get();
            $deletedCount = $dateFormats->count();

            foreach ($dateFormats as $dateFormat) {
                $dateFormat->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount date format(s) deleted successfully."
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