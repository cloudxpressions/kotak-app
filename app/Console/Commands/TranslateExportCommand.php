<?php

namespace App\Console\Commands;

use App\Services\Translation\SyncWriteService;
use Illuminate\Console\Command;

class TranslateExportCommand extends Command
{
    protected $signature = 'translate:export';
    protected $description = 'Export translations to PHP files in lang directory';

    public function handle(SyncWriteService $service)
    {
        $this->info('Exporting translations to files...');
        
        $service->sync();
        
        $this->info('Done! Translations exported successfully.');
    }
}
