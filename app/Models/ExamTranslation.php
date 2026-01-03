<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'language_code',
        'name',
        'description',
    ];

    protected $casts = [
        'exam_id' => 'integer',
    ];

    /**
     * Get the exam that owns the translation.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}