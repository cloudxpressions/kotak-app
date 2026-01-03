<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\SpecialCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class SpecialCategoryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:specialcategory.view', only: ['index']),
            new Middleware('permission:specialcategory.create', only: ['create', 'store']),
            new Middleware('permission:specialcategory.update', only: ['edit', 'update']),
            new Middleware('permission:specialcategory.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of special categories with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $specialCategories = SpecialCategory::query();

            return DataTables::of($specialCategories)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('status_badge', function ($specialCategory) {
                    return $specialCategory->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($specialCategory) {
                    $buttons = '';

                    if (auth('admin')->user()->can('specialcategory.update')) {
                        $buttons .= '<a href="' . route('admin.system.special-categories.edit', $specialCategory->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('specialcategory.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $specialCategory->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.special-categories.index');
    }

    /**
     * Show the form for creating a new special category
     */
    public function create()
    {
        return view('admin.system.special-categories.create');
    }

    /**
     * Store a newly created special category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $specialCategory = SpecialCategory::create(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            return redirect()->route('admin.system.special-categories.index')
                ->with('success', 'Special category created successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create special category: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified special category
     */
    public function edit(SpecialCategory $specialCategory)
    {
        return view('admin.system.special-categories.edit', compact('specialCategory'));
    }

    /**
     * Update the specified special category
     */
    public function update(Request $request, SpecialCategory $specialCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $specialCategory->update(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            return redirect()->route('admin.system.special-categories.index')
                ->with('success', 'Special category updated successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update special category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified special category
     */
    public function destroy(SpecialCategory $specialCategory)
    {
        try {
            $specialCategory->delete();
            return response()->json([
                'success' => true,
                'message' => 'Special category deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete special category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete special categories
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No special category IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $specialCategories = SpecialCategory::whereIn('id', $ids)->get();
            $deletedCount = $specialCategories->count();

            foreach ($specialCategories as $specialCategory) {
                $specialCategory->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount special category(s) deleted successfully."
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