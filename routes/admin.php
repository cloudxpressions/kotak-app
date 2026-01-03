<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Loaded by RouteServiceProvider inside "web" group with admin guard.
*/

/*
|--------------------------------------------------------------------------
| Guest (admin) Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware('guest:admin')
    ->group(function () {

        // Authentication
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        // Password Reset
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    });

/*
|--------------------------------------------------------------------------
| Authenticated (admin) Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [\App\Http\Controllers\Admin\System\DashboardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Profile Settings (Self Management)
        |--------------------------------------------------------------------------
        */
        Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update-basic-info', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updateBasicInfo'])->name('profile.update-basic-info');
        Route::put('/profile/update-password', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('profile.update-password');

        // Self Profile - Education & Skills
        Route::post('/profile/education', [\App\Http\Controllers\Admin\AdminProfileController::class, 'addEducation'])->name('profile.add-education');
        Route::put('/profile/education/{educationId}', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updateEducation'])->name('profile.update-education');
        Route::delete('/profile/education/{educationId}', [\App\Http\Controllers\Admin\AdminProfileController::class, 'deleteEducation'])->name('profile.delete-education');
        Route::post('/profile/skills', [\App\Http\Controllers\Admin\AdminProfileController::class, 'addSkill'])->name('profile.add-skill');
        Route::put('/profile/skills/{skillId}', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updateSkill'])->name('profile.update-skill');
        Route::delete('/profile/skills/{skillId}', [\App\Http\Controllers\Admin\AdminProfileController::class, 'deleteSkill'])->name('profile.delete-skill');

        /*
        |--------------------------------------------------------------------------
        | System Management
        |--------------------------------------------------------------------------
        */
        Route::prefix('system')
            ->name('system.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Languages
                |--------------------------------------------------------------------------
                */
                Route::resource('languages', \App\Http\Controllers\Admin\System\LanguageController::class);
                Route::post('languages/bulk-delete', [\App\Http\Controllers\Admin\System\LanguageController::class, 'bulkDelete'])->name('languages.bulk-delete');
                Route::post('languages/change', [\App\Http\Controllers\Admin\System\LanguageController::class, 'changeLanguage'])->name('languages.change');

                /*
                |--------------------------------------------------------------------------
                | Roles & Permissions
                |--------------------------------------------------------------------------
                */
                Route::resource('roles', \App\Http\Controllers\Admin\System\RolePermissionController::class);
                Route::post('roles/bulk-delete', [\App\Http\Controllers\Admin\System\RolePermissionController::class, 'bulkDelete'])->name('roles.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Countries
                |--------------------------------------------------------------------------
                */
                Route::resource('countries', \App\Http\Controllers\Admin\System\CountryController::class);
                Route::post('countries/bulk-delete', [\App\Http\Controllers\Admin\System\CountryController::class, 'bulkDelete'])->name('countries.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | States
                |--------------------------------------------------------------------------
                */
                Route::get('states/activate', [\App\Http\Controllers\Admin\System\StateController::class, 'activatePage'])->name('states.activate');
                Route::post('states/bulk-activate', [\App\Http\Controllers\Admin\System\StateController::class, 'bulkActivate'])->name('states.bulk-activate');

                Route::resource('states', \App\Http\Controllers\Admin\System\StateController::class);
                Route::post('states/bulk-delete', [\App\Http\Controllers\Admin\System\StateController::class, 'bulkDelete'])->name('states.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Cities
                |--------------------------------------------------------------------------
                */
                Route::get('cities/activate', [\App\Http\Controllers\Admin\System\CityController::class, 'activatePage'])->name('cities.activate');
                Route::post('cities/bulk-activate', [\App\Http\Controllers\Admin\System\CityController::class, 'bulkActivate'])->name('cities.bulk-activate');

                Route::resource('cities', \App\Http\Controllers\Admin\System\CityController::class);
                Route::post('cities/bulk-delete', [\App\Http\Controllers\Admin\System\CityController::class, 'bulkDelete'])->name('cities.bulk-delete');

                Route::get('states-by-country/{countryId}', [\App\Http\Controllers\Admin\System\CityController::class, 'getStatesByCountry'])->name('cities.get-states-by-country');
                Route::get('cities-by-state/{stateId}', [\App\Http\Controllers\Admin\System\CityController::class, 'getCitiesByState'])->name('cities.get-cities-by-state');

                /*
                |--------------------------------------------------------------------------
                | Currencies
                |--------------------------------------------------------------------------
                */
                Route::resource('currencies', \App\Http\Controllers\Admin\System\CurrencyController::class);
                Route::post('currencies/bulk-delete', [\App\Http\Controllers\Admin\System\CurrencyController::class, 'bulkDelete'])->name('currencies.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Date Formats
                |--------------------------------------------------------------------------
                */
                Route::resource('date-formats', \App\Http\Controllers\Admin\System\DateFormatController::class);
                Route::post('date-formats/bulk-delete', [\App\Http\Controllers\Admin\System\DateFormatController::class, 'bulkDelete'])->name('date-formats.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Communities
                |--------------------------------------------------------------------------
                */
                Route::resource('communities', \App\Http\Controllers\Admin\System\CommunityController::class);
                Route::post('communities/bulk-delete', [\App\Http\Controllers\Admin\System\CommunityController::class, 'bulkDelete'])->name('communities.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Disability Categories (DA)
                |--------------------------------------------------------------------------
                */
                Route::resource('da-categories', \App\Http\Controllers\Admin\System\DACategoryController::class);
                Route::post('da-categories/bulk-delete', [\App\Http\Controllers\Admin\System\DACategoryController::class, 'bulkDelete'])->name('da-categories.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Translations
                |--------------------------------------------------------------------------
                */
                Route::get('translations', [\App\Http\Controllers\Admin\System\TranslationController::class, 'index'])->name('translations.index');
                Route::post('translations/update', [\App\Http\Controllers\Admin\System\TranslationController::class, 'update'])->name('translations.update');

                Route::get('translations/export-excel', [\App\Http\Controllers\Admin\System\TranslationController::class, 'exportExcel'])->name('translations.export-excel');
                Route::get('translations/import', [\App\Http\Controllers\Admin\System\TranslationController::class, 'importForm'])->name('translations.import');
                Route::post('translations/import', [\App\Http\Controllers\Admin\System\TranslationController::class, 'import'])->name('translations.import.process');

                Route::post('translations/extract', [\App\Http\Controllers\Admin\System\TranslationController::class, 'extract'])->name('translations.extract');
                Route::post('translations/export', [\App\Http\Controllers\Admin\System\TranslationController::class, 'export'])->name('translations.export');

                /*
                |--------------------------------------------------------------------------
                | User Classifications
                |--------------------------------------------------------------------------
                */
                Route::resource('user-classifications', \App\Http\Controllers\Admin\System\UserClassificationController::class);
                Route::post('user-classifications/bulk-delete', [\App\Http\Controllers\Admin\System\UserClassificationController::class, 'bulkDelete'])->name('user-classifications.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Time Zones
                |--------------------------------------------------------------------------
                */
                Route::resource('time-zones', \App\Http\Controllers\Admin\System\TimeZoneController::class);
                Route::post('time-zones/bulk-delete', [\App\Http\Controllers\Admin\System\TimeZoneController::class, 'bulkDelete'])->name('time-zones.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Religions
                |--------------------------------------------------------------------------
                */
                Route::resource('religions', \App\Http\Controllers\Admin\System\ReligionController::class);
                Route::post('religions/bulk-delete', [\App\Http\Controllers\Admin\System\ReligionController::class, 'bulkDelete'])->name('religions.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Special Categories
                |--------------------------------------------------------------------------
                */
                Route::resource('special-categories', \App\Http\Controllers\Admin\System\SpecialCategoryController::class);
                Route::post('special-categories/bulk-delete', [\App\Http\Controllers\Admin\System\SpecialCategoryController::class, 'bulkDelete'])->name('special-categories.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Maintenance Manager
                |--------------------------------------------------------------------------
                */
                Route::resource('maintenances', \App\Http\Controllers\Admin\System\MaintenanceController::class);

                /*
                |--------------------------------------------------------------------------
                | FAQs
                |--------------------------------------------------------------------------
                */
                Route::resource('faqs', \App\Http\Controllers\Admin\System\FaqController::class);
                Route::post('faqs/bulk-delete', [\App\Http\Controllers\Admin\System\FaqController::class, 'bulkDelete'])->name('faqs.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Database Backups
                |--------------------------------------------------------------------------
                */
                Route::prefix('database-backups')
                    ->name('database-backups.')
                    ->group(function () {
                        Route::get('/', [\App\Http\Controllers\Admin\System\DatabaseBackupController::class, 'index'])->name('index');
                        Route::post('/create', [\App\Http\Controllers\Admin\System\DatabaseBackupController::class, 'create'])->name('create');
                        Route::get('/download/{filename}', [\App\Http\Controllers\Admin\System\DatabaseBackupController::class, 'download'])->name('download');
                        Route::delete('/{filename}', [\App\Http\Controllers\Admin\System\DatabaseBackupController::class, 'destroy'])->name('destroy');
                    });

                /*
                |--------------------------------------------------------------------------
                | Email Settings
                |--------------------------------------------------------------------------
                */
                Route::prefix('email-settings')
                    ->name('email-settings.')
                    ->group(function () {
                        Route::get('/', [\App\Http\Controllers\Admin\System\EmailSettingController::class, 'index'])->name('index');
                        Route::put('/update', [\App\Http\Controllers\Admin\System\EmailSettingController::class, 'update'])->name('update');
                        Route::post('/send-test-mail', [\App\Http\Controllers\Admin\System\EmailSettingController::class, 'sendTestMail'])->name('send-test-mail');
                    });

                /*
                |--------------------------------------------------------------------------
                | Newsletter Subscribers
                |--------------------------------------------------------------------------
                */
                Route::prefix('newsletter-subscribers')
                    ->name('newsletter-subscribers.')
                    ->group(function () {
                        Route::get('/', [\App\Http\Controllers\Admin\System\NewsletterSubscriberController::class, 'index'])->name('index');
                        Route::post('/', [\App\Http\Controllers\Admin\System\NewsletterSubscriberController::class, 'store'])->name('store');
                        Route::put('/{newsletterSubscriber}', [\App\Http\Controllers\Admin\System\NewsletterSubscriberController::class, 'update'])->name('update');
                        Route::delete('/{newsletterSubscriber}', [\App\Http\Controllers\Admin\System\NewsletterSubscriberController::class, 'destroy'])->name('destroy');
                        Route::post('/bulk-delete', [\App\Http\Controllers\Admin\System\NewsletterSubscriberController::class, 'bulkDelete'])->name('bulk-delete');
                    });

                /*
                |--------------------------------------------------------------------------
                | Newsletters
                |--------------------------------------------------------------------------
                */
                Route::prefix('newsletters')
                    ->name('newsletters.')
                    ->group(function () {
                        Route::get('/', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'index'])->name('index');
                        Route::get('/create', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'create'])->name('create');
                        Route::post('/', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'store'])->name('store');
                        Route::get('/{newsletter}', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'show'])->name('show');
                        Route::get('/{newsletter}/edit', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'edit'])->name('edit');
                        Route::put('/{newsletter}', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'update'])->name('update');
                        Route::delete('/{newsletter}', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'destroy'])->name('destroy');
                        Route::post('/{newsletter}/send', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'send'])->name('send');
                        Route::get('/{newsletter}/preview', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'preview'])->name('preview');
                        Route::post('/bulk-delete', [\App\Http\Controllers\Admin\System\NewsletterController::class, 'bulkDelete'])->name('bulk-delete');
                    });

                /*
                |--------------------------------------------------------------------------
                | Blocked IPs
                |--------------------------------------------------------------------------
                */
                Route::resource('blocked-ips', \App\Http\Controllers\Admin\System\BlockedIpController::class);

                /*
                |--------------------------------------------------------------------------
                | User Sessions
                |--------------------------------------------------------------------------
                */
                Route::resource('user-sessions', \App\Http\Controllers\Admin\System\UserSessionController::class)->only(['index', 'destroy']);
           
                /*
                |--------------------------------------------------------------------------
                | Recaptcha Settings
                |--------------------------------------------------------------------------
                */
                Route::prefix('recaptcha-setting')
                    ->name('recaptcha-setting.')
                    ->group(function () {
                        Route::get('/', [\App\Http\Controllers\Admin\System\RecaptchaSettingController::class, 'index'])->name('index');
                        Route::post('/', [\App\Http\Controllers\Admin\System\RecaptchaSettingController::class, 'store'])->name('store');
                    });

                /*
                |--------------------------------------------------------------------------
                | General Settings
                |--------------------------------------------------------------------------
                */
                Route::prefix('settings')
                    ->name('settings.')
                    ->group(function () {
                        Route::get('/', [\App\Http\Controllers\Admin\System\SettingsController::class, 'index'])->name('index');
                        Route::put('/update', [\App\Http\Controllers\Admin\System\SettingsController::class, 'update'])->name('update');
                    });

                        /*
                |--------------------------------------------------------------------------
                | Activity Logs
                |--------------------------------------------------------------------------
                */
                Route::prefix('activity-logs')
                    ->name('activity-log.')
                    ->group(function () {
                        Route::get('/', [\App\Http\Controllers\Admin\System\ActivityLogController::class, 'index'])->name('index');
                        Route::post('/bulk-delete', [\App\Http\Controllers\Admin\System\ActivityLogController::class, 'bulkDelete'])->name('bulk-delete');
                        Route::delete('/{activity}', [\App\Http\Controllers\Admin\System\ActivityLogController::class, 'destroy'])->name('destroy');
                    });

            });

            // AdMob Settings
            Route::get('ad-mob-settings', [\App\Http\Controllers\Admin\System\AdMobSettingController::class, 'index'])->name('system.ad-mob-settings.index');
            Route::put('ad-mob-settings', [\App\Http\Controllers\Admin\System\AdMobSettingController::class, 'update'])->name('system.ad-mob-settings.update');

            // Legal Pages
            Route::resource('legal-pages', \App\Http\Controllers\Admin\Legal\LegalPageController::class)->names('legal.pages')->parameters(['legal-pages' => 'legalPage']);
            Route::post('legal-pages/bulk-delete', [\App\Http\Controllers\Admin\Legal\LegalPageController::class, 'bulkDelete'])->name('legal.pages.bulk-delete');

            // Testimonials
            Route::resource('testimonials', \App\Http\Controllers\Admin\System\TestimonialController::class);
            Route::post('testimonials/bulk-delete', [\App\Http\Controllers\Admin\System\TestimonialController::class, 'bulkDelete'])->name('testimonials.bulk-delete');

        /*
        |--------------------------------------------------------------------------
        | Blog Management
        |--------------------------------------------------------------------------
        */
        Route::prefix('blog')
            ->name('blog.')
            ->group(function () {
                /*
                |--------------------------------------------------------------------------
                | Blog Categories
                |--------------------------------------------------------------------------
                */
                Route::resource('categories', \App\Http\Controllers\Admin\Blog\BlogCategoryController::class);
                Route::post('categories/bulk-delete', [\App\Http\Controllers\Admin\Blog\BlogCategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Blog Tags
                |--------------------------------------------------------------------------
                */
                Route::resource('tags', \App\Http\Controllers\Admin\Blog\BlogTagController::class);
                Route::post('tags/bulk-delete', [\App\Http\Controllers\Admin\Blog\BlogTagController::class, 'bulkDelete'])->name('tags.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Blog Posts
                |--------------------------------------------------------------------------
                */
                Route::resource('posts', \App\Http\Controllers\Admin\Blog\BlogPostController::class);
                Route::post('posts/bulk-delete', [\App\Http\Controllers\Admin\Blog\BlogPostController::class, 'bulkDelete'])->name('posts.bulk-delete');
                Route::post('upload-image', [\App\Http\Controllers\Admin\Blog\BlogPostController::class, 'uploadImage'])->name('upload-image');

                /*
                |--------------------------------------------------------------------------
                | Blog Comments
                |--------------------------------------------------------------------------
                */
                Route::get('comments', [\App\Http\Controllers\Admin\Blog\BlogCommentController::class, 'index'])->name('comments.index');
                Route::post('comments/{id}/status', [\App\Http\Controllers\Admin\Blog\BlogCommentController::class, 'updateStatus'])->name('comments.status');
                Route::delete('comments/{id}', [\App\Http\Controllers\Admin\Blog\BlogCommentController::class, 'destroy'])->name('comments.destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Insurance Management
        |--------------------------------------------------------------------------
        */
        Route::prefix('insurance')
            ->name('insurance.')
            ->group(function () {
                /*
                |--------------------------------------------------------------------------
                | Insurance Categories
                |--------------------------------------------------------------------------
                */
                Route::resource('categories', \App\Http\Controllers\Admin\Insurance\InsuranceCategoryController::class);
                Route::post('categories/bulk-delete', [\App\Http\Controllers\Admin\Insurance\InsuranceCategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Exams
                |--------------------------------------------------------------------------
                */
                Route::resource('exams', \App\Http\Controllers\Admin\Insurance\ExamController::class);
                Route::post('exams/bulk-delete', [\App\Http\Controllers\Admin\Insurance\ExamController::class, 'bulkDelete'])->name('exams.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Chapters
                |--------------------------------------------------------------------------
                */
                Route::resource('chapters', \App\Http\Controllers\Admin\Insurance\ChapterController::class);
                Route::post('chapters/bulk-delete', [\App\Http\Controllers\Admin\Insurance\ChapterController::class, 'bulkDelete'])->name('chapters.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Concepts
                |--------------------------------------------------------------------------
                */
                Route::resource('concepts', \App\Http\Controllers\Admin\Insurance\ConceptController::class);
                Route::post('concepts/bulk-delete', [\App\Http\Controllers\Admin\Insurance\ConceptController::class, 'bulkDelete'])->name('concepts.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | One Liners
                |--------------------------------------------------------------------------
                */
                Route::resource('one_liners', \App\Http\Controllers\Admin\Insurance\OneLinerController::class);
                Route::post('one_liners/bulk-delete', [\App\Http\Controllers\Admin\Insurance\OneLinerController::class, 'bulkDelete'])->name('one_liners.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Short & Simple
                |--------------------------------------------------------------------------
                */
                Route::resource('short_simples', \App\Http\Controllers\Admin\Insurance\ShortSimpleController::class);
                Route::post('short_simples/bulk-delete', [\App\Http\Controllers\Admin\Insurance\ShortSimpleController::class, 'bulkDelete'])->name('short_simples.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Terminologies
                |--------------------------------------------------------------------------
                */
                Route::resource('terminologies', \App\Http\Controllers\Admin\Insurance\TerminologyController::class);
                Route::post('terminologies/bulk-delete', [\App\Http\Controllers\Admin\Insurance\TerminologyController::class, 'bulkDelete'])->name('terminologies.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Materials
                |--------------------------------------------------------------------------
                */
                Route::resource('materials', \App\Http\Controllers\Admin\Insurance\MaterialController::class);
                Route::post('materials/bulk-delete', [\App\Http\Controllers\Admin\Insurance\MaterialController::class, 'bulkDelete'])->name('materials.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Tests
                |--------------------------------------------------------------------------
                */
                Route::resource('tests', \App\Http\Controllers\Admin\Insurance\TestController::class);
                Route::post('tests/bulk-delete', [\App\Http\Controllers\Admin\Insurance\TestController::class, 'bulkDelete'])->name('tests.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Questions
                |--------------------------------------------------------------------------
                */
                Route::resource('questions', \App\Http\Controllers\Admin\Insurance\QuestionController::class);
                Route::post('questions/bulk-delete', [\App\Http\Controllers\Admin\Insurance\QuestionController::class, 'bulkDelete'])->name('questions.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Test Attempts
                |--------------------------------------------------------------------------
                */
                Route::resource('test_attempts', \App\Http\Controllers\Admin\Insurance\TestAttemptController::class);
                Route::post('test_attempts/bulk-delete', [\App\Http\Controllers\Admin\Insurance\TestAttemptController::class, 'bulkDelete'])->name('test_attempts.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | Performance Stats
                |--------------------------------------------------------------------------
                */
                Route::resource('performance_stats', \App\Http\Controllers\Admin\Insurance\PerformanceStatController::class);
                Route::post('performance_stats/bulk-delete', [\App\Http\Controllers\Admin\Insurance\PerformanceStatController::class, 'bulkDelete'])->name('performance_stats.bulk-delete');

                /*
                |--------------------------------------------------------------------------
                | User Saved Items
                |--------------------------------------------------------------------------
                */
                Route::resource('user_saved_items', \App\Http\Controllers\Admin\Insurance\UserSavedItemController::class);
                Route::post('user_saved_items/bulk-delete', [\App\Http\Controllers\Admin\Insurance\UserSavedItemController::class, 'bulkDelete'])->name('user_saved_items.bulk-delete');

            });

        /*
        |--------------------------------------------------------------------------
        | Admin Users Management
        |--------------------------------------------------------------------------
        */
        Route::resource('admins', \App\Http\Controllers\Admin\AdminCreationController::class);
        Route::post('admins/bulk-delete', [\App\Http\Controllers\Admin\AdminCreationController::class, 'bulkDelete'])->name('admins.bulk-delete');
        Route::get('admins-deletion-requests', [\App\Http\Controllers\Admin\AdminCreationController::class, 'deletionRequests'])->name('admins.deletion-requests');
        Route::delete('admins/{id}/approve-deletion', [\App\Http\Controllers\Admin\AdminCreationController::class, 'approveDeletion'])->name('admins.approve-deletion');
        Route::patch('admins/{id}/reject-deletion', [\App\Http\Controllers\Admin\AdminCreationController::class, 'rejectDeletion'])->name('admins.reject-deletion');

        // Admin Additional Details (manage other admin's detailed profile)
        Route::get('admins/{id}/details', [\App\Http\Controllers\Admin\AdminAdditionalDetailsController::class, 'show'])->name('admins.details');
        Route::put('admins/{id}/details', [\App\Http\Controllers\Admin\AdminAdditionalDetailsController::class, 'updateDetails'])->name('admins.update-details');
        Route::post('admins/{id}/education', [\App\Http\Controllers\Admin\AdminAdditionalDetailsController::class, 'addEducation'])->name('admins.add-education');
        Route::put('admins/{id}/education/{educationId}', [\App\Http\Controllers\Admin\AdminAdditionalDetailsController::class, 'updateEducation'])->name('admins.update-education');
        Route::delete('admins/{id}/education/{educationId}', [\App\Http\Controllers\Admin\AdminAdditionalDetailsController::class, 'deleteEducation'])->name('admins.delete-education');
        Route::post('admins/{id}/skills', [\App\Http\Controllers\Admin\AdminAdditionalDetailsController::class, 'addSkill'])->name('admins.add-skill');
        Route::put('admins/{id}/skills/{skillId}', [\App\Http\Controllers\Admin\AdminAdditionalDetailsController::class, 'updateSkill'])->name('admins.update-skill');
        Route::delete('admins/{id}/skills/{skillId}', [\App\Http\Controllers\Admin\AdminAdditionalDetailsController::class, 'deleteSkill'])->name('admins.delete-skill');

        /*
        |--------------------------------------------------------------------------
        | Users Management
        |--------------------------------------------------------------------------
        */
        Route::resource('users', \App\Http\Controllers\Admin\UserCreationController::class);
        Route::post('users/bulk-delete', [\App\Http\Controllers\Admin\UserCreationController::class, 'bulkDelete'])->name('users.bulk-delete');
        Route::get('users-deletion-requests', [\App\Http\Controllers\Admin\UserCreationController::class, 'deletionRequests'])->name('users.deletion-requests');
        Route::delete('users/{id}/approve-deletion', [\App\Http\Controllers\Admin\UserCreationController::class, 'approveDeletion'])->name('users.approve-deletion');
        Route::patch('users/{id}/reject-deletion', [\App\Http\Controllers\Admin\UserCreationController::class, 'rejectDeletion'])->name('users.reject-deletion');

        // User Additional Details (manage user's detailed profile)
        Route::get('users/{id}/details', [\App\Http\Controllers\Admin\UserAdditionalDetailsController::class, 'show'])->name('users.details');
        Route::put('users/{id}/details', [\App\Http\Controllers\Admin\UserAdditionalDetailsController::class, 'updateDetails'])->name('users.update-details');
        Route::post('users/{id}/education', [\App\Http\Controllers\Admin\UserAdditionalDetailsController::class, 'addEducation'])->name('users.add-education');
        Route::put('users/{id}/education/{educationId}', [\App\Http\Controllers\Admin\UserAdditionalDetailsController::class, 'updateEducation'])->name('users.update-education');
        Route::delete('users/{id}/education/{educationId}', [\App\Http\Controllers\Admin\UserAdditionalDetailsController::class, 'deleteEducation'])->name('users.delete-education');
        Route::post('users/{id}/skills', [\App\Http\Controllers\Admin\UserAdditionalDetailsController::class, 'addSkill'])->name('users.add-skill');
        Route::put('users/{id}/skills/{skillId}', [\App\Http\Controllers\Admin\UserAdditionalDetailsController::class, 'updateSkill'])->name('users.update-skill');
        Route::delete('users/{id}/skills/{skillId}', [\App\Http\Controllers\Admin\UserAdditionalDetailsController::class, 'deleteSkill'])->name('users.delete-skill');



        /*
        |--------------------------------------------------------------------------
        | Notifications
        |--------------------------------------------------------------------------
        */
        Route::prefix('notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'getAll'])->name('index');
                Route::get('/unread', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnread'])->name('unread');
                Route::put('/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('mark-as-read');
                Route::put('/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'delete'])->name('delete');
                Route::delete('/clear-all', [\App\Http\Controllers\Admin\NotificationController::class, 'deleteAll'])->name('delete-all');
            });



        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
