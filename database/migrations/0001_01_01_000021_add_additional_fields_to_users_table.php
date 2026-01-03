<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PERSONAL DETAILS
            |--------------------------------------------------------------------------
            */
            $table->string('mobile', 12)->nullable();
            $table->string('whatsapp_number', 12)->nullable();
            $table->string('image')->nullable();
            $table->date('dob')->nullable();
            $table->text('bio')->nullable();
            $table->text('short_bio')->nullable();
            $table->string('gender', 20)->nullable();
            $table->boolean('is_differently_abled')->default(0);

            /*
            |--------------------------------------------------------------------------
            | ADDRESS DETAILS
            |--------------------------------------------------------------------------
            */
            $table->string('locality')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('aadhaar_number')->nullable();
            $table->string('document')->nullable();

            $table->foreignId('country_id')->nullable()
                ->constrained('countries')
                ->nullOnDelete();

            $table->foreignId('state_id')->nullable()
                ->constrained('states')
                ->nullOnDelete();

            $table->foreignId('city_id')->nullable()
                ->constrained('cities')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FAMILY DETAILS
            |--------------------------------------------------------------------------
            */
            $table->string('fathers_name', 100)->nullable();
            $table->string('mothers_name', 100)->nullable();
            $table->string('parent_mobile_number', 12)->nullable();

            /*
            |--------------------------------------------------------------------------
            | USER PREFERENCES
            |--------------------------------------------------------------------------
            */
            $table->foreignId('language_id')->nullable()
                ->constrained('languages')
                ->nullOnDelete();

            $table->foreignId('dateformat_id')->nullable()
                ->constrained('date_formats')
                ->nullOnDelete();

            $table->foreignId('timezone_id')->nullable()
                ->constrained('time_zones')
                ->nullOnDelete();

            $table->foreignId('currency_id')->nullable()
                ->constrained('currencies')
                ->nullOnDelete();

            $table->string('medium_of_exam')->nullable();
            $table->json('favorite_topics')->nullable();

            /*
            |--------------------------------------------------------------------------
            | CLASSIFICATIONS & SOCIAL GROUPS
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_classifications_id')->nullable()
                ->constrained('user_classifications')
                ->nullOnDelete();

            $table->foreignId('community_id')->nullable()
                ->constrained('communities')
                ->nullOnDelete();

            $table->foreignId('d_a_category_id')->nullable()
                ->constrained('d_a_categories')
                ->nullOnDelete();

            $table->foreignId('religion_id')->nullable()
                ->constrained('religions')
                ->nullOnDelete();

            $table->foreignId('special_category_id')->nullable()
                ->constrained('special_categories')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | SOCIAL LINKS
            |--------------------------------------------------------------------------
            */
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SECURITY & LOGIN
            |--------------------------------------------------------------------------
            */
            $table->integer('login_attempts')->default(0);
            $table->dateTime('account_locked_until')->nullable();
            $table->string('account_locked_reason')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('last_profile_update_at')->nullable();
            $table->timestamp('last_password_change_at')->nullable();
            $table->boolean('dark_mode_enabled')->default(0);

            /*
            |--------------------------------------------------------------------------
            | REFERRAL & PAYOUT
            |--------------------------------------------------------------------------
            */
            $table->string('referral')->nullable();
            $table->boolean('subscribe')->default(0);
            $table->decimal('payout', 15, 2)->nullable();
            $table->string('payout_icon')->nullable();
            $table->string('payout_email')->nullable();
            $table->string('special_commission')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SOCIAL LOGIN
            |--------------------------------------------------------------------------
            */
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS FLAGS
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')->default(1);
            $table->boolean('is_banned')->default(0);

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT DELETION REQUEST
            |--------------------------------------------------------------------------
            */
            $table->timestamp('delete_request_at')->nullable();
            $table->string('delete_request_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Drop FKs
            $table->dropConstrainedForeignId('country_id');
            $table->dropConstrainedForeignId('state_id');
            $table->dropConstrainedForeignId('city_id');
            $table->dropConstrainedForeignId('language_id');
            $table->dropConstrainedForeignId('dateformat_id');
            $table->dropConstrainedForeignId('timezone_id');
            $table->dropConstrainedForeignId('currency_id');
            $table->dropConstrainedForeignId('user_classifications_id');
            $table->dropConstrainedForeignId('community_id');
            $table->dropConstrainedForeignId('d_a_category_id');
            $table->dropConstrainedForeignId('religion_id');
            $table->dropConstrainedForeignId('special_category_id');

            // Drop columns
            $table->dropColumn([
                'mobile','whatsapp_number','image','dob','bio','short_bio','gender','is_differently_abled',
                'locality','address','pincode','aadhaar_number','document',
                'fathers_name','mothers_name','parent_mobile_number',
                'medium_of_exam','favorite_topics',
                'facebook','twitter','linkedin',
                'login_attempts','account_locked_until','account_locked_reason',
                'mobile_verified_at','last_profile_update_at','last_password_change_at','dark_mode_enabled',
                'referral','subscribe','payout','payout_icon','payout_email','special_commission',
                'provider','provider_id',
                'is_active','is_banned',
                'delete_request_at', 'delete_request_reason'
            ]);
        });
    }
};
