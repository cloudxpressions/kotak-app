<?php

namespace Database\Seeders;

use App\Models\AdMobSetting;
use Illuminate\Database\Seeder;

class AdMobSettingSeeder extends Seeder
{
    /**
     * Seed default AdMob settings using Google's official test ids.
     */
    public function run(): void
    {
        AdMobSetting::updateOrCreate(
            ['id' => 1],
            [
                'app_id' => 'ca-app-pub-3940256099942544~3347511713',
                'banner_id' => 'ca-app-pub-3940256099942544/6300978111',
                'interstitial_id' => 'ca-app-pub-3940256099942544/1033173712',
                'rewarded_id' => 'ca-app-pub-3940256099942544/5224354917',
                'native_id' => 'ca-app-pub-3940256099942544/2247696110',
                'is_live' => false,
            ]
        );
    }
}
