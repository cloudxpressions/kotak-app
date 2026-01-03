<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Currency;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:admin', only: ['index']),
        ];
    }

    /**
     * Display dashboard with statistics.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => Admin::count(),
            'total_countries' => Country::count(),
            'total_states' => State::count(),
            'total_cities' => City::count(),
            'total_languages' => Language::count(),
            'total_currencies' => Currency::count(),
            'total_blog_posts' => BlogPost::count(),
            'total_blog_categories' => BlogCategory::count(),
            'total_blog_tags' => BlogTag::count(),
            'recent_activities' => Activity::with('causer')
                ->latest()
                ->limit(10)
                ->get(),
            'today_activities' => Activity::whereDate('created_at', today())->count(),
            'recent_users' => User::latest()->limit(5)->get(),
        ];

        return view('admin.system.dashboard.index', compact('stats'));
    }
}