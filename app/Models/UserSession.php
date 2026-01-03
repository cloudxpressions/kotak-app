<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'is_active',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'platform',
        'country',
        'region',
        'city',
        'login_at',
        'logout_at',
        'last_seen_at',
        'authenticatable_id',
        'authenticatable_type',
        'session_type',
        'revoked_at',
        'revoked_reason',
        'admin_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }
}
