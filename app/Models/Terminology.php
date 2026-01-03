<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Terminology extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'category',
        'is_active',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the exam that owns the terminology.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the translations for the terminology.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TerminologyTranslation::class);
    }

    /**
     * Scope to get only active terminologies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the term in the current locale
     */
    public function getTermAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->term : ($this->translations->first()->term ?? 'No Term');
    }

    /**
     * Get the definition in the current locale
     */
    public function getDefinitionAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->definition : ($this->translations->first()->definition ?? '');
    }
}