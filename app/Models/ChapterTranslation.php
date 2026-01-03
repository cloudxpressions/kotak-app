<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'language_code',
        'title',
        'description',
    ];

    protected $casts = [
        'chapter_id' => 'integer',
    ];

    /**
     * Get the chapter that owns the translation.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }
}