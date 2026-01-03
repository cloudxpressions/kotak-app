<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'chapter_id',
        'type',
        'total_questions',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'chapter_id' => 'integer',
        'is_active' => 'boolean',
        'total_questions' => 'integer',
        'duration_minutes' => 'integer',
    ];

    /**
     * Get the exam that owns the test.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the chapter that owns the test.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the translations for the test.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TestTranslation::class);
    }

    /**
     * Get the questions for this test.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(TestQuestion::class);
    }

    /**
     * Get the test attempts for this test.
     */
    public function testAttempts(): HasMany
    {
        return $this->hasMany(TestAttempt::class);
    }

    /**
     * Scope to get only active tests
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