<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:country.view', only: ['index']),
            new Middleware('permission:country.create', only: ['create', 'store']),
            new Middleware('permission:country.update', only: ['edit', 'update']),
            new Middleware('permission:country.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of countries with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $countries = Country::select(['id', 'name', 'iso2', 'iso3', 'phonecode', 'capital', 'continent', 'is_active']);

            return DataTables::of($countries)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (! empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('iso2', 'LIKE', "%{$search}%")
                                ->orWhere('iso3', 'LIKE', "%{$search}%")
                                ->orWhere('currency', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('status_badge', function ($country) {
                    return $country->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($country) {
                    $buttons = '';

                    if (auth('admin')->user()->can('country.update')) {
                        $buttons .= '<a href="'.route('admin.system.countries.edit', $country->id).'" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('country.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="'.$country->id.'" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.countries.index');
    }

    /**
     * Show the form for creating a new country
     */
    public function create()
    {
        return view('admin.system.countries.create');
    }

    /**
     * Store a newly created country
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'native_name' => 'nullable|string|max:100',
            'iso3' => 'nullable|string|max:10|unique:countries,iso3',
            'iso2' => 'nullable|string|max:10|unique:countries,iso2',
            'phonecode' => 'nullable|string|max:30',
            'currency' => 'nullable|string|max:30',
            'capital' => 'nullable|string|max:50',
            'continent' => 'nullable|string|max:50',
            'emoji_flag' => 'nullable|string|max:10',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $country = Country::create(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            DB::commit();

            return redirect()->route('admin.system.countries.index')
                ->with('success', 'Country created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create country: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified country
     */
    public function edit(Country $country)
    {
        return view('admin.system.countries.edit', compact('country'));
    }

    /**
     * Update the specified country
     */
    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'native_name' => 'nullable|string|max:100',
            'iso3' => 'nullable|string|max:10|unique:countries,iso3,'.$country->id,
            'iso2' => 'nullable|string|max:10|unique:countries,iso2,'.$country->id,
            'phonecode' => 'nullable|string|max:30',
            'currency' => 'nullable|string|max:30',
            'capital' => 'nullable|string|max:50',
            'continent' => 'nullable|string|max:50',
            'emoji_flag' => 'nullable|string|max:10',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $country->update(array_merge([
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_active'])));

            DB::commit();

            return redirect()->route('admin.system.countries.index')
                ->with('success', 'Country updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update country: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified country
     */
    public function destroy(Country $country)
    {
        try {
            $country->delete();

            return response()->json([
                'success' => true,
                'message' => 'Country deleted successfully',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete country: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete countries
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No country IDs provided.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $countries = Country::whereIn('id', $ids)->get();
            $deletedCount = $countries->count();

            foreach ($countries as $country) {
                $country->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount country(s) deleted successfully.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Bulk deletion failed: '.$e->getMessage(),
            ], 500);
        }
    }
}
