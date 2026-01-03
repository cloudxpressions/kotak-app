<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class AdminCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $admins = Admin::with('roles')->select(['id', 'name', 'email', 'email_verified_at', 'created_at']);

            return DataTables::of($admins)
                ->addColumn('roles_badge', function ($admin) {
                    return $admin->roles->map(function ($role) {
                        return '<span class="badge bg-blue-lt">' . $role->name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('status_badge', function ($admin) {
                    return $admin->email_verified_at 
                        ? '<span class="badge bg-success-lt">Verified</span>'
                        : '<span class="badge bg-warning-lt">Unverified</span>';
                })
                ->editColumn('created_at', function ($admin) {
                    return $admin->created_at->format('d M Y');
                })
                ->addColumn('action', function ($admin) {
                    return view('admin.partials.action-buttons', [
                        'id' => $admin->id,
                        'detailsRoute' => route('admin.admins.details', $admin->id),
                        'editRoute' => route('admin.admins.edit', $admin->id),
                        'canEdit' => auth()->user()->can('admin.edit'),
                        'canDelete' => auth()->user()->can('admin.delete') && !$admin->hasRole('Super Admin'),
                    ])->render();
                })
                ->rawColumns(['roles_badge', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.admins.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $admin->syncRoles($request->roles);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        $admin = Admin::select(['id', 'name', 'email'])->with('roles')->where('id', $admin->id)->firstOrFail();
        $roles = Role::where('guard_name', 'admin')->get();
        $adminRoles = $admin->roles->pluck('name')->toArray();
        return view('admin.admins.edit', compact('admin', 'roles', 'adminRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $admin = Admin::select(['id', 'email'])->where('id', $admin->id)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $admin->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $admin->syncRoles($request->roles);

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin = Admin::select(['id'])->with('roles')->where('id', $admin->id)->firstOrFail();

        if ($admin->hasRole('Super Admin')) {
            return back()->with('error', 'Cannot delete Super Admin.');
        }
        $admin->delete();
        return back()->with('success', 'Admin deleted successfully.');
    }

    /**
     * Display a listing of the deletion requests.
     */
    public function deletionRequests(Request $request)
    {
        if ($request->ajax()) {
            $admins = Admin::whereNotNull('delete_request_at')
                ->select(['id', 'name', 'email', 'delete_request_at', 'delete_request_reason']);

            return DataTables::of($admins)
                ->editColumn('delete_request_at', function ($admin) {
                    return \Carbon\Carbon::parse($admin->delete_request_at)->format('d M Y, h:i A');
                })
                ->addColumn('action', function ($admin) {
                    $approveUrl = route('admin.admins.approve-deletion', $admin->id);
                    $rejectUrl = route('admin.admins.reject-deletion', $admin->id);

                    return '
                        <div class="btn-group">
                            <form action="'.$approveUrl.'" method="POST" onsubmit="return confirm(\'Are you sure you want to permanently delete this account?\');" style="display:inline;">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-sm btn-danger">Approve & Delete</button>
                            </form>
                            <form action="'.$rejectUrl.'" method="POST" style="display:inline;">
                                '.csrf_field().'
                                '.method_field('PATCH').'
                                <button type="submit" class="btn btn-sm btn-secondary ms-1">Reject</button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.admins.deletion-requests');
    }

    /**
     * Approve the deletion request and delete the account.
     */
    public function approveDeletion($id)
    {
        $admin = Admin::select(['id', 'name', 'email'])->with('roles')->findOrFail($id);

        if ($admin->hasRole('Super Admin')) {
            return back()->with('error', 'Cannot delete Super Admin account.');
        }

        // Send Email Notification
        $admin->notify(new \App\Notifications\AccountDeletionApproved());

        $admin->delete();

        return back()->with('success', 'Account deleted successfully.');
    }

    /**
     * Reject the deletion request.
     */
    public function rejectDeletion($id)
    {
        $admin = Admin::select(['id', 'name', 'email'])->findOrFail($id);

        $admin->update([
            'delete_request_at' => null,
            'delete_request_reason' => null,
        ]);

        // Send Notification
        $admin->notify(new \App\Notifications\AccountDeletionRejected());

        return back()->with('success', 'Deletion request rejected.');
    }

    /**
     * Bulk delete admins.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:admins,id'
        ]);

        $requestIds = $request->ids;

        // Efficiently check for Super Admins
        $superAdminIds = Admin::select('id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Super Admin');
            })
            ->whereIn('id', $requestIds)
            ->pluck('id')
            ->toArray();

        $idsToDelete = array_diff($requestIds, $superAdminIds);

        $count = 0;
        if (!empty($idsToDelete)) {
            $count = Admin::whereIn('id', $idsToDelete)->delete();
        }

        $message = $count . ' admin(s) deleted successfully';
        if (!empty($superAdminIds)) {
            $message .= ' (Super Admins were skipped)';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
