<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Models\Language;

class LanguageController extends Controller
{
    /**
     * Get all active legal pages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $languages = Language::active()
            ->orderBy('name')
            ->get();

        return response()->json([
            'message' => 'Languages retrieved successfully',
            'data' => LanguageResource::collection($languages),
        ]);
    }
}
