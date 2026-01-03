<?php

use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::post('/login', [UserAuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/register', [UserAuthController::class, 'register'])->middleware('throttle:3,1'); // 3 attempts per minute

// Master Data endpoints (public - needed for registration/profile forms)
Route::prefix('master-data')->group(function () {
    Route::get('/countries', [\App\Http\Controllers\Api\MasterDataController::class, 'countries']);
    Route::get('/states', [\App\Http\Controllers\Api\MasterDataController::class, 'states']);
    Route::get('/cities', [\App\Http\Controllers\Api\MasterDataController::class, 'cities']);
    Route::get('/languages', [\App\Http\Controllers\Api\MasterDataController::class, 'languages']);
    Route::get('/timezones', [\App\Http\Controllers\Api\MasterDataController::class, 'timezones']);
    Route::get('/currencies', [\App\Http\Controllers\Api\MasterDataController::class, 'currencies']);
    Route::get('/date-formats', [\App\Http\Controllers\Api\MasterDataController::class, 'dateFormats']);
    Route::get('/communities', [\App\Http\Controllers\Api\MasterDataController::class, 'communities']);
    Route::get('/religions', [\App\Http\Controllers\Api\MasterDataController::class, 'religions']);
    Route::get('/user-classifications', [\App\Http\Controllers\Api\MasterDataController::class, 'userClassifications']);
    Route::get('/da-categories', [\App\Http\Controllers\Api\MasterDataController::class, 'daCategories']);
    Route::get('/special-categories', [\App\Http\Controllers\Api\MasterDataController::class, 'specialCategories']);
    Route::get('/all', [\App\Http\Controllers\Api\MasterDataController::class, 'all']); // Get all at once
});

// AdMob Settings (public)
Route::get('/admob/settings', [\App\Http\Controllers\Api\AdMobController::class, 'ad_mob_settings']);

// Testimonials (public)
Route::get('/testimonials', [\App\Http\Controllers\Api\TestimonialController::class, 'index']);

// Legal Pages (public)
Route::prefix('legal-pages')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\LegalPageController::class, 'index']);
    Route::get('/{slug}', [\App\Http\Controllers\Api\LegalPageController::class, 'show']);
});

// Blog (public)
Route::prefix('blog')->group(function () {
    Route::get('/categories', [\App\Http\Controllers\Api\BlogController::class, 'categories']);
    Route::get('/tags', [\App\Http\Controllers\Api\BlogController::class, 'tags']);
    Route::get('/posts', [\App\Http\Controllers\Api\BlogController::class, 'posts']);
    Route::get('/posts/{slug}', [\App\Http\Controllers\Api\BlogController::class, 'show']);
    Route::get('/posts/{id}/comments', [\App\Http\Controllers\Api\BlogController::class, 'comments']);
});

// Newsletter (public)
Route::prefix('newsletter')->group(function () {
    Route::post('/subscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'subscribe']);
    Route::get('/verify/{token}', [\App\Http\Controllers\Api\NewsletterController::class, 'verify']);
    Route::post('/unsubscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'unsubscribe']);
});

// App Settings (public)
Route::prefix('settings')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
    Route::get('/{key}', [\App\Http\Controllers\Api\SettingsController::class, 'show']);
});

// reCAPTCHA (public)
Route::prefix('recaptcha')->group(function () {
    Route::get('/config', [\App\Http\Controllers\Api\RecaptchaController::class, 'config']);
    Route::post('/verify', [\App\Http\Controllers\Api\RecaptchaController::class, 'verify']);
});

// FAQs (public)
Route::get('/faqs', [FaqController::class, 'index']);

// Languages (public)
Route::get('/languages', [LanguageController::class, 'index']);

// Protected routes (require authentication)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () { // 60 requests per minute
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Profile Management
    Route::prefix('user')->group(function () {
        Route::get('/profile', [UserProfileController::class, 'show']);
        Route::put('/profile', [UserProfileController::class, 'update']);
        Route::put('/password', [UserProfileController::class, 'updatePassword']);

        // Image Management
        Route::post('/image', [UserProfileController::class, 'uploadImage']);
        Route::post('/image/delete', [UserProfileController::class, 'deleteImage']);

        // Education
        Route::get('/education', [UserProfileController::class, 'getEducation']);
        Route::put('/education', [UserProfileController::class, 'updateEducation']);

        // Skills
        Route::get('/skills', [UserProfileController::class, 'getSkills']);
        Route::put('/skills', [UserProfileController::class, 'updateSkills']);

        // Account Deletion
        Route::post('/delete-request', [UserProfileController::class, 'requestAccountDeletion']);
    });

    // Authentication
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::post('/logout-all', [UserAuthController::class, 'logoutAll']);

    // Blog (authenticated)
    Route::prefix('blog')->group(function () {
        Route::post('/posts/{id}/comments', [\App\Http\Controllers\Api\BlogController::class, 'addComment']);
        Route::post('/posts/{id}/rate', [\App\Http\Controllers\Api\BlogController::class, 'rate']);
    });
});
