<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;


class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_mode',
        'title',
        'subtitle',
        'maintenance_page_banner',
        'starts_at',
        'ends_at',
        'allowed_ips',
        'is_emergency',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'allowed_ips' => 'array',
        'is_emergency' => 'boolean',
    ];

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('maintenance:active');

        static::saved($flush);
        static::deleted($flush);
    }

    public function isCurrentlyActive(): bool
    {
        if (! $this->maintenance_mode) {
            return false;
        }

        $now = Carbon::now();

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function allowsIp(?string $ip): bool
    {
        if (! $ip) {
            return false;
        }

        $allowed = Arr::wrap($this->allowed_ips);

        return in_array($ip, $allowed, true);
    }
}
