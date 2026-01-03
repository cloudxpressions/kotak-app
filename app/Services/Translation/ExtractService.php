<?php

namespace App\Services\Translation;

use App\Models\Language;
use App\Models\TranslationKey;
use App\Models\TranslationValue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExtractService
{
    public function extract()
    {
        $paths = [
            resource_path('views'),
            app_path(),
            base_path('routes'),
        ];

        $keys = [];

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                continue;
            }

            $files = File::allFiles($path);

            foreach ($files as $file) {
                $content = File::get($file);
                $matches = [];

                // Match __('key') and @lang('key') and trans('key')
                preg_match_all("/(?:__|@lang|trans)\(['\"]([^'\"]+)['\"]\)/U", $content, $matches);

                if (isset($matches[1]) && !empty($matches[1])) {
                    foreach ($matches[1] as $key) {
                        // Ignore keys with spaces or variables (simple heuristic)
                        if (str_contains($key, ' ') || str_contains($key, '$')) {
                            continue;
                        }

                        // Also ignore if key contains special characters that might cause issues
                        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $key)) {
                            continue;
                        }

                        $keys[] = $key;
                    }
                }
            }
        }

        $keys = array_unique($keys);
        $newCount = 0;

        foreach ($keys as $key) {
            // Determine if this key follows the module.pattern format
            $parts = explode('.', $key);

            // A key is considered to have a module if:
            // 1. It has more than one part when split by '.'
            // 2. The first part is a common module name (like auth, validation, etc.)
            $commonModules = ['auth', 'validation', 'passwords', 'pagination', 'general', 'profile', 'dashboard', 'system'];

            // If it has multiple parts AND the first part is a common module OR is short (like 2-4 chars), treat as module
            if (count($parts) > 1 && (in_array(strtolower($parts[0]), $commonModules) || strlen($parts[0]) <= 4)) {
                $module = $parts[0];
            } else {
                // Otherwise, treat as a general key
                $module = 'general';
            }

            $file = $module . '.php';

            $exists = TranslationKey::where('key', $key)->exists();

            if (!$exists) {
                $translationKey = TranslationKey::create([
                    'key' => $key,
                    'module' => $module,
                    'file' => $file,
                    'type' => 'extracted',
                ]);

                // Automatically populate English value based on key
                $englishLang = Language::where('code', 'en')->first();
                if ($englishLang) {
                    // Extract the last part of the key after the module
                    $keyParts = explode('.', $key);

                    // Only remove the module part if this key truly has a module prefix
                    if (count($keyParts) > 1 && $module !== 'general') {
                        array_shift($keyParts); // Remove module
                        $englishValue = trim(implode('.', $keyParts));
                    } else {
                        // If it's a general key, use the entire key
                        $englishValue = $key;
                    }

                    // Convert to readable text (e.g., "login_failed" -> "Login Failed")
                    $englishValue = $this->makeReadable($englishValue);

                    TranslationValue::create([
                        'translation_key_id' => $translationKey->id,
                        'language_id' => $englishLang->id,
                        'value' => $englishValue,
                        'is_auto_translated' => false,
                    ]);
                }

                $newCount++;
            }
        }

        return $newCount;
    }

    private function makeReadable($text)
    {
        // Replace dots and underscores with spaces, then convert to proper case
        $readable = str_replace(['.', '_'], ' ', $text);
        $readable = trim($readable);
        return ucfirst(strtolower($readable)); // Convert to "Login failed" instead of "Login Failed"
    }
}
