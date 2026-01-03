<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:faq.view', only: ['index']),
            new Middleware('permission:faq.create', only: ['create', 'store']),
            new Middleware('permission:faq.update', only: ['edit', 'update']),
            new Middleware('permission:faq.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $faqs = Faq::with(['translations'])->select(['id', 'sort_order', 'is_featured', 'is_active', 'created_at']);

            return DataTables::of($faqs)
                ->addIndexColumn()
                ->filter(function ($query) {
                    $search = request('search')['value'] ?? null;

                    if (! empty($search)) {
                        $query->whereHas('translations', function ($q) use ($search) {
                            $q->where('question', 'LIKE', "%{$search}%")
                                ->orWhere('category', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('category', function ($faq) {
                    return $faq->category ?? '-';
                })
                ->addColumn('question', function ($faq) {
                    return $faq->question;
                })
                ->addColumn('status_badge', function ($faq) {
                    return $faq->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('featured_badge', function ($faq) {
                    return $faq->is_featured
                        ? '<span class="badge bg-purple-lt">Featured</span>'
                        : '<span class="badge bg-muted-lt">Standard</span>';
                })
                ->addColumn('action', function ($faq) {
                    $buttons = '';

                    $editIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>';
                    $deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>';

                    if (auth('admin')->user()->can('faq.update')) {
                        $buttons .= '<a href="'.route('admin.system.faqs.edit', $faq->id).'" class="btn btn-sm btn-icon btn-primary" title="Edit">'.$editIcon.'</a>';
                    }

                    if (auth('admin')->user()->can('faq.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="'.$faq->id.'" title="Delete">'.$deleteIcon.'</button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'featured_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.faqs.index');
    }

    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        return view('admin.system.faqs.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        DB::beginTransaction();
        try {
            $faq = Faq::create([
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Create translations
            foreach ($validated['translations'] as $translation) {
                $faq->translations()->create([
                    'language_id' => $translation['language_id'],
                    'category' => $translation['category'] ?? null,
                    'question' => $translation['question'],
                    'answer' => $translation['answer'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.system.faqs.index')
                ->with('success', 'FAQ created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()->with('error', 'Failed to create FAQ: '.$e->getMessage());
        }
    }

    public function edit(Faq $faq)
    {
        $languages = Language::where('is_active', true)->get();
        $faq->load('translations');
        return view('admin.system.faqs.edit', compact('faq', 'languages'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate($this->rules());

        DB::beginTransaction();
        try {
            $faq->update([
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Delete existing translations
            $faq->translations()->delete();

            // Create new translations
            foreach ($validated['translations'] as $translation) {
                $faq->translations()->create([
                    'language_id' => $translation['language_id'],
                    'category' => $translation['category'] ?? null,
                    'question' => $translation['question'],
                    'answer' => $translation['answer'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.system.faqs.index')
                ->with('success', 'FAQ updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()->with('error', 'Failed to update FAQ: '.$e->getMessage());
        }
    }

    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();

            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete FAQ: '.$e->getMessage(),
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No FAQ IDs provided.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $faqs = Faq::whereIn('id', $ids)->get();
            $deletedCount = $faqs->count();

            foreach ($faqs as $faq) {
                $faq->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount FAQ(s) deleted successfully.",
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

    protected function rules(): array
    {
        return [
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.category' => 'nullable|string|max:100',
            'translations.*.question' => 'required|string|max:500',
            'translations.*.answer' => 'required|string',
        ];
    }
}
