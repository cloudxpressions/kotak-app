<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LegalPageResource;
use App\Models\LegalPage;

class LegalPageController extends Controller
{
    /**
     * Get all active legal pages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $legalPages = LegalPage::where('is_active', true)
            ->orderBy('title', 'asc')
            ->get();

        return response()->json([
            'message' => 'Legal pages retrieved successfully',
            'data' => LegalPageResource::collection($legalPages),
        ]);
    }

    /**
     * Get a specific legal page by slug
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        $legalPage = LegalPage::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$legalPage) {
            return response()->json([
                'message' => 'Legal page not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Legal page retrieved successfully',
            'data' => new LegalPageResource($legalPage),
        ]);
    }
}
