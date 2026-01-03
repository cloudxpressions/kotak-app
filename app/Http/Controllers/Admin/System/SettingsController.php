<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Settings as SettingsService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings.view', only: ['index']),
            new Middleware('permission:settings.update', only: ['update']),
        ];
    }

    /**
     * Display the settings page
     */
    public function index()
    {
        // Get all settings to display in the form
        $settings = [
            'app_name' => setting('app_name', 'Kalviplus'),
            'app_tagline' => setting('app_tagline', 'Learn Smarter. Grow Faster.'),
            'company_name' => setting('company_name', 'Kalviplus Pvt Ltd'),
            'company_email' => setting('company_email', 'support@kalviplus.com'),
            'company_phone' => setting('company_phone', '+91-9876543210'),
            'company_address' => setting('company_address', 'Chennai, Tamil Nadu, India'),
            'copyright_text' => setting('copyright_text', '© ' . date('Y') . ' Kalviplus'),
            'maintenance_mode' => setting('maintenance_mode', '0'),
            'app_theme' => setting('app_theme', 'default'),
            'enable_registration' => setting('enable_registration', '1'),
            'email_from_address' => setting('email_from_address', 'support@kalviplus.com'),
            'email_from_name' => setting('email_from_name', 'Kalviplus Support'),
        ];

        return view('admin.system.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_tagline' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string|max:500',
            'copyright_text' => 'nullable|string|max:255',
            'maintenance_mode' => 'sometimes|boolean',
            'app_theme' => 'nullable|string|max:50',
            'enable_registration' => 'sometimes|boolean',
            'email_from_address' => 'nullable|email|max:255',
            'email_from_name' => 'nullable|string|max:255',
        ]);

        // Update all settings
        foreach ($validatedData as $key => $value) {
            \App\Support\Settings::set($key, $value);
        }

        return redirect()->route('admin.system.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}