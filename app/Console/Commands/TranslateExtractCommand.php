<?php

namespace App\Console\Commands;

use App\Services\Translation\ExtractService;
use Illuminate\Console\Command;

class TranslateExtractCommand extends Command
{
    protected $signature = 'translate:extract';
    protected $description = 'Extract translation keys from the codebase';

    public function handle(ExtractService $service)
    {
        $this->info('Extracting translation keys...');
        
        $count = $service->extract();
        
        $this->info("Done! Extracted {$count} new keys.");
    }
}
