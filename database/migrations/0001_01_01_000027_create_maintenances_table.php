<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->boolean('maintenance_mode')->default(false)->comment('If true, application is in maintenance mode');
            $table->string('title')->nullable()->comment('Heading for the maintenance page');
            $table->text('subtitle')->nullable()->comment('Detailed message / instructions for users');
            $table->string('maintenance_page_banner')->nullable()->comment('Optional image/banner path for maintenance screen');
            $table->timestamp('starts_at')->nullable()->comment('Planned start time for maintenance');
            $table->timestamp('ends_at')->nullable()->comment('Planned end time for maintenance');
            $table->json('allowed_ips')->nullable()->comment('IP addresses allowed to bypass maintenance');
            $table->boolean('is_emergency')->default(false)->comment('True for unplanned/emergency downtime');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
