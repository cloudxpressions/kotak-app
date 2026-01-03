<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'insurance_category_id',
        'order_no',
        'is_active',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'insurance_category_id' => 'integer',
        'is_active' => 'boolean',
        'order_no' => 'integer',
    ];

    /**
     * Get the exam that owns the chapter.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the insurance category that owns the chapter.
     */
    public function insuranceCategory(): BelongsTo
    {
        return $this->belongsTo(InsuranceCategory::class);
    }

    /**
     * Get the translations for the chapter.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ChapterTranslation::class);
    }

    /**
     * Get the concepts for this chapter.
     */
    public function concepts(): HasMany
    {
        return $this->hasMany(Concept::class);
    }

    /**
     * Get the one liners for this chapter.
     */
    public function oneLiners(): HasMany
    {
        return $this->hasMany(OneLiner::class);
    }

    /**
     * Get the short & simples for this chapter.
     */
    public function shortSimples(): HasMany
    {
        return $this->hasMany(ShortSimple::class);
    }

    /**
     * Get the tests for this chapter.
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    /**
     * Scope to get only active chapters
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