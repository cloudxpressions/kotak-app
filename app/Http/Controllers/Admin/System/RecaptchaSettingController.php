<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\RecaptchaSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RecaptchaSettingController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:recaptcha.view', only: ['index']),
            new Middleware('permission:recaptcha.update', only: ['store']),
        ];
    }

    /**
     * Display the reCAPTCHA settings form
     */
    public function index()
    {
        $setting = RecaptchaSetting::current();
        return view('admin.system.recaptcha-setting.index', compact('setting'));
    }

    /**
     * Update the reCAPTCHA settings
     */
    public function store(Request $request)
    {
        $request->validate([
            'site_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'is_enabled' => 'boolean',
            'version' => 'required|in:v2_checkbox,v2_invisible,v3',
            'v3_score_threshold' => 'required_if:version,v3|numeric|min:0|max:1',
            'captcha_for_login' => 'boolean',
            'captcha_for_register' => 'boolean',
            'captcha_for_contact' => 'boolean',
        ]);

        $setting = RecaptchaSetting::current();

        $data = [
            'site_key' => $request->input('site_key'),
            'is_enabled' => $request->boolean('is_enabled'),
            'version' => $request->input('version'),
            'v3_score_threshold' => $request->input('version') === 'v3' ? $request->input('v3_score_threshold') : 0.5,
            'captcha_for_login' => $request->boolean('captcha_for_login'),
            'captcha_for_register' => $request->boolean('captcha_for_register'),
            'captcha_for_contact' => $request->boolean('captcha_for_contact'),
        ];

        // Only update secret key if provided (don't allow empty to override)
        if ($request->filled('secret_key')) {
            $data['secret_key'] = $request->input('secret_key');
        }

        $setting->update($data);

        return redirect()->route('admin.system.recaptcha-setting.index')
            ->with('success', 'reCAPTCHA settings updated successfully.');
    }
}