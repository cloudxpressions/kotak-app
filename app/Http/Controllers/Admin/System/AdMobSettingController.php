<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\AdMobSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdMobSettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:system.settings', only: ['index', 'update']),
        ];
    }

    public function index()
    {
        $adMobSetting = AdMobSetting::first(); // Assuming only one record
        
        if (!$adMobSetting) {
            // Create a default record if it doesn't exist
            $adMobSetting = AdMobSetting::create([]);
        }
        
        return view('admin.system.ad_mob_settings.index', compact('adMobSetting'));
    }

    public function update(Request $request)
    {
        $adMobSetting = AdMobSetting::first(); // Assuming only one record
        
        if (!$adMobSetting) {
            // Create if doesn't exist
            $adMobSetting = AdMobSetting::create([]);
        }

        $validated = $request->validate([
            'app_id' => 'nullable|string|max:255',
            'banner_id' => 'nullable|string|max:255',
            'interstitial_id' => 'nullable|string|max:255',
            'rewarded_id' => 'nullable|string|max:255',
            'native_id' => 'nullable|string|max:255',
            'is_live' => 'boolean',
        ]);

        $adMobSetting->update($validated);

        return redirect()->route('admin.system.ad-mob-settings.index')
            ->with('success', 'AdMob settings updated successfully.');
    }
}