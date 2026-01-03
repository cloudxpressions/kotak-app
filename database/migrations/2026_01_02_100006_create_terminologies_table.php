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
        Schema::create('terminologies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->string('category', 50)->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('terminology_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terminology_id')->constrained()->onDelete('cascade');
            $table->string('language_code', 10);
            $table->string('term', 255);
            $table->text('definition');
            $table->timestamps();

            // Make unique per language
            $table->unique(['terminology_id', 'language_code'], 'term_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminology_translations');
        Schema::dropIfExists('terminologies');
    }
};