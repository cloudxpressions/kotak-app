<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RolePermissionController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:role.view', only: ['index']),
            new Middleware('permission:role.create', only: ['store']),
            new Middleware('permission:role.update', only: ['update']),
            new Middleware('permission:role.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Load permissions grouped
            if ($request->get('load_permissions')) {
                return response()->json([
                    'permissions' => Permission::where('guard_name', 'admin')
                        ->get()
                        ->groupBy('group_name')
                ]);
            }

            // DataTables
            $roles = Role::where('guard_name', 'admin')->with('permissions')->get();
            
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('permissions_count', function ($role) {
                    $count = $role->permissions->count();
                    return '<span class="badge bg-primary-lt">' . $count . ' permissions</span>';
                })
                ->addColumn('created_at', function ($role) {
                    return $role->created_at->format('Y-m-d H:i');
                })
                ->addColumn('action', function ($role) {
                    $buttons = '';
                    
                    if (auth('admin')->user()->can('role.update')) {
                        $buttons .= '<a href="' . route('admin.system.roles.edit', $role->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }
                    
                    if (auth('admin')->user()->can('role.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $role->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }
                    
                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['permissions_count', 'action'])
                ->make(true);
        }

        return view('admin.system.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissionGroups = Permission::where('guard_name', 'admin')
            ->get()
            ->groupBy('group_name');

        return view('admin.system.roles.create', compact('permissionGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'admin'
            ]);

            if ($request->filled('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            return redirect()->route('admin.system.roles.index')
                ->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        
        $permissionGroups = Permission::where('guard_name', 'admin')
            ->get()
            ->groupBy('group_name');
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.system.roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);
            $role->update(['name' => $request->name]);

            $role->syncPermissions($request->permissions ?? []);

            DB::commit();

            return redirect()->route('admin.system.roles.index')
                ->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);

            // Protect Super Admin
            if ($role->name === 'Super Admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete Super Admin role'
                ], 403);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete roles.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:roles,id'
        ]);

        try {
            $roles = Role::whereIn('id', $request->ids)->get();
            $deleted = 0;
            $skipped = 0;

            foreach ($roles as $role) {
                if ($role->name === 'Super Admin') {
                    $skipped++;
                    continue;
                }

                $role->delete();
                $deleted++;
            }

            $message = "Deleted {$deleted} role(s)";
            if ($skipped > 0) {
                $message .= ". Skipped {$skipped} protected role(s)";
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
