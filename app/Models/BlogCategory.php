<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'parent_id' => 'integer',
    ];

    /**
     * Get the translations for the blog category.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(BlogCategoryTranslation::class);
    }

    /**
     * Get the parent category for the blog category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    /**
     * Get the children categories for the blog category.
     */
    public function children(): HasMany
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    /**
     * Scope to get only active categories
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