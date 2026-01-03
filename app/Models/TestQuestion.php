<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'question_id',
    ];

    protected $casts = [
        'test_id' => 'integer',
        'question_id' => 'integer',
    ];

    /**
     * Get the test that owns the test question.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the question that owns the test question.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}