<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            // Core
            $table->string('name', 100)->nullable();      // English name
            $table->string('native_name', 100)->nullable(); // Local name: भारत, 日本
            $table->string('iso3', 10)->nullable();       // IND
            $table->string('iso2', 10)->nullable();       // IN
            $table->string('phonecode', 30)->nullable();  // 91
            $table->string('currency', 30)->nullable();   // INR
            $table->string('capital', 50)->nullable();    // New Delhi

            // Enterprise additions
            $table->string('continent', 50)->nullable();  // Asia, Europe, etc.
            $table->string('emoji_flag', 10)->nullable(); // 🇮🇳

            $table->boolean('is_active')->default(1);

            $table->timestamps();

            $table->index('iso2');
            $table->index('iso3');
            $table->index('continent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
