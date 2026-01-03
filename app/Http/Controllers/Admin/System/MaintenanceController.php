<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class MaintenanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:maintenance.view', only: ['index']),
            new Middleware('permission:maintenance.create', only: ['create', 'store']),
            new Middleware('permission:maintenance.update', only: ['edit', 'update']),
            new Middleware('permission:maintenance.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $maintenances = Maintenance::query()->latest('updated_at');

            return DataTables::of($maintenances)
                ->addColumn('status_badge', function (Maintenance $maintenance) {
                    $status = $maintenance->maintenance_mode
                        ? '<span class="badge bg-danger-lt">Active</span>'
                        : '<span class="badge bg-success-lt">Disabled</span>';

                    if ($maintenance->is_emergency) {
                        $status .= '<span class="badge bg-warning-lt ms-1">Emergency</span>';
                    }

                    return $status;
                })
                ->addColumn('schedule', function (Maintenance $maintenance) {
                    $start = $maintenance->starts_at ? $maintenance->starts_at->format('d M Y H:i') : '--';
                    $end = $maintenance->ends_at ? $maintenance->ends_at->format('d M Y H:i') : '--';
                    return "<div><strong>Start:</strong> {$start}</div><div><strong>End:</strong> {$end}</div>";
                })
                ->addColumn('action', function (Maintenance $maintenance) {
                    $buttons = '';
                    if (auth('admin')->user()->can('maintenance.update')) {
                        $buttons .= sprintf(
                            '<a href="%s" class="btn btn-sm btn-icon btn-primary me-1" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>',
                            route('admin.system.maintenances.edit', $maintenance)
                        );
                    }

                    if (auth('admin')->user()->can('maintenance.delete')) {
                        $buttons .= sprintf(
                            '<button type="button" class="btn btn-sm btn-icon btn-danger delete-maintenance" data-url="%s" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>',
                            route('admin.system.maintenances.destroy', $maintenance)
                        );
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->editColumn('updated_at', function (Maintenance $maintenance) {
                    return $maintenance->updated_at?->format('d M Y H:i');
                })
                ->rawColumns(['status_badge', 'schedule', 'action'])
                ->make(true);
        }

        return view('admin.system.maintenances.index');
    }

    public function create()
    {
        return view('admin.system.maintenances.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        Maintenance::create($data);

        return redirect()
            ->route('admin.system.maintenances.index')
            ->with('success', 'Maintenance entry created successfully.');
    }

    public function edit(Maintenance $maintenance)
    {
        return view('admin.system.maintenances.edit', compact('maintenance'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $data = $this->validatedData($request, $maintenance->id);
        $maintenance->update($data);

        return redirect()
            ->route('admin.system.maintenances.index')
            ->with('success', 'Maintenance entry updated successfully.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return response()->json([
            'message' => 'Maintenance entry deleted successfully.',
        ]);
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $validated = $request->validate([
            'maintenance_mode' => ['nullable', 'boolean'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'maintenance_page_banner' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'allowed_ips' => ['nullable', 'string'],
            'is_emergency' => ['nullable', 'boolean'],
        ]);

        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');
        $validated['is_emergency'] = $request->boolean('is_emergency');

        $ips = $this->parseAllowedIps($request->input('allowed_ips'));
        $validated['allowed_ips'] = $ips ?: null;

        return $validated;
    }

    private function parseAllowedIps(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        $ips = preg_split('/[\n,]+/', $value);
        return array_values(array_filter(array_map('trim', $ips)));
    }
}
