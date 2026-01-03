<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessTranslationImport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $filePath, public $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $count = 0;
        $errors = 0;

        try {
            $rows = \Spatie\SimpleExcel\SimpleExcelReader::create($this->filePath)->getRows();

            $languages = \App\Models\Language::all()->keyBy('code');

            foreach ($rows as $row) {
                try {
                    $keyName = $row['key'] ?? null;
                    $module = $row['module'] ?? 'general';

                    if (!$keyName) continue;

                    // Find or create the key
                    $key = \App\Models\TranslationKey::firstOrCreate(
                        ['key' => $keyName],
                        ['module' => $module]
                    );

                    foreach ($languages as $code => $language) {
                        if (isset($row[$code]) && !empty($row[$code])) {
                            \App\Models\TranslationValue::updateOrCreate(
                                [
                                    'translation_key_id' => $key->id,
                                    'language_id' => $language->id,
                                ],
                                [
                                    'value' => $row[$code],
                                    'is_auto_translated' => false,
                                    'last_updated_by' => $this->user->id,
                                ]
                            );
                        }
                    }
                    $count++;
                } catch (\Exception $e) {
                    $errors++;
                    \Illuminate\Support\Facades\Log::error("Import Error Row: " . json_encode($row) . " - " . $e->getMessage());
                }
            }

            // Notify user
            $this->user->notify(new \App\Notifications\TranslationImportCompleted($count, $errors, basename($this->filePath)));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Import Job Failed: " . $e->getMessage());
        } finally {
            // Cleanup file
            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }
        }
    }
}
