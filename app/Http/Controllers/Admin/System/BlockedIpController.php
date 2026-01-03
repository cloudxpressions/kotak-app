<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class BlockedIpController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:blocked-ip.view', only: ['index']),
            new Middleware('permission:blocked-ip.create', only: ['create', 'store']),
            new Middleware('permission:blocked-ip.update', only: ['edit', 'update']),
            new Middleware('permission:blocked-ip.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $blockedIps = BlockedIp::query()->latest();

            return DataTables::of($blockedIps)
                ->addColumn('status_badge', function (BlockedIp $blockedIp) {
                    if ($blockedIp->is_permanent) {
                        return '<span class="badge bg-danger-lt">Permanent</span>';
                    }

                    if ($blockedIp->blocked_until && $blockedIp->blocked_until->isFuture()) {
                        return '<span class="badge bg-warning-lt">Temporary</span>';
                    }

                    return '<span class="badge bg-success-lt">Expired</span>';
                })
                ->addColumn('action', function (BlockedIp $blockedIp) {
                    $buttons = '';
                    if (auth('admin')->user()->can('blocked-ip.update')) {
                        $buttons .= sprintf(
                            '<a href="%s" class="btn btn-sm btn-icon btn-primary me-1" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>',
                            route('admin.system.blocked-ips.edit', $blockedIp)
                        );
                    }

                    if (auth('admin')->user()->can('blocked-ip.delete')) {
                        $buttons .= sprintf(
                            '<button type="button" class="btn btn-sm btn-icon btn-danger delete-blocked-ip" data-url="%s" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>',
                            route('admin.system.blocked-ips.destroy', $blockedIp)
                        );
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->editColumn('blocked_until', function (BlockedIp $blockedIp) {
                    return $blockedIp->blocked_until ? $blockedIp->blocked_until->format('d M Y H:i') : '--';
                })
                ->editColumn('last_attempt_at', function (BlockedIp $blockedIp) {
                    return $blockedIp->last_attempt_at ? $blockedIp->last_attempt_at->format('d M Y H:i') : '--';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.blocked-ips.index');
    }

    public function create()
    {
        return view('admin.system.blocked-ips.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $adminId = auth('admin')->id();
        $data['created_by'] = $adminId;
        $data['updated_by'] = $adminId;

        BlockedIp::create($data);

        return redirect()
            ->route('admin.system.blocked-ips.index')
            ->with('success', 'IP address blocked successfully.');
    }

    public function edit(BlockedIp $blockedIp)
    {
        return view('admin.system.blocked-ips.edit', compact('blockedIp'));
    }

    public function update(Request $request, BlockedIp $blockedIp)
    {
        $data = $this->validatedData($request, $blockedIp->id);
        $data['updated_by'] = auth('admin')->id();

        $blockedIp->update($data);

        return redirect()
            ->route('admin.system.blocked-ips.index')
            ->with('success', 'Blocked IP updated successfully.');
    }

    public function destroy(BlockedIp $blockedIp)
    {
        $blockedIp->delete();

        return response()->json([
            'message' => 'Blocked IP deleted successfully.',
        ]);
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'ip_address' => ['required', 'ip', 'unique:blocked_ips,ip_address,' . $id],
            'reason' => ['nullable', 'string'],
            'blocked_until' => ['nullable', 'date'],
            'is_permanent' => ['nullable', 'boolean'],
            'attempts_count' => ['nullable', 'integer', 'min:0'],
            'last_attempt_at' => ['nullable', 'date'],
            'user_agent' => ['nullable', 'string'],
        ]);

        $data['is_permanent'] = $request->boolean('is_permanent');
        $data['attempts_count'] = $data['attempts_count'] ?? 0;

        return $data;
    }
}
