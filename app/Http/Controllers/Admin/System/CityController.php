<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:city.view', only: ['index', 'activatePage']),
            new Middleware('permission:city.create', only: ['create', 'store']),
            new Middleware('permission:city.update', only: ['edit', 'update', 'bulkActivate']),
            new Middleware('permission:city.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of cities with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cities = City::select(['id', 'name', 'state_id', 'country_id', 'is_district_hq', 'is_active'])
                          ->with(['state:id,name', 'country:id,name']);

            return DataTables::of($cities)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%")
                              ->orWhereHas('state', function ($q) use ($search) {
                                  $q->where('name', 'LIKE', "%{$search}%");
                              })
                              ->orWhereHas('country', function ($q) use ($search) {
                                  $q->where('name', 'LIKE', "%{$search}%");
                              });
                        });
                    }
                })
                ->addColumn('state_name', function ($city) {
                    return $city->state ? $city->state->name : 'N/A';
                })
                ->addColumn('country_name', function ($city) {
                    return $city->country ? $city->country->name : 'N/A';
                })
                ->addColumn('status_badge', function ($city) {
                    return $city->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($city) {
                    $buttons = '';

                    if (auth('admin')->user()->can('city.update')) {
                        $buttons .= '<a href="' . route('admin.system.cities.edit', $city->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('city.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $city->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.cities.index');
    }

    /**
     * Show the form for creating a new city
     */
    public function create()
    {
        $countries = Country::select(['id', 'name'])
                           ->where('is_active', 1)
                           ->get();
        $states = State::select(['id', 'name'])
                      ->where('is_active', 1)
                      ->get();
        return view('admin.system.cities.create', compact('countries', 'states'));
    }

    /**
     * Store a newly created city
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_district_hq' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $city = City::create(array_merge([
                'is_district_hq' => $request->has('is_district_hq'),
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_district_hq', 'is_active'])));

            DB::commit();

            return redirect()->route('admin.system.cities.index')
                ->with('success', 'City created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create city: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified city
     */
    public function edit(City $city)
    {
        $countries = Country::select(['id', 'name'])
                           ->where('is_active', 1)
                           ->orWhere('id', $city->country_id)
                           ->get();

        $states = State::select(['id', 'name'])
                      ->where('country_id', $city->country_id)
            ->where(function ($query) use ($city) {
                $query->where('is_active', 1)
                    ->orWhere('id', $city->state_id);
            })
            ->get();

        return view('admin.system.cities.edit', compact('city', 'countries', 'states'));
    }

    /**
     * Update the specified city
     */
    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_district_hq' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $city->update(array_merge([
                'is_district_hq' => $request->has('is_district_hq'),
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_district_hq', 'is_active'])));

            DB::commit();

            return redirect()->route('admin.system.cities.index')
                ->with('success', 'City updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update city: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified city
     */
    public function destroy(City $city)
    {
        try {
            $city->delete();
            return response()->json([
                'success' => true,
                'message' => 'City deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete city: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get states by country ID for AJAX requests
     */
    public function getStatesByCountry($countryId)
    {
        $states = State::where('country_id', $countryId)
                      ->where('is_active', 1)
                      ->select('id', 'name')
                      ->get();

        return response()->json($states);
    }

    /**
     * Get cities by state ID for AJAX requests
     */
    public function getCitiesByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
                      ->where('is_active', 1)
                      ->select('id', 'name')
                      ->get();

        return response()->json($cities);
    }

    /**
     * Bulk delete cities
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No city IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $cities = City::whereIn('id', $ids)->get();
            $deletedCount = $cities->count();

            foreach ($cities as $city) {
                $city->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount city(s) deleted successfully."
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
                           ->with(['states' => function ($query) {
                               $query->select(['id', 'country_id', 'name'])
                                     ->withCount([
                                         'cities',
                                         'cities as active_cities_count' => function ($q) {
                                             $q->where('is_active', 1);
                                         }
                                     ])->orderBy('name');
                           }])->orderBy('name')->get();

        return view('admin.system.cities.activate', compact('countries'));
    }

    /**
     * Bulk activate/deactivate cities by state
     */
    public function bulkActivate(Request $request)
    {
        $validated = $request->validate([
            'state_ids' => 'required|array',
            'state_ids.*' => 'exists:states,id',
            'activate' => 'required|in:true,false,1,0',
        ]);

        DB::beginTransaction();
        try {
            $stateIds = $validated['state_ids'];
            // Convert string/numeric to boolean
            $activate = filter_var($validated['activate'], FILTER_VALIDATE_BOOLEAN);

            $affectedCount = City::whereIn('state_id', $stateIds)
                ->update(['is_active' => $activate]);

            $states = State::select(['id', 'name', 'country_id'])
                          ->whereIn('id', $stateIds)
                          ->with(['country:id,name'])
                          ->get();
            $stateNames = $states->pluck('name')->toArray();
            $stateList = implode(', ', $stateNames);

            DB::commit();

            $action = $activate ? 'activated' : 'deactivated';
            $message = "{$affectedCount} city(s) {$action} for: {$stateList}";

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