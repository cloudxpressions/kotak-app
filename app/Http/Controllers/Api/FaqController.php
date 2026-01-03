<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class FaqController extends Controller
{
    /**
     * Get FAQs for the marketing/app surfaces.
     */
    public function index(Request $request)
    {
        $locale = $request->get('locale') ?: $request->get('lang');

        if ($locale && Language::where('code', $locale)->exists()) {
            App::setLocale($locale);
        }

        $query = Faq::with(['translations.language'])
            ->active()
            ->orderBy('sort_order')
            ->orderBy('created_at');

        if ($request->boolean('featured')) {
            $query->featured();
        }

        if ($request->filled('category')) {
            $query->whereHas('translations', function ($q) use ($request) {
                $q->where('category', $request->get('category'));
            });
        }

        $faqs = $query->get();

        return response()->json([
            'message' => 'FAQs retrieved successfully',
            'data' => FaqResource::collection($faqs),
        ]);
    }
}
