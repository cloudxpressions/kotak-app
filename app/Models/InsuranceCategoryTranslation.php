<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceCategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_category_id',
        'language_code',
        'name',
        'description',
    ];

    protected $casts = [
        'insurance_category_id' => 'integer',
    ];

    /**
     * Get the insurance category that owns the translation.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(InsuranceCategory::class, 'insurance_category_id');
    }
}