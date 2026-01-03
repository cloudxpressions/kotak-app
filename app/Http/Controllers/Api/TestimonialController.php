<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    /**
     * Get all visible testimonials
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $testimonials = Testimonial::where('is_visible', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Testimonials retrieved successfully',
            'data' => TestimonialResource::collection($testimonials),
        ]);
    }
}
