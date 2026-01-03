<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TerminologyTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'terminology_id',
        'language_code',
        'term',
        'definition',
    ];

    protected $casts = [
        'terminology_id' => 'integer',
    ];

    /**
     * Get the terminology that owns the translation.
     */
    public function terminology(): BelongsTo
    {
        return $this->belongsTo(Terminology::class, 'terminology_id');
    }
}