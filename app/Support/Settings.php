<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    protected const CACHE_KEY = 'app_settings';

    /**
     * Get all settings as [key => value] from cache/DB.
     */
    protected static function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return \App\Models\Setting::query()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $settings = self::all();

        return $settings[$key] ?? $default;
    }

    /**
     * Set/update a setting value.
     */
    public static function set(string $key, $value): void
    {
        \App\Models\Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear cache so next read sees new value
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Clear cache manually if needed.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}