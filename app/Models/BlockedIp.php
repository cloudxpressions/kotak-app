<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BlockedIp extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'reason',
        'blocked_until',
        'is_permanent',
        'attempts_count',
        'last_attempt_at',
        'user_agent',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'blocked_until' => 'datetime',
        'is_permanent' => 'boolean',
        'attempts_count' => 'integer',
        'last_attempt_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    protected static function booted(): void
    {
        $flush = fn (BlockedIp $blockedIp) => Cache::forget("blocked-ip:{$blockedIp->ip_address}");

        static::saved($flush);
        static::deleted($flush);
    }

    public function isCurrentlyBlocked(): bool
    {
        if ($this->is_permanent) {
            return true;
        }

        if (! $this->blocked_until) {
            return false;
        }

        return $this->blocked_until->isFuture();
    }
}
