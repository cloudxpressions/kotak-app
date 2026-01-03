<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\UserClassification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class UserClassificationController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:userclassification.view', only: ['index']),
            new Middleware('permission:userclassification.create', only: ['create', 'store']),
            new Middleware('permission:userclassification.update', only: ['edit', 'update']),
            new Middleware('permission:userclassification.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of user classifications with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userClassifications = UserClassification::query();

            return DataTables::of($userClassifications)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%")
                              ->orWhere('type', 'LIKE', "%{$search}%")
                              ->orWhere('description', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('status_badge', function ($userClassification) {
                    return $userClassification->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($userClassification) {
                    $buttons = '';

                    if (auth('admin')->user()->can('userclassification.update')) {
                        $buttons .= '<a href="' . route('admin.system.user-classifications.edit', $userClassification->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('userclassification.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $userClassification->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.user-classifications.index');
    }

    /**
     * Show the form for creating a new user classification
     */
    public function create()
    {
        return view('admin.system.user-classifications.create');
    }

    /**
     * Store a newly created user classification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $userClassification = UserClassification::create(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            return redirect()->route('admin.system.user-classifications.index')
                ->with('success', 'User classification created successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create user classification: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user classification
     */
    public function edit(UserClassification $userClassification)
    {
        return view('admin.system.user-classifications.edit', compact('userClassification'));
    }

    /**
     * Update the specified user classification
     */
    public function update(Request $request, UserClassification $userClassification)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $userClassification->update(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            return redirect()->route('admin.system.user-classifications.index')
                ->with('success', 'User classification updated successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update user classification: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user classification
     */
    public function destroy(UserClassification $userClassification)
    {
        try {
            $userClassification->delete();
            return response()->json([
                'success' => true,
                'message' => 'User classification deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user classification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete user classifications
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No user classification IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $userClassifications = UserClassification::whereIn('id', $ids)->get();
            $deletedCount = $userClassifications->count();

            foreach ($userClassifications as $userClassification) {
                $userClassification->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount user classification(s) deleted successfully."
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