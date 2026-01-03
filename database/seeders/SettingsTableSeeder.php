<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['key' => 'app_name',        'value' => 'Kalviplus'],
            ['key' => 'app_tagline',     'value' => 'Learn Smarter. Grow Faster.'],
            ['key' => 'app_logo',        'value' => '/storage/settings/app_logo.png'],
            ['key' => 'app_favicon',     'value' => '/storage/settings/favicon.ico'],
            ['key' => 'company_name',    'value' => 'Kalviplus Pvt Ltd'],
            ['key' => 'company_email',   'value' => 'support@kalviplus.com'],
            ['key' => 'company_phone',   'value' => '+91-9876543210'],
            ['key' => 'company_address', 'value' => 'Chennai, Tamil Nadu, India'],
            ['key' => 'copyright_text',  'value' => '© ' . date('Y') . ' Kalviplus'],
            ['key' => 'maintenance_mode', 'value' => '0'],
            ['key' => 'app_theme', 'value' => 'default'],
            ['key' => 'enable_registration', 'value' => '1'],
            ['key' => 'enable_email_verification', 'value' => '0'],
            ['key' => 'email_from_address', 'value' => 'support@kalviplus.com'],
            ['key' => 'email_from_name', 'value' => 'Kalviplus Support'],
        ];

        foreach ($items as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value']]
            );
        }
    }
}