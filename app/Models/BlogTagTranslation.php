<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogTagTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_tag_id',
        'language_id',
        'slug',
        'name',
    ];

    protected $casts = [
        'blog_tag_id' => 'integer',
        'language_id' => 'integer',
    ];

    /**
     * Get the blog tag that owns the translation.
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(BlogTag::class, 'blog_tag_id');
    }

    /**
     * Get the language that owns the translation.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}