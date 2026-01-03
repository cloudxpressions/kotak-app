<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id',
        'language_id',
        'title',
        'summary',
        'content',
    ];

    protected $casts = [
        'blog_post_id' => 'integer',
        'language_id' => 'integer',
    ];

    /**
     * Get the blog post that owns the translation.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    /**
     * Get the language that owns the translation.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}