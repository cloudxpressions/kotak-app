<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:currency.view', only: ['index']),
            new Middleware('permission:currency.create', only: ['create', 'store']),
            new Middleware('permission:currency.update', only: ['edit', 'update']),
            new Middleware('permission:currency.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of currencies with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currencies = Currency::query();

            return DataTables::of($currencies)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%")
                              ->orWhere('code', 'LIKE', "%{$search}%")
                              ->orWhere('symbol', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('default_badge', function ($currency) {
                    return $currency->is_default
                        ? '<span class="badge bg-primary-lt">Default</span>'
                        : '<span class="badge bg-secondary-lt">No</span>';
                })
                ->addColumn('status_badge', function ($currency) {
                    return $currency->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($currency) {
                    $buttons = '';

                    if (auth('admin')->user()->can('currency.update')) {
                        $buttons .= '<a href="' . route('admin.system.currencies.edit', $currency->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('currency.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $currency->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['default_badge', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.currencies.index');
    }

    /**
     * Show the form for creating a new currency
     */
    public function create()
    {
        return view('admin.system.currencies.create');
    }

    /**
     * Store a newly created currency
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:currencies,code',
            'symbol' => 'nullable|string|max:10',
            'conversion_rate' => 'required|numeric|min:0',
            'symbol_position' => 'required|in:before,after',
            'decimal_places' => 'required|integer|min:0|max:10',
            'is_default' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            // If this is set as default, unset other defaults
            if ($request->has('is_default')) {
                Currency::where('is_default', true)->update(['is_default' => false]);
            }

            $currency = Currency::create(array_merge([
                'is_default' => $request->has('is_default'),
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_default', 'is_active'])));

            DB::commit();

            return redirect()->route('admin.system.currencies.index')
                ->with('success', 'Currency created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create currency: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified currency
     */
    public function edit(Currency $currency)
    {
        return view('admin.system.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified currency
     */
    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:currencies,code,' . $currency->id,
            'symbol' => 'nullable|string|max:10',
            'conversion_rate' => 'required|numeric|min:0',
            'symbol_position' => 'required|in:before,after',
            'decimal_places' => 'required|integer|min:0|max:10',
            'is_default' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            // If this is set as default, unset other defaults
            if ($request->has('is_default')) {
                Currency::where('id', '!=', $currency->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $currency->update(array_merge([
                'is_default' => $request->has('is_default'),
                'is_active' => $request->has('is_active'),
            ], Arr::except($validated, ['is_default', 'is_active'])));

            DB::commit();

            return redirect()->route('admin.system.currencies.index')
                ->with('success', 'Currency updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update currency: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified currency
     */
    public function destroy(Currency $currency)
    {
        // Prevent deletion of default currency
        if ($currency->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the default currency.'
            ], 403);
        }

        try {
            $currency->delete();
            return response()->json([
                'success' => true,
                'message' => 'Currency deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete currency: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete currencies
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No currency IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Get currencies that can be deleted (not default)
            $currencies = Currency::whereIn('id', $ids)
                ->where('is_default', false)
                ->get();

            $deletedCount = $currencies->count();

            foreach ($currencies as $currency) {
                $currency->delete();
            }

            DB::commit();

            $notDeletedCount = count($ids) - $deletedCount;
            $message = "$deletedCount currency(s) deleted successfully.";

            if ($notDeletedCount > 0) {
                $message .= " $notDeletedCount currency(s) (including default) were not deleted.";
            }

            return response()->json([
                'success' => true,
                'message' => $message
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
}