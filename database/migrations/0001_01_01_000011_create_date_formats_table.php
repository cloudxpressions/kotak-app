<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('date_formats', function (Blueprint $table) {
            $table->id();

            $table->string('format')->nullable();
            // Human readable example
            $table->string('normal_view')->nullable();    // 17th May, 2019
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('date_formats');
    }
};