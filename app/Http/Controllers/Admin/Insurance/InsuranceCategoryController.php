<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\InsuranceCategory;
use App\Models\InsuranceCategoryTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InsuranceCategoryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:insurance_category.view', only: ['index']),
            new Middleware('permission:insurance_category.create', only: ['create', 'store']),
            new Middleware('permission:insurance_category.update', only: ['edit', 'update']),
            new Middleware('permission:insurance_category.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the insurance categories.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = InsuranceCategory::select(['id', 'is_active'])->with('translations:id,insurance_category_id,language_code,name')->get();
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('name', function($category) use ($languages) {
                    $names = [];
                    foreach ($languages as $lang) {
                        $translation = $category->translations->where('language_code', $lang->code)->first();
                        $names[] = $lang->name . ': ' . ($translation?->name ?? '-');
                    }
                    return implode('<br>', $names);
                })
                ->addColumn('status_badge', function ($category) {
                    return $category->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($category) {
                    $buttons = '';

                    if (auth('admin')->user()->can('insurance_category.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.categories.edit', $category->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('insurance_category.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $category->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['name', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.categories.index');
    }

    /**
     * Show the form for creating a new insurance category.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        return view('admin.insurance.categories.create', compact('languages'));
    }

    /**
     * Store a newly created insurance category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:insurance_categories,slug',
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $category = InsuranceCategory::create([
                'slug' => $validated['slug'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                InsuranceCategoryTranslation::create([
                    'insurance_category_id' => $category->id,
                    'language_code' => $translationData['language_code'],
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.categories.index')
                ->with('success', 'Insurance category created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create insurance category: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified insurance category.
     */
    public function edit(InsuranceCategory $category)
    {
        $category = InsuranceCategory::select(['id', 'slug', 'order_no', 'is_active'])->where('id', $category->id)->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $category->load('translations');
        return view('admin.insurance.categories.edit', compact('category', 'languages'));
    }

    /**
     * Update the specified insurance category in storage.
     */
    public function update(Request $request, InsuranceCategory $category)
    {
        $category = InsuranceCategory::select(['id'])->where('id', $category->id)->firstOrFail();

        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:insurance_categories,slug,' . $category->id,
            'order_no' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $category->update([
                'slug' => $validated['slug'],
                'order_no' => $validated['order_no'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                InsuranceCategoryTranslation::updateOrCreate(
                    [
                        'insurance_category_id' => $category->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'name' => $translationData['name'],
                        'description' => $translationData['description'] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.categories.index')
                ->with('success', 'Insurance category updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update insurance category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified insurance category from storage.
     */
    public function destroy(InsuranceCategory $category)
    {
        $category = InsuranceCategory::select(['id'])->where('id', $category->id)->firstOrFail();

        try {
            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Insurance category deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete insurance category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete insurance categories
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No category IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = InsuranceCategory::whereIn('id', $ids)->count();
            InsuranceCategory::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount category(s) deleted successfully."
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