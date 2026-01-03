<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\TranslationKey;
use App\Models\TranslationValue;

use App\Services\Translation\ExtractService;
use App\Services\Translation\SyncWriteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            \Illuminate\Support\Facades\Log::info('Translation Index AJAX called', $request->all());
            $query = TranslationKey::with(['values' => function($q) {
                $q->with('language');  // Eager load the language relationship
            }]);

            if ($request->has('module') && $request->module != '') {
                $query->where('module', $request->module);
            }

            $count = $query->count();
            \Illuminate\Support\Facades\Log::info('Translation Key Count: ' . $count);

            return DataTables::of($query)
                ->addColumn('key', function ($row) {
                    return $row->key;
                })
                ->addColumn('module', function ($row) {
                    return $row->module;
                })
                ->addColumn('translations', function ($row) {
                    $translations = [];
                    foreach($row->values as $value) {
                        $translations[$value->language_id] = $value->value;
                    }
                    return $translations;
                })
                ->make(true);
        }

        $modules = TranslationKey::select('module')->distinct()->pluck('module');
        $languages = Language::active()->get();

        return view('admin.system.translations.index', compact('modules', 'languages'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'key_id' => 'required|exists:translation_keys,id',
            'language_id' => 'required|exists:languages,id',
            'value' => 'nullable|string',
        ]);

        // Check if the language being updated is English
        $language = Language::find($request->language_id);
        $isEnglish = $language && $language->code === 'en';

        TranslationValue::updateOrCreate(
            [
                'translation_key_id' => $request->key_id,
                'language_id' => $request->language_id,
            ],
            [
                'value' => $request->value,
                'last_updated_by' => auth()->id(),
                'is_auto_translated' => false,
            ]
        );

        // If English value was updated, clear any auto-translation flags for other languages
        if ($isEnglish) {
            TranslationValue::where('translation_key_id', $request->key_id)
                ->where('language_id', '!=', $request->language_id) // Not English
                ->update(['is_auto_translated' => false]);
        }

        return response()->json(['success' => true]);
    }

    public function extract(ExtractService $service)
    {
        $count = $service->extract();
        return response()->json(['success' => true, 'message' => "Extracted {$count} new keys."]);
    }

    public function export(SyncWriteService $service)
    {
        $service->sync();
        return response()->json(['success' => true, 'message' => 'Translations exported to files.']);
    }

    public function exportExcel()
    {
        $languages = Language::active()->get();
        $keys = TranslationKey::with('values')->get();

        $rows = [];
        foreach ($keys as $key) {
            $row = [
                'key' => $key->key,
                'module' => $key->module,
            ];

            foreach ($languages as $language) {
                $value = $key->values->where('language_id', $language->id)->first();
                $row[$language->code] = $value ? $value->value : '';
            }

            $rows[] = $row;
        }

        $writer = \Spatie\SimpleExcel\SimpleExcelWriter::streamDownload('translations.xlsx');
        
        foreach ($rows as $row) {
            $writer->addRow($row);
        }

        return $writer->toBrowser();
    }

    public function importForm()
    {
        return view('admin.system.translations.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('temp/imports', $fileName, 'local');
        $fullPath = storage_path('app/temp/imports/' . $fileName);

        // Dispatch job
        \App\Jobs\ProcessTranslationImport::dispatch($fullPath, auth()->user());

        return redirect()->route('admin.system.translations.index')
            ->with('success', 'Import started in background. You will be notified when completed.');
    }
}
