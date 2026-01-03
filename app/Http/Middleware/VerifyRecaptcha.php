<?php

namespace App\Http\Middleware;

use App\Models\RecaptchaSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current reCAPTCHA settings
        $settings = RecaptchaSetting::current();

        // If reCAPTCHA is globally disabled or no keys are configured, continue
        if (!$settings->is_enabled || !$settings->site_key || !$settings->secret_key) {
            return $next($request);
        }

        // Determine the context (route) to see if CAPTCHA is required
        $routeName = $request->route()?->getName();

        // Skip validation if CAPTCHA is disabled for this route context
        $shouldSkip = false;

        if ($routeName === 'login' && !$settings->captcha_for_login) {
            $shouldSkip = true;
        } elseif ($routeName === 'register' && !$settings->captcha_for_register) {
            $shouldSkip = true;
        } elseif (str_contains($routeName ?? '', 'register') && !$settings->captcha_for_register) {
            $shouldSkip = true;
        } elseif (str_contains($routeName ?? '', 'login') && !$settings->captcha_for_login) {
            $shouldSkip = true;
        } elseif ($routeName === 'contact.send' && !$settings->captcha_for_contact) {
            $shouldSkip = true;
        }

        if ($shouldSkip) {
            return $next($request);
        }

        // Read the reCAPTCHA token from the request
        $token = $request->get('g-recaptcha-response') ?: $request->get('recaptcha_response') ?: $request->get('g-recaptcha-v3-token');

        if (!$token) {
            return back()->withErrors(['recaptcha' => 'Please verify you are not a robot.'])
                        ->withInput();
        }

        // Call Google's reCAPTCHA verification API
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $settings->secret_key,
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        if (!$response->successful()) {
            return back()->withErrors(['recaptcha' => 'Captcha verification failed.'])
                        ->withInput();
        }

        $responseData = $response->json();

        if (!$responseData['success']) {
            return back()->withErrors(['recaptcha' => 'Captcha verification failed.'])
                        ->withInput();
        }

        // For v3, also check the score
        if ($settings->version === 'v3') {
            $score = $responseData['score'] ?? 0;
            $threshold = $settings->v3_score_threshold;

            if ($score < $threshold) {
                return back()->withErrors(['recaptcha' => 'Captcha verification failed. Score too low.'])
                            ->withInput();
            }
        }

        // CAPTCHA verification passed, continue with the request
        return $next($request);
    }
}