<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'order_no',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_no' => 'integer',
    ];

    /**
     * Get the translations for the insurance category.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(InsuranceCategoryTranslation::class);
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
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->name : ($this->translations->first()->name ?? 'No Name');
    }

    /**
     * Get the description in the current locale
     */
    public function getDescriptionAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->description : ($this->translations->first()->description ?? '');
    }
}