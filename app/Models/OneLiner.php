<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OneLiner extends Model
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
     * Get the chapter that owns the one liner.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the translations for the one liner.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(OneLinerTranslation::class);
    }

    /**
     * Scope to get only active one liners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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