<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'language_code',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
    ];

    protected $casts = [
        'question_id' => 'integer',
    ];

    /**
     * Get the question that owns the translation.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}