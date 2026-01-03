<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     *
     * @param NewsletterSubscribeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(NewsletterSubscribeRequest $request)
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'status' => 'pending', // Requires email verification
            'verify_token' => Str::random(64),
            'source' => $request->source ?? 'website',
        ]);

        // TODO: Send verification email
        // You can implement email sending here using Laravel Mail

        return response()->json([
            'message' => 'Subscription successful! Please check your email to confirm.',
            'data' => [
                'email' => $subscriber->email,
                'status' => $subscriber->status,
            ],
        ], 201);
    }

    /**
     * Verify email subscription
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(string $token)
    {
        $subscriber = NewsletterSubscriber::where('verify_token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$subscriber) {
            return response()->json([
                'message' => 'Invalid or expired verification token',
            ], 404);
        }

        $subscriber->update([
            'status' => 'subscribed',
            'subscribed_at' => now(),
            'verify_token' => null,
        ]);

        return response()->json([
            'message' => 'Email verified successfully! You are now subscribed to our newsletter.',
            'data' => [
                'email' => $subscriber->email,
                'status' => $subscriber->status,
            ],
        ]);
    }

    /**
     * Unsubscribe from newsletter
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if (!$subscriber) {
            return response()->json([
                'message' => 'Email not found in our subscriber list',
            ], 404);
        }

        $subscriber->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);

        return response()->json([
            'message' => 'You have been unsubscribed successfully',
            'data' => [
                'email' => $subscriber->email,
                'status' => $subscriber->status,
            ],
        ]);
    }
}
