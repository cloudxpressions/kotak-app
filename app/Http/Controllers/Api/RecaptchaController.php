<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecaptchaResource;
use App\Models\RecaptchaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecaptchaController extends Controller
{
    /**
     * Get reCAPTCHA configuration (public data only)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function config()
    {
        $recaptcha = RecaptchaSetting::first();

        if (!$recaptcha || !$recaptcha->is_enabled) {
            return response()->json([
                'message' => 'reCAPTCHA is not enabled',
                'data' => [
                    'is_enabled' => false,
                ],
            ]);
        }

        return response()->json([
            'message' => 'reCAPTCHA configuration retrieved successfully',
            'data' => new RecaptchaResource($recaptcha),
        ]);
    }

    /**
     * Verify reCAPTCHA token (server-side validation)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'action' => 'nullable|string', // For v3
        ]);

        $recaptcha = RecaptchaSetting::first();

        if (!$recaptcha || !$recaptcha->is_enabled) {
            return response()->json([
                'message' => 'reCAPTCHA is not enabled',
                'verified' => true, // Allow through if disabled
            ]);
        }

        // Verify with Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptcha->secret_key,
            'response' => $request->token,
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        // For v3, check score threshold
        if ($recaptcha->version === 'v3') {
            $score = $result['score'] ?? 0;
            $verified = $result['success'] && $score >= $recaptcha->v3_score_threshold;

            return response()->json([
                'message' => $verified ? 'reCAPTCHA verified successfully' : 'reCAPTCHA verification failed',
                'verified' => $verified,
                'score' => $score,
            ]);
        }

        // For v2
        return response()->json([
            'message' => $result['success'] ? 'reCAPTCHA verified successfully' : 'reCAPTCHA verification failed',
            'verified' => $result['success'] ?? false,
        ]);
    }
}
