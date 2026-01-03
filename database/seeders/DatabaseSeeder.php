<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * IMPORTANT: Seeders are run in dependency order.
     * Each step depends on data from previous steps.
     */
    public function run(): void
    {
        // ========================================
        // STEP 1: Core System Configuration
        // No dependencies - must run first
        // ========================================
        $this->call([
            LanguageSeeder::class,          // Required by: FAQs, Blog, Admin, Users
            CurrencySeeder::class,          // Required by: Users
            DateFormatSeeder::class,        // Required by: Users
            TimeZoneSeeder::class,          // Required by: Users
        ]);

        // ========================================
        // STEP 2: Authentication & Authorization
        // Depends on: Languages
        // ========================================
        $this->call([
            RolePermissionSeeder::class,    // Required by: Admin, Users
            AdminSeeder::class,             // Depends on: Roles/Permissions
        ]);

        // ========================================
        // STEP 3: Location Data (Hierarchical)
        // No dependencies on other seeders
        // ========================================
        $this->call([
            CountrySeeder::class,           // Required by: States, Users
            StateSeeder::class,             // Depends on: Countries, Required by: Cities, Users
            CitySeeder::class,              // Depends on: States, Required by: Users
        ]);

        // ========================================
        // STEP 4: User Classification & Categories
        // No dependencies on other seeders
        // ========================================
        $this->call([
            UserClassificationSeeder::class, // Required by: Users
            CommunitySeeder::class,          // Required by: Users
            ReligionSeeder::class,           // Required by: Users
            DACategorySeeder::class,         // Required by: Users
            SpecialCategorySeeder::class,    // Required by: Users
        ]);

        // ========================================
        // STEP 5: Application Settings & Content
        // Depends on: Languages (for translations)
        // ========================================
        $this->call([
            SettingsTableSeeder::class,     // General app settings
            AdMobSettingSeeder::class,      // AdMob configuration
            LegalPageSeeder::class,         // Legal pages
            TestimonialSeeder::class,       // Testimonials
        ]);

        // ========================================
        // STEP 6: Blog System
        // Depends on: Languages (for translations)
        // ========================================
        $this->call([
            BlogSeeder::class,              // Blog categories, tags, posts (with translations)
        ]);

        // ========================================
        // STEP 7: FAQs
        // Depends on: Languages (for translations)
        // ========================================
        $this->call([
            FaqSeeder::class,               // FAQs with English & Tamil translations
        ]);

        // ========================================
        // STEP 8: Users (Final Step)
        // Depends on: ALL above tables
        // ========================================
        $this->call([
            UserSeeder::class,              // Regular users (depends on everything)
        ]);
    }
}
