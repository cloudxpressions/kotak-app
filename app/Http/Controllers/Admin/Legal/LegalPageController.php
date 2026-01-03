<?php

namespace App\Http\Controllers\Admin\Legal;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class LegalPageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:legal_pages.view', only: ['index']),
            new Middleware('permission:legal_pages.create', only: ['create', 'store']),
            new Middleware('permission:legal_pages.update', only: ['edit', 'update']),
            new Middleware('permission:legal_pages.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the legal pages.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $legalPages = LegalPage::select(['id', 'title', 'slug', 'is_active', 'created_at']);

            return DataTables::of($legalPages)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($legalPage) {
                    return $legalPage->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($legalPage) {
                    $buttons = '';

                    if (auth('admin')->user()->can('legal_pages.update')) {
                        $buttons .= '<a href="' . route('admin.legal.pages.edit', $legalPage->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('legal_pages.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $legalPage->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.legal.pages.index');
    }

    /**
     * Show the form for creating a new legal page.
     */
    public function create()
    {
        return view('admin.legal.pages.create');
    }

    /**
     * Store a newly created legal page in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string',
        ]);

        // Generate slug from title
        $validated['slug'] = Str::slug($request->title);

        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (LegalPage::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $validated['is_active'] = $request->has('is_active');

        LegalPage::create($validated);

        return redirect()->route('admin.legal.pages.index')
            ->with('success', 'Legal page created successfully.');
    }

    /**
     * Show the form for editing the specified legal page.
     */
    public function edit(LegalPage $legalPage)
    {
        return view('admin.legal.pages.edit', compact('legalPage'));
    }

    /**
     * Update the specified legal page in storage.
     */
    public function update(Request $request, LegalPage $legalPage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string',
        ]);

        // Handle slug generation
        $slug = Str::slug($request->title);
        // Only update slug if title has changed
        if ($legalPage->title !== $request->title) {
            // Check if slug exists and make unique if needed
            $originalSlug = $slug;
            $counter = 1;
            while (LegalPage::where('slug', $slug)->where('id', '!=', $legalPage->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $validated['is_active'] = $request->has('is_active');

        $legalPage->update($validated);

        return redirect()->route('admin.legal.pages.index')
            ->with('success', 'Legal page updated successfully.');
    }

    /**
     * Remove the specified legal page from storage.
     */
    public function destroy(LegalPage $legalPage)
    {
        $legalPage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Legal page deleted successfully'
        ]);
    }

    /**
     * Bulk delete multiple legal pages
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No legal pages selected for deletion'
            ], 422);
        }

        LegalPage::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' legal page(s) deleted successfully'
        ]);
    }
}