<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'language_code',
        'title',
        'description',
    ];

    protected $casts = [
        'test_id' => 'integer',
    ];

    /**
     * Get the test that owns the translation.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
}