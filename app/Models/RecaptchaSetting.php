<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class RecaptchaSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_key',
        'secret_key',
        'is_enabled',
        'version',
        'v3_score_threshold',
        'captcha_for_login',
        'captcha_for_register',
        'captcha_for_contact',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'captcha_for_login' => 'boolean',
        'captcha_for_register' => 'boolean',
        'captcha_for_contact' => 'boolean',
        'v3_score_threshold' => 'decimal:2',
    ];

    /**
     * Get the current reCAPTCHA settings with caching
     */
    public static function current()
    {
        return Cache::remember('recaptcha:settings', 3600, function () {
            return static::first() ?: static::create([
                'site_key' => null,
                'secret_key' => null,
                'is_enabled' => false,
                'version' => 'v2_checkbox',
                'v3_score_threshold' => 0.5,
                'captcha_for_login' => false,
                'captcha_for_register' => false,
                'captcha_for_contact' => false,
            ]);
        });
    }

    /**
     * Get the secret key from either the database or environment variable
     */
    public function getSecretKeyAttribute()
    {
        $dbSecret = $this->attributes['secret_key'] ?? null;
        return $dbSecret ?: env('RECAPTCHA_SECRET_KEY');
    }

    /**
     * Get the site key from either the database or environment variable
     */
    public function getSiteKeyAttribute()
    {
        $dbSiteKey = $this->attributes['site_key'] ?? null;
        return $dbSiteKey ?: env('RECAPTCHA_SITE_KEY');
    }

    /**
     * Clear the cached settings
     */
    public static function clearCache()
    {
        Cache::forget('recaptcha:settings');
    }

    /**
     * Boot the model and attach event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when a setting is saved
        static::saved(function () {
            static::clearCache();
        });

        // Clear cache when the setting is deleted
        static::deleted(function () {
            static::clearCache();
        });
    }
}