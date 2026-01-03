<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdMobResource;
use App\Models\AdMobSetting;

class AdMobController extends Controller
{
    /**
     * Get AdMob settings for mobile app
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ad_mob_settings()
    {
        $ad_mob_settings = AdMobSetting::first();

        if (! $ad_mob_settings) {
            return response()->json([
                'message' => 'AdMob settings not configured',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'AdMob settings retrieved successfully',
            'data' => new AdMobResource($ad_mob_settings),
        ]);
    }
}
