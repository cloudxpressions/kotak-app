<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('time_zones', function (Blueprint $table) {
            $table->id();

            $table->string('name');             // Asia/Kolkata
            $table->string('timezone');         // IST
            $table->string('offset')->nullable(); // "+05:30" for display
            $table->integer('utc_offset_minutes')->default(0); // 330

            $table->boolean('is_active')->default(1);

            $table->timestamps();

            $table->unique('name');
            $table->index('utc_offset_minutes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_zones');
    }
};