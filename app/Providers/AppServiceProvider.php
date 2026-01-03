<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{
    // Super Admin has access to everything
    Gate::before(function ($user, $ability) {
        return $user->hasRole('Super Admin') ? true : null;
    });

    // Prevent lazy loading in non-production
    Model::preventLazyLoading(! app()->isProduction());

    // Frontend password reset URL
    ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
        return config('app.frontend_url')
            . "/password-reset/{$token}?email={$notifiable->getEmailForPasswordReset()}";
    });

    /*
     |--------------------------------------------------------------------------
     | Database-dependent logic (GUARDED)
     |--------------------------------------------------------------------------
     */
    if (
        Schema::hasTable('languages') &&
        Schema::hasTable('settings')
    ) {
        // Share active languages with admin header
        View::composer('admin.layouts.header', function ($view) {
            $languages = Language::active()->orderBy('name')->get();
            $view->with('languages', $languages);
        });

        // Share settings with all views
        View::share('app_name', setting('app_name'));
        View::share('app_tagline', setting('app_tagline'));
        View::share('company_name', setting('company_name'));
        View::share('company_email', setting('company_email'));
        View::share('company_phone', setting('company_phone'));
        View::share('company_address', setting('company_address'));
        View::share('copyright_text', setting('copyright_text'));
    }
}

}
