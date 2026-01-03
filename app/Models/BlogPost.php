<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'user_id',
        'slug',
        'image',
        'image_url',
        'image_description',
        'is_visible',
        'is_slider',
        'is_featured',
        'is_breaking',
        'is_recommended',
        'registered_only',
        'is_paid_only',
        'publish_status',
        'publish_date',
        'show_author',
        'average_rating',
        'rating_count',
        'allow_print_pdf',
    ];

    protected $casts = [
        'blog_category_id' => 'integer',
        'user_id' => 'integer',
        'is_visible' => 'boolean',
        'is_slider' => 'boolean',
        'is_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'is_recommended' => 'boolean',
        'registered_only' => 'boolean',
        'is_paid_only' => 'boolean',
        'publish_date' => 'datetime',
        'show_author' => 'boolean',
        'average_rating' => 'decimal:1',
        'rating_count' => 'integer',
        'allow_print_pdf' => 'boolean',
        'publish_status' => 'string', // Using string for enum-like behavior
    ];

    /**
     * Get the category for the blog post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Get the user who authored the blog post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the translations for the blog post.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(BlogPostTranslation::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    /**
     * Get the attachments for the blog post.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(BlogPostAttachment::class);
    }

    /**
     * Get the ratings for the blog post.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(BlogPostRating::class);
    }

    /**
     * Get the references for the blog post.
     */
    public function references(): HasMany
    {
        return $this->hasMany(BlogPostReference::class);
    }

    /**
     * Get the tags for the blog post.
     */
    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tags');
    }

    /**
     * Scope to get only published posts
     */
    public function scopePublished($query)
    {
        return $query->where('publish_status', 'published');
    }

    /**
     * Scope to get posts that are visible
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope to get featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the title in the current locale
     */
    public function getTitleAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_id', getLanguageIdByCode($locale))->first();

        return $translation ? $translation->title : ($this->translations->first()->title ?? 'No Title');
    }

    /**
     * Get the content in the current locale
     */
    public function getContentAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_id', getLanguageIdByCode($locale))->first();

        return $translation ? $translation->content : ($this->translations->first()->content ?? 'No Content');
    }

    /**
     * Get the summary in the current locale
     */
    public function getSummaryAttribute()
    {
        // Load translations if not already loaded to prevent lazy loading violation
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $locale = app()->getLocale();
        $translation = $this->translations->where('language_id', getLanguageIdByCode($locale))->first();

        return $translation ? $translation->summary : ($this->translations->first()->summary ?? 'No Summary');
    }
}