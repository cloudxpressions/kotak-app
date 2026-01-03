<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortSimple extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'order_no',
        'is_active',
    ];

    protected $casts = [
        'chapter_id' => 'integer',
        'is_active' => 'boolean',
        'order_no' => 'integer',
    ];

    /**
     * Get the chapter that owns the short simple.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the translations for the short simple.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ShortSimpleTranslation::class);
    }

    /**
     * Scope to get only active short simples
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the title in the current locale
     */
    public function getTitleAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->title : ($this->translations->first()->title ?? 'No Title');
    }

    /**
     * Get the content in the current locale
     */
    public function getContentAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->content : ($this->translations->first()->content ?? '');
    }
}