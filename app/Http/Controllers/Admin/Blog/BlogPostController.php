<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class BlogPostController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:blog_post.view', only: ['index']),
            new Middleware('permission:blog_post.create', only: ['create', 'store']),
            new Middleware('permission:blog_post.update', only: ['edit', 'update']),
            new Middleware('permission:blog_post.delete', only: ['destroy', 'bulkDelete']),
        ];
    }
    /**
     * Display a listing of the blog posts.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $posts = BlogPost::with('translations', 'category')->get();

            return DataTables::of($posts)
                ->addIndexColumn()
                ->addColumn('title', function($post) {
                    $title = $post->title;
                    return strlen($title) > 50 ? substr($title, 0, 50) . '...' : $title;
                })
                ->addColumn('category', function($post) {
                    return $post->category->name ?? 'N/A';
                })
                ->addColumn('status_badge', function ($post) {
                    $statusClass = match($post->publish_status) {
                        'published' => 'bg-success-lt',
                        'scheduled' => 'bg-warning-lt',
                        'draft' => 'bg-secondary-lt',
                        default => 'bg-danger-lt',
                    };
                    return '<span class="badge ' . $statusClass . '">' . ucfirst($post->publish_status) . '</span>';
                })
                ->addColumn('action', function ($post) {
                    $buttons = '';

                    if (auth('admin')->user()->can('blog_post.update')) {
                        $buttons .= '<a href="' . route('admin.blog.posts.edit', $post->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></a>';
                    }

                    if (auth('admin')->user()->can('blog_post.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $post->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.blog.posts.index');
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create()
    {
        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $categories = BlogCategory::select(['id'])->where('is_active', true)->with(['translations:id,blog_category_id,name'])->get();
        $users = User::select(['id', 'name', 'email'])->get();
        $tags = BlogTag::active()->with(['translations:id,blog_tag_id,name'])->get();
        return view('admin.blog.posts.create', compact('languages', 'categories', 'users', 'tags'));
    }

    /**
     * Store a newly created blog post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'user_id' => 'required|exists:users,id',
            'slug' => 'required|string|max:255|unique:blog_posts,slug',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'image_url' => 'nullable|url|max:500',
            'image_description' => 'nullable|string|max:500',
            'is_visible' => 'boolean',
            'is_slider' => 'boolean',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'is_recommended' => 'boolean',
            'registered_only' => 'boolean',
            'is_paid_only' => 'boolean',
            'publish_status' => 'required|in:draft,scheduled,published',
            'publish_date' => 'nullable|date',
            'show_author' => 'boolean',
            'allow_print_pdf' => 'boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.summary' => 'required|string',
            'translations.*.content' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload with server-side processing
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Process and convert to WebP using Intervention Image
                $processedImage = Image::read($image->getRealPath())
                    ->scaleDown(width: 1920, height: 1920)
                    ->toWebp(quality: 80);
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.webp';
                $directory = 'blog-images/' . date('Y/m/d');
                
                // Ensure directory exists
                Storage::disk('public')->makeDirectory($directory);
                
                // Save the processed image
                $imagePath = $directory . '/' . $filename;
                Storage::disk('public')->put($imagePath, (string) $processedImage);
            }

            $post = BlogPost::create(array_merge([
                'image' => $imagePath,
                'is_visible' => $request->has('is_visible'),
                'is_slider' => $request->has('is_slider'),
                'is_featured' => $request->has('is_featured'),
                'is_breaking' => $request->has('is_breaking'),
                'is_recommended' => $request->has('is_recommended'),
                'registered_only' => $request->has('registered_only'),
                'is_paid_only' => $request->has('is_paid_only'),
                'show_author' => $request->has('show_author'),
                'allow_print_pdf' => $request->has('allow_print_pdf'),
            ], Arr::except($validated, ['translations', 'references', 'attachments', 'image'])));

            // Create translations
            foreach ($validated['translations'] as $translationData) {
                BlogPostTranslation::create([
                    'blog_post_id' => $post->id,
                    'language_id' => $translationData['language_id'],
                    'title' => $translationData['title'],
                    'summary' => $translationData['summary'],
                    'content' => $translationData['content'],
                ]);
            }

            // Save References
            if ($request->has('references')) {
                foreach ($request->references as $reference) {
                    if (!empty($reference['title']) && !empty($reference['url'])) {
                        $post->references()->create([
                            'title' => $reference['title'],
                            'url' => $reference['url'],
                        ]);
                    }
                }
            }

            // Save Attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('blog-attachments', 'public');
                    $post->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            // Sync tags
            if ($request->has('tags')) {
                $post->tags()->sync($request->tags);
            }

            DB::commit();

            return redirect()->route('admin.blog.posts.index')
                ->with('success', 'Blog post created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to create blog post: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit(BlogPost $post)
    {
        $post = BlogPost::select(['id', 'blog_category_id', 'user_id', 'slug', 'image', 'image_url', 
                                  'image_description', 'is_visible', 'is_slider',
                                  'is_featured', 'is_breaking', 'is_recommended', 'registered_only',
                                  'is_paid_only', 'publish_status', 'publish_date', 'show_author',
                                  'allow_print_pdf'])
                       ->where('id', $post->id)->firstOrFail();

        $languages = Language::select(['id', 'name', 'code'])->where('is_active', true)->get();
        $categories = BlogCategory::select(['id'])->where('is_active', true)->with(['translations:id,blog_category_id,name'])->get();
        $users = User::select(['id', 'name', 'email'])->get();
        $tags = BlogTag::active()->with(['translations:id,blog_tag_id,name'])->get();

        $post->load(['translations', 'references', 'attachments', 'tags']);
        return view('admin.blog.posts.edit', compact('post', 'languages', 'categories', 'users', 'tags'));
    }

    /**
     * Update the specified blog post in storage.
     */
    public function update(Request $request, BlogPost $post)
    {
        // Load existing translations to get old content for image garbage collection
        $post = BlogPost::with('translations')->findOrFail($post->id);

        // Extract old images
        $oldImages = [];
        foreach ($post->translations as $translation) {
            $oldImages = array_merge($oldImages, $this->extractImagePaths($translation->content));
        }

        // Build validation rules dynamically to handle optional attachments properly
        $validationRules = [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'user_id' => 'required|exists:users,id',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $post->id,
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'image_url' => 'nullable|url|max:500',
            'image_description' => 'nullable|string|max:500',
            'remove_image' => 'nullable|boolean',
            'is_visible' => 'boolean',
            'is_slider' => 'boolean',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'is_recommended' => 'boolean',
            'registered_only' => 'boolean',
            'is_paid_only' => 'boolean',
            'publish_status' => 'required|in:draft,scheduled,published',
            'publish_date' => 'nullable|date',
            'show_author' => 'boolean',
            'allow_print_pdf' => 'boolean',
            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.summary' => 'required|string',
            'translations.*.content' => 'required|string',
            'references' => 'nullable|array',
            'references.*.title' => 'nullable|string|max:255',
            'references.*.url' => 'nullable|url',
            'attachments' => 'nullable|array',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
        ];

        // Only validate attachment files if they are actually present in the request
        if ($request->hasFile('attachments')) {
            $validationRules['attachments.*'] = 'file|max:10240'; // 10MB max
        }

        $validated = $request->validate($validationRules);

        DB::beginTransaction();
        try {
            // Handle image upload and removal with server-side processing
            $imageData = [];
            
            // Remove existing image if requested
            if ($request->has('remove_image') && $post->image) {
                if (Storage::disk('public')->exists($post->image)) {
                    Storage::disk('public')->delete($post->image);
                }
                $imageData['image'] = null;
            }
            
            // Upload new image if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($post->image && Storage::disk('public')->exists($post->image)) {
                    Storage::disk('public')->delete($post->image);
                }
                
                $image = $request->file('image');
                
                // Process and convert to WebP using Intervention Image
                $processedImage = Image::read($image->getRealPath())
                    ->scaleDown(width: 1920, height: 1920)
                    ->toWebp(quality: 80);
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.webp';
                $directory = 'blog-images/' . date('Y/m/d');
                
                // Ensure directory exists
                Storage::disk('public')->makeDirectory($directory);
                
                // Save the processed image
                $imagePath = $directory . '/' . $filename;
                Storage::disk('public')->put($imagePath, (string) $processedImage);
                
                $imageData['image'] = $imagePath;
            }

            $post->update(array_merge([
                'is_visible' => $request->has('is_visible'),
                'is_slider' => $request->has('is_slider'),
                'is_featured' => $request->has('is_featured'),
                'is_breaking' => $request->has('is_breaking'),
                'is_recommended' => $request->has('is_recommended'),
                'registered_only' => $request->has('registered_only'),
                'is_paid_only' => $request->has('is_paid_only'),
                'show_author' => $request->has('show_author'),
                'allow_print_pdf' => $request->has('allow_print_pdf'),
            ], $imageData, Arr::except($validated, ['translations', 'references', 'attachments', 'image', 'remove_image'])));

            // Update translations
            foreach ($validated['translations'] as $translationData) {
                BlogPostTranslation::updateOrCreate(
                    [
                        'blog_post_id' => $post->id,
                        'language_id' => $translationData['language_id'],
                    ],
                    [
                        'title' => $translationData['title'],
                        'summary' => $translationData['summary'],
                        'content' => $translationData['content'],
                    ]
                );
            }

            // Update References (Delete all and recreate for simplicity, or handle IDs)
            // For simplicity, we'll delete existing and recreate if provided, OR we can handle IDs.
            // Let's assume the form sends all current references.
            // Actually, a better approach for "dynamic fields" is to delete all and recreate.
            $post->references()->delete();
            if ($request->has('references')) {
                foreach ($request->references as $reference) {
                    if (!empty($reference['title']) && !empty($reference['url'])) {
                        $post->references()->create([
                            'title' => $reference['title'],
                            'url' => $reference['url'],
                        ]);
                    }
                }
            }

            // Save New Attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('blog-attachments', 'public');
                    $post->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            // Handle Attachment Deletion (if any IDs passed)
            if ($request->has('delete_attachments')) {
                foreach ($request->delete_attachments as $attachmentId) {
                    $attachment = $post->attachments()->find($attachmentId);
                    if ($attachment) {
                        Storage::delete($attachment->file_path); // Ensure Storage facade is imported
                        $attachment->delete();
                    }
                }
            }

            // Sync tags
            if ($request->has('tags')) {
                $post->tags()->sync($request->tags);
            } else {
                $post->tags()->sync([]);
            }

            // Image Garbage Collection
            // 1. Extract images from new content
            $newImages = [];
            foreach ($validated['translations'] as $translationData) {
                $newImages = array_merge($newImages, $this->extractImagePaths($translationData['content']));
            }

            // 2. Find images that were in old content but not in new content
            $imagesToDelete = array_diff($oldImages, $newImages);

            // 3. Delete them from storage
            foreach ($imagesToDelete as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            DB::commit();

            return redirect()->route('admin.blog.posts.index')
                ->with('success', 'Blog post updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()
                ->with('error', 'Failed to update blog post: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified blog post from storage.
     */
    public function destroy(BlogPost $post)
    {
        $post = BlogPost::with('translations')->findOrFail($post->id);

        try {
            // Extract images from content before deleting
            $imagesToDelete = [];
            foreach ($post->translations as $translation) {
                $imagesToDelete = array_merge($imagesToDelete, $this->extractImagePaths($translation->content));
            }

            // Delete attachments
            foreach ($post->attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
            }

            $post->delete();

            // Delete images from storage after successful DB deletion
            foreach ($imagesToDelete as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Blog post deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete blog post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete blog posts
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No post IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = BlogPost::whereIn('id', $ids)->count();
            BlogPost::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount post(s) deleted successfully."
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Bulk deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload image from editor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
        ]);

        try {
            // Get the uploaded file
            $image = $request->file('image');

            // Create a unique name for the file
            $extension = $image->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;

            // Store the image in the blog-images directory, organized by date
            $path = $image->storeAs('blog-images/' . date('Y/m/d'), $fileName, 'public');

            // Generate the URL for the image
            $imageUrl = asset('storage/' . $path);

            return response()->json([
                'success' => true,
                'url' => $imageUrl,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Helper to extract image paths from HTML content
     * Returns array of paths relative to public disk root (e.g. 'blog-images/...')
     */
    private function extractImagePaths(?string $content): array
    {
        if (empty($content)) {
            return [];
        }

        $paths = [];
        // Match all img src attributes
        if (preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches)) {
            foreach ($matches[1] as $url) {
                // Parse the URL path
                $path = parse_url($url, PHP_URL_PATH);
                
                // Decode URL encoding (e.g. %20)
                $path = urldecode($path);

                // Check if it's a storage URL
                // Assuming storage URL structure is /storage/path/to/file
                if (strpos($path, '/storage/') !== false) {
                    // Extract the part after /storage/
                    // explode limit 2 ensures we only split on the first occurrence if multiple (unlikely)
                    $parts = explode('/storage/', $path, 2);
                    if (isset($parts[1])) {
                        $paths[] = $parts[1];
                    }
                }
            }
        }
        
        return array_unique($paths);
    }
}