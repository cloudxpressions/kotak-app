<?php

namespace App\Console\Commands;

use App\Models\TranslationValue;
use App\Models\TranslationKey;
use App\Services\Translation\ExtractService;
use App\Services\Translation\SyncWriteService;
use Illuminate\Console\Command;

class TranslationResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all translation data and re-extract keys with auto-populated English values';

    /**
     * Execute the console command.
     */
    public function handle(ExtractService $extractService, SyncWriteService $syncWriteService)
    {
        $this->info('Resetting all translation data...');

        // Delete all translation values and keys
        TranslationValue::query()->delete();
        TranslationKey::query()->delete();

        $this->info('Cleared all translation data.');

        // Extract keys again to populate with auto-generated English values
        $this->info('Extracting translation keys with auto-populated English values...');
        $count = $extractService->extract();

        $this->info("Extracted {$count} new keys with auto-populated English values.");

        // Export to files
        $this->info('Exporting translations to files...');
        $syncWriteService->sync();

        $this->info('All translation data reset successfully!');

        return 0;
    }
}
