<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class StateController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:state.view', only: ['index', 'activatePage']),
            new Middleware('permission:state.create', only: ['create', 'store']),
            new Middleware('permission:state.update', only: ['edit', 'update', 'bulkActivate']),
            new Middleware('permission:state.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of states with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $states = State::select(['id', 'name', 'country_id', 'is_active'])
                           ->with(['country:id,name']);

            return DataTables::of($states)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%")
                              ->orWhereHas('country', function ($q) use ($search) {
                                  $q->where('name', 'LIKE', "%{$search}%");
                              });
                        });
                    }
                })
                ->addColumn('country_name', function ($state) {
                    return $state->country ? $state->country->name : 'N/A';
                })
                ->addColumn('status_badge', function ($state) {
                    return $state->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($state) {
                    $buttons = '';

                    if (auth('admin')->user()->can('state.update')) {
                        $buttons .= '<a href="' . route('admin.system.states.edit', $state->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('state.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $state->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.states.index');
    }

    /**
     * Show the form for creating a new state
     */
    public function create()
    {
        $countries = Country::select(['id', 'name'])
                           ->where('is_active', 1)
                           ->get();
        return view('admin.system.states.create', compact('countries'));
    }

    /**
     * Store a newly created state
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'country_id' => 'required|exists:countries,id',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $state = State::create(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            DB::commit();

            return redirect()->route('admin.system.states.index')
                ->with('success', 'State created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create state: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified state
     */
    public function edit(State $state)
    {
        $countries = Country::select(['id', 'name'])
                           ->where('is_active', 1)
                           ->orWhere('id', $state->country_id)
                           ->get();
        return view('admin.system.states.edit', compact('state', 'countries'));
    }

    /**
     * Update the specified state
     */
    public function update(Request $request, State $state)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'country_id' => 'required|exists:countries,id',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $state->update(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            DB::commit();

            return redirect()->route('admin.system.states.index')
                ->with('success', 'State updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update state: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified state
     */
    public function destroy(State $state)
    {
        try {
            $state->delete();
            return response()->json([
                'success' => true,
                'message' => 'State deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete state: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete states
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No state IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $states = State::whereIn('id', $ids)->get();
            $deletedCount = $states->count();

            foreach ($states as $state) {
                $state->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount state(s) deleted successfully."
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

    /**
     * Show the bulk activation page
     */
    public function activatePage()
    {
        $countries = Country::select(['id', 'name'])
                           ->withCount([
                               'states',
                               'states as active_states_count' => function ($query) {
                                   $query->where('is_active', 1);
                               }
                           ])->get();

        return view('admin.system.states.activate', compact('countries'));
    }

    /**
     * Bulk activate/deactivate states by country
     */
    public function bulkActivate(Request $request)
    {
        $validated = $request->validate([
            'country_ids' => 'required|array',
            'country_ids.*' => 'exists:countries,id',
            'activate' => 'required|in:true,false,1,0',
        ]);

        DB::beginTransaction();
        try {
            $countryIds = $validated['country_ids'];
            // Convert string/numeric to boolean
            $activate = filter_var($validated['activate'], FILTER_VALIDATE_BOOLEAN);

            $affectedCount = State::whereIn('country_id', $countryIds)
                ->update(['is_active' => $activate]);

            $countries = Country::whereIn('id', $countryIds)->pluck('name')->toArray();
            $countryNames = implode(', ', $countries);

            DB::commit();

            $action = $activate ? 'activated' : 'deactivated';
            $message = "{$affectedCount} state(s) {$action} for: {$countryNames}";

            return response()->json([
                'success' => true,
                'message' => $message,
                'affected_count' => $affectedCount
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Bulk activation failed: ' . $e->getMessage()
            ], 500);
        }
    }
}