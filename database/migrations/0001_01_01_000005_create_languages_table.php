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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // English
            $table->string('native_name')->nullable();   // English / தமிழ் / हिन्दी
            $table->string('code', 10);                  // en, ta
            $table->string('slug')->nullable();          // en, ta, etc.
            $table->string('direction', 3)->default('ltr'); // ltr / rtl
            $table->boolean('is_default')->default(0);      // default language
            $table->boolean('is_active')->default(1);       // active
            $table->timestamps();

            $table->unique('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
