<?php

namespace App\Services\Translation;

use App\Models\Language;
use App\Models\TranslationKey;
use Illuminate\Support\Facades\File;

class SyncWriteService
{
    public function sync()
    {
        $languages = Language::all();

        foreach ($languages as $language) {
            $locale = $language->code;
            $path = lang_path($locale);

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            // Get all translation keys with their values for this language
            $translationKeys = TranslationKey::with(['values' => function ($query) use ($language) {
                $query->where('language_id', $language->id);
            }])->get();

            // Group the keys by file/module
            $keysByFile = $translationKeys->groupBy('file');

            // Get list of files before processing to know which ones to clean up
            $existingFiles = [];
            if (File::exists($path)) {
                $existingFiles = array_map('basename', File::files($path));
            }

            foreach ($keysByFile as $file => $group) {
                $content = "<?php\n\nreturn [\n";

                foreach ($group as $key) {
                    $value = $key->values->first()?->value ?? '';

                    // Extract the key part after the module (e.g., 'auth.failed' -> 'failed')
                    $keyPart = explode('.', $key->key);
                    $module = array_shift($keyPart); // Remove module
                    $shortKey = implode('.', $keyPart);

                    // If the key has no module part, use the full key
                    if (empty($shortKey)) {
                        $shortKey = $key->key;
                    }

                    $content .= "    '" . addslashes($shortKey) . "' => '" . addslashes($value) . "',\n";
                }

                $content .= "];\n";

                File::put($path . '/' . $file, $content);

                // Remove this file from the existing files list as it's now handled by our system
                if (($key = array_search($file, $existingFiles)) !== false) {
                    unset($existingFiles[$key]);
                }
            }

            // Remove any remaining files that our system doesn't manage
            // except Laravel's default files
            $laravelDefaultFiles = ['auth.php', 'pagination.php', 'passwords.php', 'validation.php'];

            foreach ($existingFiles as $file) {
                if (!in_array($file, $laravelDefaultFiles)) {
                    $filePath = $path . '/' . $file;
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
            }

            // Ensure Laravel's default language files exist for this locale
            foreach ($laravelDefaultFiles as $defaultFile) {
                $defaultFilePath = base_path("vendor/laravel/framework/src/Illuminate/Translation/lang/{$locale}/{$defaultFile}");

                // If Laravel's default file exists and our system hasn't created it, copy it
                if (File::exists($defaultFilePath)) {
                    $outputPath = $path . '/' . $defaultFile;
                    if (!File::exists($outputPath)) {
                        $content = File::get($defaultFilePath);
                        File::put($outputPath, $content);
                    }
                }
            }
        }
    }
}
