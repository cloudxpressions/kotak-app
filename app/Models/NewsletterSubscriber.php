<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'last_open_at',
        'last_click_at',
        'verify_token',
        'source',
        'meta',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'last_open_at' => 'datetime',
        'last_click_at' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * Get the newsletter sends for the subscriber
     */
    public function newsletterSends(): HasMany
    {
        return $this->hasMany(NewsletterSend::class, 'subscriber_id');
    }

    /**
     * Scope to get only active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'subscribed');
    }

    /**
     * Scope to get subscribers by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}