<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\DACategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class DACategoryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:dacategory.view', only: ['index']),
            new Middleware('permission:dacategory.create', only: ['create', 'store']),
            new Middleware('permission:dacategory.update', only: ['edit', 'update']),
            new Middleware('permission:dacategory.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of disability categories with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $daCategories = DACategory::query();

            return DataTables::of($daCategories)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%")
                              ->orWhere('code', 'LIKE', "%{$search}%")
                              ->orWhere('description', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('action', function ($daCategory) {
                    $buttons = '';

                    if (auth('admin')->user()->can('dacategory.update')) {
                        $buttons .= '<a href="' . route('admin.system.da-categories.edit', $daCategory->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('dacategory.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $daCategory->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.system.da-categories.index');
    }

    /**
     * Show the form for creating a new disability category
     */
    public function create()
    {
        return view('admin.system.da-categories.create');
    }

    /**
     * Store a newly created disability category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'severity_level' => 'nullable|in:mild,moderate,severe',
            'percentage' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            $daCategory = DACategory::create($validated);

            return redirect()->route('admin.system.da-categories.index')
                ->with('success', 'Disability category created successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create disability category: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified disability category
     */
    public function edit(DACategory $daCategory)
    {
        return view('admin.system.da-categories.edit', compact('daCategory'));
    }

    /**
     * Update the specified disability category
     */
    public function update(Request $request, DACategory $daCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'severity_level' => 'nullable|in:mild,moderate,severe',
            'percentage' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            $daCategory->update($validated);

            return redirect()->route('admin.system.da-categories.index')
                ->with('success', 'Disability category updated successfully');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update disability category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified disability category
     */
    public function destroy(DACategory $daCategory)
    {
        try {
            $daCategory->delete();
            return response()->json([
                'success' => true,
                'message' => 'Disability category deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete disability category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete disability categories
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No disability category IDs provided.'
            ], 422);
        }

        try {
            $daCategories = DACategory::whereIn('id', $ids)->get();
            $deletedCount = $daCategories->count();

            foreach ($daCategories as $daCategory) {
                $daCategory->delete();
            }

            return response()->json([
                'success' => true,
                'message' => "$deletedCount disability category(s) deleted successfully."
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Bulk deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}