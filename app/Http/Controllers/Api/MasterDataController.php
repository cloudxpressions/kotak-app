<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Community;
use App\Models\Country;
use App\Models\Currency;
use App\Models\DACategory;
use App\Models\DateFormat;
use App\Models\Language;
use App\Models\Religion;
use App\Models\SpecialCategory;
use App\Models\State;
use App\Models\TimeZone;
use App\Models\UserClassification;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * Get all countries
     */
    public function countries()
    {
        $countries = Country::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $countries]);
    }

    /**
     * Get states by country
     */
    public function states(Request $request)
    {
        $countryId = $request->query('country_id');

        $query = State::select('id', 'name', 'country_id');

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        $states = $query->orderBy('name')->get();

        return response()->json(['data' => $states]);
    }

    /**
     * Get cities by state
     */
    public function cities(Request $request)
    {
        $stateId = $request->query('state_id');

        $query = City::select('id', 'name', 'state_id');

        if ($stateId) {
            $query->where('state_id', $stateId);
        }

        $cities = $query->orderBy('name')->get();

        return response()->json(['data' => $cities]);
    }

    /**
     * Get all languages
     */
    public function languages()
    {
        $languages = Language::select('id', 'name', 'code')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $languages]);
    }

    /**
     * Get all timezones
     */
    public function timezones()
    {
        $timezones = TimeZone::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('utc_offset_minutes')
            ->get();

        return response()->json(['data' => $timezones]);
    }

    /**
     * Get all currencies
     */
    public function currencies()
    {
        $currencies = Currency::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $currencies]);
    }

    /**
     * Get all date formats
     */
    public function dateFormats()
    {
        $formats = DateFormat::select('id', 'normal_view')
            ->where('is_active', true)
            ->get();

        return response()->json(['data' => $formats]);
    }

    /**
     * Get all communities
     */
    public function communities()
    {
        $communities = Community::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $communities]);
    }

    /**
     * Get all religions
     */
    public function religions()
    {
        $religions = Religion::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $religions]);
    }

    /**
     * Get all user classifications
     */
    public function userClassifications()
    {
        $classifications = UserClassification::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $classifications]);
    }

    /**
     * Get all DA categories
     */
    public function daCategories()
    {
        $categories = DACategory::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    /**
     * Get all special categories
     */
    public function specialCategories()
    {
        $categories = SpecialCategory::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    /**
     * Get all master data in one call (for initial load)
     */
    public function all()
    {
        return response()->json([
            'data' => [
                'countries' => Country::select('id', 'name')->where('is_active', true)->orderBy('name')->get(),
                'languages' => Language::select('id', 'name')->where('is_active', true)->orderBy('name')->get(),
                'timezones' => TimeZone::select('id', 'name')->where('is_active', true)->orderBy('utc_offset_minutes')->get(),
                'currencies' => Currency::select('id', 'name')->where('is_active', true)->orderBy('name')->get(),
                'date_formats' => DateFormat::select('id', 'normal_view')->where('is_active', true)->get(),
                'communities' => Community::select('id', 'name')->orderBy('name')->get(),
                'religions' => Religion::select('id', 'name')->orderBy('name')->get(),
                'user_classifications' => UserClassification::select('id', 'name')->orderBy('name')->get(),
                'da_categories' => DACategory::select('id', 'name')->orderBy('name')->get(),
                'special_categories' => SpecialCategory::select('id', 'name')->orderBy('name')->get(),
            ],
        ]);
    }
}
