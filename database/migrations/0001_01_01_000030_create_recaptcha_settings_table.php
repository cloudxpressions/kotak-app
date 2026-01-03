<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recaptcha_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_key')->nullable();                           // public key for frontend
            $table->string('secret_key')->nullable();                         // private key (or keep in .env)
            $table->boolean('is_enabled')->default(false);                    // master on/off switch
            $table->enum('version', ['v2_checkbox', 'v2_invisible', 'v3'])
                  ->default('v2_checkbox');                                   // type of recaptcha
            $table->decimal('v3_score_threshold', 3, 2)->default(0.5);        // only used for v3
            $table->boolean('captcha_for_login')->default(false);
            $table->boolean('captcha_for_register')->default(false);
            $table->boolean('captcha_for_contact')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recaptcha_settings');
    }
};
