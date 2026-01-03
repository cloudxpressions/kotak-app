<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'language_code',
        'title',
        'file_path',
    ];

    protected $casts = [
        'material_id' => 'integer',
    ];

    /**
     * Get the material that owns the translation.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}