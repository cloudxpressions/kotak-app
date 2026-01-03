<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OneLinerTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'one_liner_id',
        'language_code',
        'content',
    ];

    protected $casts = [
        'one_liner_id' => 'integer',
    ];

    /**
     * Get the one liner that owns the translation.
     */
    public function oneLiner(): BelongsTo
    {
        return $this->belongsTo(OneLiner::class, 'one_liner_id');
    }
}