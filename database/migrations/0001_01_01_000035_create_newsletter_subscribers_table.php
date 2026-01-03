<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();

            $table->string('email')->unique();
            $table->string('name')->nullable();

            // pending = double opt-in not confirmed yet
            // subscribed = active
            // unsubscribed = opted out
            // bounced = hard bounce / invalid
            $table->enum('status', ['pending', 'subscribed', 'unsubscribed', 'bounced'])
                  ->default('pending');

            // when they actually confirmed
            $table->timestamp('subscribed_at')->nullable();

            // when they unsubscribed
            $table->timestamp('unsubscribed_at')->nullable();

            // last engagement (optional but handy)
            $table->timestamp('last_open_at')->nullable();
            $table->timestamp('last_click_at')->nullable();

            // for double opt-in confirmation
            $table->string('verify_token', 64)->nullable()->index();

            // where they came from: form/admin/import/etc.
            $table->string('source')->nullable();

            // if you want to store extra JSON (UTM tags, preferences, etc.)
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};