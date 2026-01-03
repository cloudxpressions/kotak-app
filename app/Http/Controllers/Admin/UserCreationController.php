<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

class UserCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email', 'email_verified_at', 'created_at']);

            return DataTables::of($users)
                ->addColumn('status_badge', function ($user) {
                    return $user->email_verified_at 
                        ? '<span class="badge bg-success-lt">Verified</span>'
                        : '<span class="badge bg-warning-lt">Unverified</span>';
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at->format('d M Y');
                })
                ->addColumn('action', function ($user) {
                    return view('admin.partials.action-buttons', [
                        'id' => $user->id,
                        'detailsRoute' => route('admin.users.details', $user->id),
                        'editRoute' => route('admin.users.edit', $user->id),
                        'canEdit' => auth()->user()->can('user.edit'),
                        'canDelete' => auth()->user()->can('user.delete'),
                    ])->render();
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user = User::select(['id', 'name', 'email'])
                    ->where('id', $user->id)
                    ->firstOrFail();

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user = User::select(['id', 'email'])->where('id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user = User::select(['id'])->where('id', $user->id)->firstOrFail();
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Display a listing of the deletion requests.
     */
    public function deletionRequests(Request $request)
    {
        if ($request->ajax()) {
            $users = User::whereNotNull('delete_request_at')
                ->select(['id', 'name', 'email', 'delete_request_at', 'delete_request_reason']);

            return DataTables::of($users)
                ->editColumn('delete_request_at', function ($user) {
                    return \Carbon\Carbon::parse($user->delete_request_at)->format('d M Y, h:i A');
                })
                ->addColumn('action', function ($user) {
                    $approveUrl = route('admin.users.approve-deletion', $user->id);
                    $rejectUrl = route('admin.users.reject-deletion', $user->id);

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

        return view('admin.users.deletion-requests');
    }

    /**
     * Approve the deletion request and delete the account.
     */
    public function approveDeletion($id)
    {
        $user = User::select(['id', 'name', 'email'])->findOrFail($id);

        // Send Email Notification
        $user->notify(new \App\Notifications\AccountDeletionApproved());

        $user->delete();

        return back()->with('success', 'Account deleted successfully.');
    }

    /**
     * Reject the deletion request.
     */
    public function rejectDeletion($id)
    {
        $user = User::select(['id', 'name', 'email'])->findOrFail($id);

        $user->update([
            'delete_request_at' => null,
            'delete_request_reason' => null,
        ]);

        // Send Notification
        $user->notify(new \App\Notifications\AccountDeletionRejected());

        return back()->with('success', 'Deletion request rejected.');
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $count = User::whereIn('id', $request->ids)->count();
        User::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => $count . ' user(s) deleted successfully'
        ]);
    }
}
