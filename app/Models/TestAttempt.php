<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'score',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'test_id' => 'integer',
        'score' => 'integer',
    ];

    protected $dates = [
        'started_at',
        'submitted_at',
    ];

    /**
     * Get the user that owns the test attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the test that owns the test attempt.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}