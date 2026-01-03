<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'difficulty',
        'correct_option',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the translations for the question.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(QuestionTranslation::class);
    }

    /**
     * Get the test questions for this question.
     */
    public function testQuestions(): HasMany
    {
        return $this->hasMany(TestQuestion::class);
    }

    /**
     * Scope to get only active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the question text in the current locale
     */
    public function getQuestionTextAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->question_text : ($this->translations->first()->question_text ?? '');
    }

    /**
     * Get option A in the current locale
     */
    public function getOptionAAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->option_a : ($this->translations->first()->option_a ?? '');
    }

    /**
     * Get option B in the current locale
     */
    public function getOptionBAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->option_b : ($this->translations->first()->option_b ?? '');
    }

    /**
     * Get option C in the current locale
     */
    public function getOptionCAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->option_c : ($this->translations->first()->option_c ?? '');
    }

    /**
     * Get option D in the current locale
     */
    public function getOptionDAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_code', $locale)->first();

        return $translation ? $translation->option_d : ($this->translations->first()->option_d ?? '');
    }
}