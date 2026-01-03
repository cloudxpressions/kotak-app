<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get IDs from seeded data with fallbacks
        $indiaCountry = DB::table('countries')->where('iso2', 'IN')->first();
        if (! $indiaCountry) {
            $indiaCountry = DB::table('countries')->where('is_active', 1)->first();
        }

        if (! $indiaCountry) {
            echo "Warning: No countries found in database. Skipping user seeding.\n";

            return;
        }

        // Use state_code 'TN' for Tamil Nadu
        $tamilNaduState = DB::table('states')
            ->where('code', 'TN')
            ->where('country_id', $indiaCountry->id)
            ->first();
        if (! $tamilNaduState) {
            $tamilNaduState = DB::table('states')->where('country_id', $indiaCountry->id)->where('is_active', 1)->first();
        }

        if (! $tamilNaduState) {
            echo "Warning: No states found for India. Skipping user seeding.\n";

            return;
        }

        // Chennai city lookup with 3-tier fallback
        // 1. Case-insensitive city lookup in Tamil Nadu
        $chennaiCity = DB::table('cities')
            ->whereRaw('LOWER(name) = ?', ['chennai'])
            ->where('state_id', $tamilNaduState->id)
            ->first();

        // 2. Fallback: any Chennai in India
        if (! $chennaiCity) {
            $chennaiCity = DB::table('cities')
                ->whereRaw('LOWER(name) = ?', ['chennai'])
                ->where('country_id', $indiaCountry->id)
                ->first();
        }

        // 3. Final fallback: any active city in Tamil Nadu
        if (! $chennaiCity) {
            $chennaiCity = DB::table('cities')
                ->where('state_id', $tamilNaduState->id)
                ->where('is_active', 1)
                ->first();
        }

        if (! $chennaiCity) {
            echo "Warning: No cities found for Tamil Nadu. Skipping user seeding.\n";

            return;
        }

        $englishLang = DB::table('languages')->where('code', 'en')->first();
        if (! $englishLang) {
            echo "Warning: English language not found. Skipping user seeding.\n";

            return;
        }

        $tamilLang = DB::table('languages')->where('code', 'ta')->first() ?? $englishLang;
        $inrCurrency = DB::table('currencies')->where('code', 'INR')->first();
        if (! $inrCurrency) {
            echo "Warning: INR currency not found. Skipping user seeding.\n";

            return;
        }

        $usdCurrency = DB::table('currencies')->where('code', 'USD')->first();

        $hinduReligion = DB::table('religions')->where('name', 'Hindu')->first();
        $christianReligion = DB::table('religions')->where('name', 'Christian')->first();

        $ocCommunity = DB::table('communities')->where('name', 'LIKE', '%General%')->first();
        $studentClassification = DB::table('user_classifications')->where('name', 'Student')->first();
        $professionalClassification = DB::table('user_classifications')->where('name', 'Working Professional')->first();

        $dateFormat = DB::table('date_formats')->where('format', 'LIKE', '%DD%MM%YYYY%')->first();
        if (! $dateFormat) {
            $dateFormat = DB::table('date_formats')->first();
        }

        if (! $dateFormat) {
            echo "Warning: No date formats found. Skipping user seeding.\n";

            return;
        }

        $timezone = DB::table('time_zones')->where('timezone', 'Asia/Kolkata')->first();
        if (! $timezone) {
            $timezone = DB::table('time_zones')->first();
        }

        if (! $timezone) {
            echo "Warning: No timezones found. Skipping user seeding.\n";

            return;
        }

        $users = [
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh.kumar@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),

                // Personal Details
                'mobile' => '9876543210',
                'whatsapp_number' => '9876543210',
                'dob' => '1995-05-15',
                'gender' => 'Male',
                'bio' => 'Competitive exam aspirant preparing for UPSC. Passionate about current affairs and Indian history.',
                'short_bio' => 'UPSC Aspirant | History Enthusiast',
                'is_differently_abled' => false,

                // Address Details
                'locality' => 'T Nagar',
                'address' => '123, Usman Road, T Nagar',
                'pincode' => '600017',
                'country_id' => $indiaCountry->id,
                'state_id' => $tamilNaduState->id,
                'city_id' => $chennaiCity->id,

                // Family Details
                'fathers_name' => 'Kumar Swamy',
                'mothers_name' => 'Lakshmi Kumar',
                'parent_mobile_number' => '9876543211',

                // Preferences
                'language_id' => $tamilLang->id,
                'dateformat_id' => $dateFormat->id,
                'timezone_id' => $timezone->id,
                'currency_id' => $inrCurrency->id,
                'medium_of_exam' => 'English',

                // Classifications
                'user_classifications_id' => $studentClassification->id,
                'community_id' => $ocCommunity->id,
                'religion_id' => $hinduReligion->id,
                'd_a_category_id' => null,
                'special_category_id' => null,

                // Social Links
                'facebook' => 'https://facebook.com/rajeshkumar',
                'twitter' => 'https://twitter.com/rajeshkumar',
                'linkedin' => 'https://linkedin.com/in/rajeshkumar',

                // Status
                'is_active' => true,
                'is_banned' => false,
                'dark_mode_enabled' => false,

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya.sharma@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),

                // Personal Details
                'mobile' => '9876543220',
                'whatsapp_number' => '9876543220',
                'dob' => '1998-08-22',
                'gender' => 'Female',
                'bio' => 'Software engineer passionate about learning new technologies and competitive programming.',
                'short_bio' => 'Software Engineer | Tech Enthusiast',
                'is_differently_abled' => false,

                // Address Details
                'locality' => 'Anna Nagar',
                'address' => '456, 2nd Avenue, Anna Nagar',
                'pincode' => '600040',
                'country_id' => $indiaCountry->id,
                'state_id' => $tamilNaduState->id,
                'city_id' => $chennaiCity->id,

                // Family Details
                'fathers_name' => 'Sharma Raj',
                'mothers_name' => 'Meena Sharma',
                'parent_mobile_number' => '9876543221',

                // Preferences
                'language_id' => $englishLang->id,
                'dateformat_id' => $dateFormat->id,
                'timezone_id' => $timezone->id,
                'currency_id' => $inrCurrency->id,
                'medium_of_exam' => 'English',

                // Classifications
                'user_classifications_id' => $professionalClassification->id,
                'community_id' => $ocCommunity->id,
                'religion_id' => $christianReligion->id,
                'd_a_category_id' => null,
                'special_category_id' => null,

                // Social Links
                'facebook' => null,
                'twitter' => 'https://twitter.com/priyasharma',
                'linkedin' => 'https://linkedin.com/in/priyasharma',

                // Status
                'is_active' => true,
                'is_banned' => false,
                'dark_mode_enabled' => true,

                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
