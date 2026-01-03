<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogCategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'language_id',
        'slug',
        'name',
    ];

    protected $casts = [
        'blog_category_id' => 'integer',
        'language_id' => 'integer',
    ];

    /**
     * Get the blog category that owns the translation.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Get the language that owns the translation.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}