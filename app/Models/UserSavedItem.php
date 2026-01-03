<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSavedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'action',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'entity_id' => 'integer',
    ];

    /**
     * Get the user that owns the saved item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}