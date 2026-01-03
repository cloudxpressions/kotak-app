<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCommentRequest;
use App\Http\Requests\BlogRatingRequest;
use App\Http\Resources\BlogCategoryResource;
use App\Http\Resources\BlogCommentResource;
use App\Http\Resources\BlogPostResource;
use App\Http\Resources\BlogTagResource;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogPostRating;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Get all active blog categories with translations
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        $categories = BlogCategory::with(['translations', 'parent', 'children'])
            ->where('is_active', true)
            ->get();

        return response()->json([
            'message' => 'Blog categories retrieved successfully',
            'data' => BlogCategoryResource::collection($categories),
        ]);
    }

    /**
     * Get all active blog tags with translations
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tags()
    {
        $tags = BlogTag::with('translations')
            ->where('is_active', true)
            ->get();

        return response()->json([
            'message' => 'Blog tags retrieved successfully',
            'data' => BlogTagResource::collection($tags),
        ]);
    }

    /**
     * Get published blog posts with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function posts(Request $request)
    {
        $query = BlogPost::with(['translations', 'category.translations', 'user', 'tags.translations'])
            ->where('is_visible', true)
            ->where('publish_status', 'published')
            ->where(function ($q) {
                $q->whereNull('publish_date')
                  ->orWhere('publish_date', '<=', now());
            });

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('blog_category_id', $request->category_id);
        }

        // Filter by tag
        if ($request->has('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('blog_tags.id', $request->tag_id);
            });
        }

        // Filter by type
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        if ($request->has('breaking') && $request->breaking) {
            $query->where('is_breaking', true);
        }

        if ($request->has('recommended') && $request->recommended) {
            $query->where('is_recommended', true);
        }

        if ($request->has('slider') && $request->slider) {
            $query->where('is_slider', true);
        }

        // Search by title/content (in translations)
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'publish_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage = $request->get('per_page', 15);
        $posts = $query->paginate($perPage);

        return response()->json([
            'message' => 'Blog posts retrieved successfully',
            'data' => BlogPostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    /**
     * Get a single blog post by slug
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        $post = BlogPost::with([
            'translations',
            'category.translations',
            'user',
            'tags.translations',
            'attachments',
            'references'
        ])
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->where('publish_status', 'published')
            ->where(function ($q) {
                $q->whereNull('publish_date')
                  ->orWhere('publish_date', '<=', now());
            })
            ->first();

        if (!$post) {
            return response()->json([
                'message' => 'Blog post not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Blog post retrieved successfully',
            'data' => new BlogPostResource($post),
        ]);
    }

    /**
     * Get comments for a blog post
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(int $id)
    {
        $post = BlogPost::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Blog post not found',
            ], 404);
        }

        $comments = BlogComment::with('commentable')
            ->where('blog_post_id', $id)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Comments retrieved successfully',
            'data' => BlogCommentResource::collection($comments),
        ]);
    }

    /**
     * Add a comment to a blog post (authenticated)
     *
     * @param BlogCommentRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(BlogCommentRequest $request, int $id)
    {
        $post = BlogPost::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Blog post not found',
            ], 404);
        }

        $comment = BlogComment::create([
            'blog_post_id' => $id,
            'commentable_id' => auth()->id(),
            'commentable_type' => 'App\Models\User',
            'content' => $request->comment,
            'is_approved' => false, // Requires admin approval
        ]);

        return response()->json([
            'message' => 'Comment submitted successfully. It will be visible after approval.',
            'data' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'is_approved' => $comment->is_approved,
            ],
        ], 201);
    }

    /**
     * Rate a blog post (authenticated)
     *
     * @param BlogRatingRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function rate(BlogRatingRequest $request, int $id)
    {
        $post = BlogPost::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Blog post not found',
            ], 404);
        }

        // Check if user already rated this post
        $existingRating = BlogPostRating::where('blog_post_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
            ]);

            $message = 'Rating updated successfully';
        } else {
            // Create new rating
            BlogPostRating::create([
                'blog_post_id' => $id,
                'user_id' => auth()->id(),
                'rating' => $request->rating,
            ]);

            $message = 'Rating submitted successfully';
        }

        // Recalculate average rating
        $averageRating = BlogPostRating::where('blog_post_id', $id)->avg('rating');
        $ratingCount = BlogPostRating::where('blog_post_id', $id)->count();

        $post->update([
            'average_rating' => round($averageRating, 1),
            'rating_count' => $ratingCount,
        ]);

        return response()->json([
            'message' => $message,
            'data' => [
                'rating' => $request->rating,
                'average_rating' => round($averageRating, 1),
                'rating_count' => $ratingCount,
            ],
        ]);
    }
}
