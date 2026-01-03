<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogPostAttachment;
use App\Models\BlogPostRating;
use App\Models\BlogPostReference;
use App\Models\BlogTag;
use App\Models\Language;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Ensure Languages exist
        $englishLang = Language::where('code', 'en')->first();
        $tamilLang = Language::where('code', 'ta')->first();

        if (! $englishLang || ! $tamilLang) {
            $this->command->error('Languages not found. Please run LanguageSeeder first.');

            return;
        }

        // 2. Ensure Categories exist (Call BlogCategorySeeder if needed)
        if (BlogCategory::count() == 0) {
            $this->call(BlogCategorySeeder::class);
        }
        $categories = BlogCategory::all();

        // 3. Create Tags
        $tags = [];
        $tagNames = ['UPSC', 'TNPSC', 'Exam Tips', 'Interview', 'Study Material', 'News', 'Announcement', 'Result', 'Syllabus', 'General Knowledge'];

        foreach ($tagNames as $tagName) {
            $slug = \Illuminate\Support\Str::slug($tagName);

            // Check if tag exists via translation
            $existingTag = BlogTag::whereHas('translations', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })->first();

            if ($existingTag) {
                $tags[] = $existingTag;
            } else {
                $tag = BlogTag::create(['is_active' => true]);

                $tag->translations()->create([
                    'language_id' => $englishLang->id,
                    'name' => $tagName,
                    'slug' => $slug,
                ]);
                $tag->translations()->create([
                    'language_id' => $tamilLang->id,
                    'name' => $tagName.' (TA)',
                    'slug' => $slug.'-ta',
                ]);
                $tags[] = $tag;
            }
        }

        // 4. Create Users if none exist (for ratings/comments)
        if (User::count() == 0) {
            User::factory(5)->create();
        }
        $users = User::all();
        $adminUser = \App\Models\Admin::first(); // Assuming an admin exists, or use a user for 'user_id' in blog_posts

        // 5. Create 10 Blog Posts
        for ($i = 1; $i <= 10; $i++) {
            DB::transaction(function () use ($faker, $i, $categories, $tags, $users, $englishLang, $tamilLang) {
                $category = $categories->random();
                $author = $users->random(); // Post author

                // Create Post
                $post = BlogPost::create([
                    'blog_category_id' => $category->id,
                    'user_id' => $author->id, // Using User as author based on schema
                    'slug' => 'blog-post-'.$i.'-'.time(),
                    'is_visible' => true,
                    'is_slider' => $faker->boolean(20),
                    'is_featured' => $faker->boolean(20),
                    'is_breaking' => $faker->boolean(10),
                    'is_recommended' => $faker->boolean(15),
                    'registered_only' => $faker->boolean(10),
                    'is_paid_only' => false,
                    'publish_status' => 'published',
                    'publish_date' => now(),
                    'show_author' => true,
                    'allow_print_pdf' => true,
                ]);

                // Translations
                $titleEn = "Blog Post Title $i: ".$faker->sentence(3);
                $titleTa = "வலைப்பதிவு தலைப்பு $i: ".$faker->sentence(3); // Dummy Tamil

                $post->translations()->create([
                    'language_id' => $englishLang->id,
                    'title' => $titleEn,
                    'summary' => $faker->paragraph,
                    'content' => $faker->paragraphs(3, true),
                ]);

                $post->translations()->create([
                    'language_id' => $tamilLang->id,
                    'title' => $titleTa,
                    'summary' => $faker->paragraph.' (Tamil)',
                    'content' => $faker->paragraphs(3, true).' (Tamil Content)',
                ]);

                // Attach Tags
                $post->tags()->attach(collect($tags)->random(rand(1, 3))->pluck('id'));

                // References
                $numRefs = rand(0, 3);
                for ($j = 0; $j < $numRefs; $j++) {
                    BlogPostReference::create([
                        'blog_post_id' => $post->id,
                        'title' => 'Reference '.($j + 1),
                        'url' => $faker->url,
                    ]);
                }

                // Attachments (Dummy)
                $numAtt = rand(0, 2);
                for ($j = 0; $j < $numAtt; $j++) {
                    BlogPostAttachment::create([
                        'blog_post_id' => $post->id,
                        'file_path' => 'dummy/path/file-'.$j.'.pdf',
                        'file_name' => 'Document-'.($j + 1).'.pdf',
                    ]);
                }

                // Ratings
                $numRatings = rand(0, 5);
                $ratedUsers = $users->random(min($numRatings, $users->count()));
                foreach ($ratedUsers as $rUser) {
                    BlogPostRating::create([
                        'blog_post_id' => $post->id,
                        'user_id' => $rUser->id,
                        'rating' => rand(3, 5), // Mostly good ratings
                    ]);
                }

                // Comments
                $numComments = rand(0, 5);
                for ($j = 0; $j < $numComments; $j++) {
                    $isUser = $faker->boolean(70);
                    $commenter = $isUser ? $users->random() : null;

                    BlogComment::create([
                        'blog_post_id' => $post->id,
                        'commentable_id' => $commenter ? $commenter->id : null,
                        'commentable_type' => $commenter ? 'App\\Models\\User' : null,
                        'content' => $faker->sentence,
                        'is_approved' => $faker->boolean(80), // Mostly approved
                    ]);
                }
            });
        }

        $this->command->info('BlogSeeder completed successfully! 10 posts created with related data.');
    }
}
