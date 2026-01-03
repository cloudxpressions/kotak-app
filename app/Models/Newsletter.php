<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'slug',
        'body_text',
        'body_html',
        'status',
        'scheduled_for',
        'sent_at',
        'total_recipients',
        'total_sent',
        'total_opened',
        'total_clicked',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
        'total_recipients' => 'integer',
        'total_sent' => 'integer',
        'total_opened' => 'integer',
        'total_clicked' => 'integer',
    ];

    /**
     * Boot the model and set up slug generation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($newsletter) {
            if (empty($newsletter->slug)) {
                $newsletter->slug = Str::slug($newsletter->subject) . '-' . time();
            }
        });
    }

    /**
     * Get the newsletter sends for this newsletter
     */
    public function newsletterSends(): HasMany
    {
        return $this->hasMany(NewsletterSend::class, 'newsletter_id');
    }

    /**
     * Scope to get newsletters by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for sent newsletters
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for draft newsletters
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for scheduled newsletters
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope for upcoming newsletters
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('scheduled_for', '>', now());
    }
}