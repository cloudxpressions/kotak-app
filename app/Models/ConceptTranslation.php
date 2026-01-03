<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'concept_id',
        'language_code',
        'title',
        'content_html',
    ];

    protected $casts = [
        'concept_id' => 'integer',
    ];

    /**
     * Get the concept that owns the translation.
     */
    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class, 'concept_id');
    }
}