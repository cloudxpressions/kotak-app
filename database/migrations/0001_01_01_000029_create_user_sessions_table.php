<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('authenticatable_id')->nullable()
                ->comment('Polymorphic owner ID');
            $table->string('authenticatable_type')->nullable()
                ->comment('Polymorphic owner class');
            $table->string('session_token', 64)->unique()->comment('Random token identifying this session');
            $table->boolean('is_active')->default(true)->comment('True while the session is active');
            $table->string('ip_address', 45)->nullable()->comment('IP used for this session');
            $table->text('user_agent')->nullable()->comment('Raw user agent string');
            $table->string('device')->nullable()->comment('Device name/type if parsed');
            $table->string('browser')->nullable()->comment('Browser name/version if parsed');
            $table->string('platform')->nullable()->comment('OS/platform');
            $table->string('country', 2)->nullable()->comment('ISO country code if available');
            $table->string('region')->nullable()->comment('State/region if available');
            $table->string('city')->nullable()->comment('City if available');
            $table->timestamp('login_at')->comment('When the session started');
            $table->timestamp('logout_at')->nullable()->comment('When the session ended');
            $table->timestamp('last_seen_at')->nullable()->comment('Last activity ping from this session');
            $table->string('session_type', 20)->default('web')->comment('Session category: web, mobile, api, etc.');
            $table->timestamp('revoked_at')->nullable()->comment('When the session was forcefully revoked');
            $table->string('revoked_reason')->nullable()->comment('Reason for revoking');
            $table->timestamps();

            $table->index(['authenticatable_id', 'authenticatable_type']);
            $table->index(['is_active']);
            $table->index('login_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
