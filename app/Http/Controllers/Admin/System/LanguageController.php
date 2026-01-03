<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class LanguageController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:language.view', only: ['index']),
            new Middleware('permission:language.create', only: ['create', 'store']),
            new Middleware('permission:language.update', only: ['edit', 'update']),
            new Middleware('permission:language.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of languages with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $languages = Language::query();
            
            return DataTables::of($languages)
                ->addIndexColumn()
->filter(function ($query) {
    $search = request('search')['value'] ?? null;

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('native_name', 'LIKE', "%{$search}%")
              ->orWhere('code', 'LIKE', "%{$search}%")
              ->orWhere('direction', 'LIKE', "%{$search}%");
        });
    }
})


                ->addColumn('default_badge', function ($language) {
                    return $language->is_default
                        ? '<span class="badge bg-primary-lt">Default</span>'
                        : '<span class="badge bg-secondary-lt">No</span>';
                })
                ->addColumn('status_badge', function ($language) {
                    return $language->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($language) {
                    $buttons = '';
                    
                    if (auth('admin')->user()->can('language.update')) {
                        $buttons .= '<a href="' . route('admin.system.languages.edit', $language->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }
                    
                    if (auth('admin')->user()->can('language.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $language->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }
                    
                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['default_badge', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.languages.index');
    }

    /**
     * Show the form for creating a new language
     */
    public function create()
    {
        return view('admin.system.languages.create');
    }

    /**
     * Store a newly created language
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'native_name' => 'nullable|string|max:100',
            'code' => 'required|string|max:10|unique:languages,code',
            'slug' => 'nullable|string|max:20',
            'direction' => 'required|in:ltr,rtl',
            'is_default' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            // If this is set as default, unset other defaults
            if ($request->has('is_default')) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }

            $language = Language::create(array_merge([
                'is_default' => $request->boolean('is_default', false),
                'is_active' => $request->boolean('is_active', false),
            ], Arr::except($validated, ['is_default', 'is_active'])));

            DB::commit();
            
            return redirect()->route('admin.system.languages.index')
                ->with('success', 'Language created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            
            return back()->withInput()
                ->with('error', 'Failed to create language: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified language
     */
    public function edit(Language $language)
    {
        return view('admin.system.languages.edit', compact('language'));
    }

    /**
     * Update the specified language
     */
    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'native_name' => 'nullable|string|max:100',
            'code' => 'required|string|max:10|unique:languages,code,' . $language->id,
            'slug' => 'nullable|string|max:20',
            'direction' => 'required|in:ltr,rtl',
            'is_default' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            // If this is set as default, unset other defaults
            if ($request->has('is_default')) {
                Language::where('id', '!=', $language->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $language->update(array_merge([
                'is_default' => $request->boolean('is_default', false),
                'is_active' => $request->boolean('is_active', false),
            ], Arr::except($validated, ['is_default', 'is_active'])));

            DB::commit();
            
            return redirect()->route('admin.system.languages.index')
                ->with('success', 'Language updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            
            return back()->withInput()
                ->with('error', 'Failed to update language: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified language
     */
    public function destroy(Language $language)
    {
        // Prevent deletion of default or English language
        if ($language->is_default || $language->code === 'en') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the default or English language.'
            ], 403);
        }

        try {
            $language->delete();
            return response()->json([
                'success' => true,
                'message' => 'Language deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete language: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete languages
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No language IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Get languages that can be deleted (not default, not English)
            $languages = Language::whereIn('id', $ids)
                ->where('code', '!=', 'en')
                ->where('is_default', false)
                ->get();

            $deletedCount = $languages->count();

            foreach ($languages as $language) {
                $language->delete();
            }

            DB::commit();

            $notDeletedCount = count($ids) - $deletedCount;
            $message = "$deletedCount language(s) deleted successfully.";
            
            if ($notDeletedCount > 0) {
                $message .= " $notDeletedCount language(s) (including English or default) were not deleted.";
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

    /**
     * Change the active language
     */
    public function changeLanguage(Request $request)
    {
        $request->validate([
            'language_code' => 'required|string|exists:languages,code'
        ]);

        session(['language' => $request->language_code]);

        return response()->json([
            'success' => true,
            'message' => 'Language changed successfully'
        ]);
    }
}
