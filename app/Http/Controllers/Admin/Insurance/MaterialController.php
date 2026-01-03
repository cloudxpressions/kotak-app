<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Language;
use App\Models\Material;
use App\Models\MaterialTranslation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MaterialController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:material.view', only: ['index']),
            new Middleware('permission:material.create', only: ['create', 'store']),
            new Middleware('permission:material.update', only: ['edit', 'update']),
            new Middleware('permission:material.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the materials.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $materials = Material::select(['id', 'type', 'file_size', 'is_active'])
                ->with([
                    'translations:id,material_id,language_code,title',
                    'exam:id,code'
                ])
                ->get();
            
            $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get()->keyBy('code');

            return DataTables::of($materials)
                ->addIndexColumn()
                ->addColumn('title', function($material) use ($languages) {
                    $titles = [];
                    foreach ($languages as $lang) {
                        $translation = $material->translations->where('language_code', $lang->code)->first();
                        $titles[] = $lang->name . ': ' . ($translation?->title ?? '-');
                    }
                    return implode('<br>', $titles);
                })
                ->addColumn('exam', function($material) {
                    return $material->exam->code ?? '-';
                })
                ->addColumn('type_badge', function($material) {
                    $type = ucfirst($material->type);
                    $color = match($material->type) {
                        'pdf' => 'bg-blue-lt',
                        'poster' => 'bg-green-lt',
                        'note' => 'bg-orange-lt',
                        default => 'bg-gray-lt'
                    };
                    return "<span class=\"badge $color\">$type</span>";
                })
                ->addColumn('status_badge', function ($material) {
                    return $material->is_active
                        ? '<span class="badge bg-success-lt">Active</span>'
                        : '<span class="badge bg-danger-lt">Inactive</span>';
                })
                ->addColumn('action', function ($material) {
                    $buttons = '';

                    if (auth('admin')->user()->can('material.update')) {
                        $buttons .= '<a href="' . route('admin.insurance.materials.edit', $material->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('material.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $material->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['title', 'type_badge', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.materials.index');
    }

    /**
     * Show the form for creating a new material.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        
        return view('admin.insurance.materials.create', compact('languages', 'exams'));
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'type' => 'required|in:pdf,poster,note',
            'file_size' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.file_path' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $material = Material::create([
                'exam_id' => $validated['exam_id'],
                'type' => $validated['type'],
                'file_size' => $validated['file_size'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                MaterialTranslation::create([
                    'material_id' => $material->id,
                    'language_code' => $translationData['language_code'],
                    'title' => $translationData['title'],
                    'file_path' => $translationData['file_path'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.insurance.materials.index')
                ->with('success', 'Material created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create material: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified material.
     */
    public function edit(Material $material)
    {
        $material = Material::select(['id', 'exam_id', 'type', 'file_size', 'is_active'])
            ->where('id', $material->id)
            ->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $exams = Exam::select(['id', 'code'])->where('is_active', true)->with(['translations:id,exam_id,language_code,name'])->get();
        $material->load('translations');
        
        return view('admin.insurance.materials.edit', compact('material', 'languages', 'exams'));
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, Material $material)
    {
        $material = Material::select(['id'])->where('id', $material->id)->firstOrFail();

        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'type' => 'required|in:pdf,poster,note',
            'file_size' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_code' => 'required|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.file_path' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $material->update([
                'exam_id' => $validated['exam_id'],
                'type' => $validated['type'],
                'file_size' => $validated['file_size'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                MaterialTranslation::updateOrCreate(
                    [
                        'material_id' => $material->id,
                        'language_code' => $translationData['language_code'],
                    ],
                    [
                        'title' => $translationData['title'],
                        'file_path' => $translationData['file_path'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.insurance.materials.index')
                ->with('success', 'Material updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update material: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy(Material $material)
    {
        $material = Material::select(['id'])->where('id', $material->id)->firstOrFail();

        try {
            $material->delete();
            return response()->json([
                'success' => true,
                'message' => 'Material deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete materials
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No material IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = Material::whereIn('id', $ids)->count();
            Material::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount material(s) deleted successfully."
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