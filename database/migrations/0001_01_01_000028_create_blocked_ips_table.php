<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique()->comment('IPv4 or IPv6 string of blocked IP');
            $table->text('reason')->nullable()->comment('Reason for blocking this IP');
            $table->timestamp('blocked_until')->nullable()->comment('If set, IP is blocked until this time');
            $table->boolean('is_permanent')->default(false)->comment('True if block is permanent');
            $table->unsignedInteger('attempts_count')->default(0)->comment('Number of suspicious attempts recorded');
            $table->timestamp('last_attempt_at')->nullable()->comment('Last time this IP was seen making a bad request');
            $table->text('user_agent')->nullable()->comment('Example user agent seen from this IP');
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete()->comment('Admin who added this block');
            $table->foreignId('updated_by')->nullable()->constrained('admins')->nullOnDelete()->comment('Admin who last updated this block');
            $table->timestamps();

            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
    }
};
