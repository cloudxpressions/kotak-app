<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Faq extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('faq')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the translations for the FAQ.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }

    /**
     * Scope to get only active FAQs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only featured FAQs
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the category in the current locale
     */
    public function getCategoryAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $languageId = \App\Models\Language::where('code', $locale)->value('id');
        
        if ($languageId) {
            $translation = $this->translations->where('language_id', $languageId)->first();
            if ($translation) {
                return $translation->category;
            }
        }

        return $this->translations->first()?->category;
    }

    /**
     * Get the question in the current locale
     */
    public function getQuestionAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $languageId = \App\Models\Language::where('code', $locale)->value('id');
        
        if ($languageId) {
            $translation = $this->translations->where('language_id', $languageId)->first();
            if ($translation) {
                return $translation->question;
            }
        }

        return $this->translations->first()?->question ?? 'No Question';
    }

    /**
     * Get the answer in the current locale
     */
    public function getAnswerAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $languageId = \App\Models\Language::where('code', $locale)->value('id');
        
        if ($languageId) {
            $translation = $this->translations->where('language_id', $languageId)->first();
            if ($translation) {
                return $translation->answer;
            }
        }

        return $this->translations->first()?->answer ?? 'No Answer';
    }
}
