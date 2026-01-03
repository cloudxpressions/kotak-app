<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Get all public app settings
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $settings = Setting::all();

        // Transform to key-value pairs
        $settingsData = $settings->mapWithKeys(function ($setting) {
            $value = $setting->value;
            $decodedValue = json_decode($value, true);
            
            return [$setting->key => $decodedValue !== null ? $decodedValue : $value];
        });

        return response()->json([
            'message' => 'Settings retrieved successfully',
            'data' => $settingsData,
        ]);
    }

    /**
     * Get a specific setting by key
     *
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $key)
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'message' => 'Setting not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Setting retrieved successfully',
            'data' => new SettingResource($setting),
        ]);
    }
}
