<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserSessionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user-session.view', only: ['index']),
            new Middleware('permission:user-session.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sessions = UserSession::with('authenticatable')->latest('login_at');

            return DataTables::of($sessions)
                ->addColumn('user_info', function (UserSession $session) {
                    $auth = $session->authenticatable;

                    if (! $auth) {
                        return '<span class="text-muted">Actor deleted</span>';
                    }

                    if ($auth instanceof Admin) {
                        return sprintf(
                            '<div class="fw-bold">%s</div><small class="text-muted">Admin</small>',
                            e($auth->name ?? 'Admin')
                        );
                    }

                    return sprintf(
                        '<div class="fw-bold">%s</div><small class="text-muted">%s</small>',
                        e($auth->name),
                        e($auth->email ?? 'User')
                    );
                })
                ->addColumn('status_badge', function (UserSession $session) {
                    if (!$session->is_active) {
                        return '<span class="badge bg-secondary-lt">Ended</span>';
                    }
                    if ($session->revoked_at) {
                        return '<span class="badge bg-danger-lt">Revoked</span>';
                    }
                    return '<span class="badge bg-success-lt">Active</span>';
                })
                ->addColumn('action', function (UserSession $session) {
                    if (!auth('admin')->user()->can('user-session.delete')) {
                        return '<span class="text-muted">No actions</span>';
                    }

                    if (!$session->is_active) {
                        return '<span class="badge bg-secondary-lt">Closed</span>';
                    }

                    return sprintf(
                        '<button type="button" class="btn btn-sm btn-icon btn-danger revoke-session" data-url="%s" title="Force logout"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg></button>',
                        route('admin.system.user-sessions.destroy', $session)
                    );
                })
                ->editColumn('login_at', fn (UserSession $session) => $session->login_at ? $session->login_at->format('d M Y H:i') : '--')
                ->editColumn('last_seen_at', fn (UserSession $session) => $session->last_seen_at ? $session->last_seen_at->format('d M Y H:i') : '--')
                ->rawColumns(['user_info', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.user-sessions.index');
    }

    public function destroy(UserSession $userSession, Request $request)
    {
        $userSession->update([
            'is_active' => false,
            'revoked_at' => Carbon::now(),
            'revoked_reason' => $request->input('reason'),
            'logout_at' => $userSession->logout_at ?? Carbon::now(),
        ]);

        if (! empty($userSession->session_token)) {
            DB::table('sessions')->where('id', $userSession->session_token)->delete();
        }

        return response()->json([
            'message' => 'Session revoked successfully.',
        ]);
    }
}
