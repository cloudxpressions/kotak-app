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
        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->onDelete('cascade');
            $table->integer('order_no')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('concept_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concept_id')->constrained()->onDelete('cascade');
            $table->string('language_code', 10);
            $table->string('title', 255);
            $table->longText('content_html');
            $table->timestamps();

            // Make unique per language
            $table->unique(['concept_id', 'language_code'], 'conc_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concept_translations');
        Schema::dropIfExists('concepts');
    }
};