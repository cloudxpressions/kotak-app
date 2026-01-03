<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the translations for the blog tag.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(BlogTagTranslation::class);
    }

    /**
     * Scope to get only active tags
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the name in the current locale
     */
    public function getNameAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_id', getLanguageIdByCode($locale))->first();

        return $translation ? $translation->name : ($this->translations->first()->name ?? 'No Name');
    }

    /**
     * Get the slug in the current locale
     */
    public function getSlugAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_id', getLanguageIdByCode($locale))->first();

        return $translation ? $translation->slug : ($this->translations->first()->slug ?? 'no-slug');
    }
}