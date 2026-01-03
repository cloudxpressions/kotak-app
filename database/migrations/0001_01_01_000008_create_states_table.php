<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();

            $table->string('name');               // Tamil Nadu
            $table->string('code', 20)->nullable();  // TN, KA etc.
            $table->string('type', 50)->nullable();  // State / Union Territory

            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->boolean('is_active')->default(1);

            $table->timestamps();

            $table->index(['country_id', 'name']);
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};