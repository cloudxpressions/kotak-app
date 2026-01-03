<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\Language;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get language IDs
        $englishLang = Language::where('code', 'en')->first();
        $tamilLang = Language::where('code', 'ta')->first();

        if (! $englishLang || ! $tamilLang) {
            $this->command->error('English or Tamil language not found. Please run LanguageSeeder first.');

            return;
        }

        $categories = [
            [
                'name' => [
                    'en' => 'General Studies',
                    'ta' => 'பொது அறிவு',
                ],
                'slug' => [
                    'en' => 'general-studies',
                    'ta' => 'general-studies',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'Current Affairs',
                    'ta' => 'தற்போதைய நிகழ்வுகள்',
                ],
                'slug' => [
                    'en' => 'current-affairs',
                    'ta' => 'current-affairs',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'History',
                    'ta' => 'வரலாறு',
                ],
                'slug' => [
                    'en' => 'history',
                    'ta' => 'history',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'Geography',
                    'ta' => 'பு geography',
                ],
                'slug' => [
                    'en' => 'geography',
                    'ta' => 'geography',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'Polity',
                    'ta' => 'அரசியல்',
                ],
                'slug' => [
                    'en' => 'polity',
                    'ta' => 'polity',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'Economy',
                    'ta' => 'பொருளாதாரம்',
                ],
                'slug' => [
                    'en' => 'economy',
                    'ta' => 'economy',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'Science & Technology',
                    'ta' => 'அறிவியல் & தொழில்நுட்பம்',
                ],
                'slug' => [
                    'en' => 'science-technology',
                    'ta' => 'science-technology',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'Environment',
                    'ta' => 'சுற்றுச்சூழல்',
                ],
                'slug' => [
                    'en' => 'environment',
                    'ta' => 'environment',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'Ethics',
                    'ta' => 'நெறிமுறைகள்',
                ],
                'slug' => [
                    'en' => 'ethics',
                    'ta' => 'ethics',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'en' => 'Optional Subjects',
                    'ta' => 'விருப்பப் பாடங்கள்',
                ],
                'slug' => [
                    'en' => 'optional-subjects',
                    'ta' => 'optional-subjects',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            // Create the base category
            $category = BlogCategory::create([
                'is_active' => $categoryData['is_active'],
            ]);

            // Create English translation
            $category->translations()->create([
                'language_id' => $englishLang->id,
                'name' => $categoryData['name']['en'],
                'slug' => $categoryData['slug']['en'],
            ]);

            // Create Tamil translation
            $category->translations()->create([
                'language_id' => $tamilLang->id,
                'name' => $categoryData['name']['ta'],
                'slug' => $categoryData['slug']['ta'],
            ]);
        }
    }
}
