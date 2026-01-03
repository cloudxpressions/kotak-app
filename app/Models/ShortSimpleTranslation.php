<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortSimpleTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_simple_id',
        'language_code',
        'title',
        'content',
    ];

    protected $casts = [
        'short_simple_id' => 'integer',
    ];

    /**
     * Get the short simple that owns the translation.
     */
    public function shortSimple(): BelongsTo
    {
        return $this->belongsTo(ShortSimple::class, 'short_simple_id');
    }
}