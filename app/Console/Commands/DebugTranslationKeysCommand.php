<?php

namespace App\Console\Commands;

use App\Models\TranslationKey;
use Illuminate\Console\Command;

class DebugTranslationKeysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:translation-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug the translation keys in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keys = TranslationKey::with(['values' => function($query) {
            $query->with('language');
        }])->get();

        $this->info("Found " . $keys->count() . " translation keys:");

        foreach ($keys as $key) {
            $this->line("Key: {$key->key}, Module: {$key->module}, File: {$key->file}");

            foreach ($key->values as $value) {
                $language = $value->language;
                $this->comment("  - {$language->name} ({$language->code}): '{$value->value}' (auto: " . ($value->is_auto_translated ? 'yes' : 'no') . ")");
            }
        }
    }
}
