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
        Schema::create('short_simples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->onDelete('cascade');
            $table->integer('order_no')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('short_simple_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_simple_id')->constrained()->onDelete('cascade');
            $table->string('language_code', 10);
            $table->string('title', 255);
            $table->text('content');
            $table->timestamps();

            // Make unique per language
            $table->unique(['short_simple_id', 'language_code'], 'shorts_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_simple_translations');
        Schema::dropIfExists('short_simples');
    }
};