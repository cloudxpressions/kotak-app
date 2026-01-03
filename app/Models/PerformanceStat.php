<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'total_tests',
        'avg_score',
        'accuracy',
        'last_test_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'exam_id' => 'integer',
        'total_tests' => 'integer',
        'avg_score' => 'decimal:2',
        'accuracy' => 'decimal:2',
    ];

    protected $dates = [
        'last_test_at',
    ];

    /**
     * Get the user that owns the performance stat.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exam that owns the performance stat.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}