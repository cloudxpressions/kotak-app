<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();       // Dollars, Rupees
            $table->string('code', 5)->nullable();    // USD, INR
            $table->string('symbol')->nullable();     // $, ₹

            $table->double('conversion_rate')->default(1); // relative to base system currency

            // Enterprise formatting
            $table->enum('symbol_position', ['before', 'after'])
                  ->default('before');               // ₹500 vs 500₽
            $table->tinyInteger('decimal_places')->default(2);
            $table->boolean('is_default')->default(0);

            $table->boolean('is_active')->default(1);

            $table->timestamps();

            $table->unique('code');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};