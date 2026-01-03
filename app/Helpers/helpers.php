<?php

/**
 * Global Helper Functions
 * 
 * This file contains all global helper functions used throughout the application.
 * Functions are organized by category for better maintainability.
 */

// ============================================================================
// Settings Helpers
// ============================================================================

if (!function_exists('setting')) {
    /**
     * Get a setting value from the database.
     *
     * @param string $key The setting key
     * @param mixed $default Default value if setting not found
     * @return mixed The setting value or default
     */
    function setting(string $key, $default = null)
    {
        return \App\Support\Settings::get($key, $default);
    }
}

// ============================================================================
// Language Helpers
// ============================================================================

if (!function_exists('getLanguageIdByCode')) {
    /**
     * Get the language ID by language code.
     *
     * @param string $code Language code (e.g., 'en', 'es')
     * @return int|null Language ID or null if not found
     */
    function getLanguageIdByCode(string $code): ?int
    {
        static $languageMap = null;

        if ($languageMap === null) {
            $languageMap = [];
            $languages = \App\Models\Language::all(['id', 'code']);
            
            foreach ($languages as $language) {
                $languageMap[$language->code] = $language->id;
            }
        }

        return $languageMap[$code] ?? null;
    }
}
