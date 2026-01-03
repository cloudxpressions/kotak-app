<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountDeletionController extends Controller
{
    /**
     * Display a listing of the deletion requests.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $adminRequests = Admin::whereNotNull('delete_request_at')
                ->select(['id', 'name', 'email', 'delete_request_at', 'delete_request_reason', DB::raw("'Admin' as type")]);
            
            $userRequests = User::whereNotNull('delete_request_at')
                ->select(['id', 'name', 'email', 'delete_request_at', 'delete_request_reason', DB::raw("'User' as type")]);

            $query = $adminRequests->union($userRequests);

            return DataTables::of($query)
                ->editColumn('delete_request_at', function ($row) {
                    return \Carbon\Carbon::parse($row->delete_request_at)->format('d M Y, h:i A');
                })
                ->addColumn('action', function ($row) {
                    $approveUrl = route('admin.system.account-deletions.approve', ['id' => $row->id, 'type' => $row->type]);
                    $rejectUrl = route('admin.system.account-deletions.reject', ['id' => $row->id, 'type' => $row->type]);
                    
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

        return view('admin.system.account-deletions.index');
    }

    /**
     * Approve the deletion request and delete the account.
     */
    public function approve($id, $type)
    {
        if ($type === 'Admin') {
            $account = Admin::findOrFail($id);
            if ($account->hasRole('Super Admin')) {
                return back()->with('error', 'Cannot delete Super Admin account.');
            }
        } else {
            $account = User::findOrFail($id);
        }

        // Send Email Notification
        $account->notify(new \App\Notifications\AccountDeletionApproved());

        $account->delete();

        return back()->with('success', 'Account deleted successfully.');
    }

    /**
     * Reject the deletion request.
     */
    public function reject($id, $type)
    {
        if ($type === 'Admin') {
            $account = Admin::findOrFail($id);
        } else {
            $account = User::findOrFail($id);
        }

        $account->update([
            'delete_request_at' => null,
            'delete_request_reason' => null,
        ]);

        // Send Notification
        $account->notify(new \App\Notifications\AccountDeletionRejected());

        return back()->with('success', 'Deletion request rejected.');
    }
}
