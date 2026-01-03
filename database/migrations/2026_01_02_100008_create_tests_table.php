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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('chapter_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['mock', 'practice', 'live', 'chapter']);
            $table->integer('total_questions');
            $table->integer('duration_minutes');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('test_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->string('language_code', 10);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->timestamps();

            // Make unique per language
            $table->unique(['test_id', 'language_code'], 'test_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_translations');
        Schema::dropIfExists('tests');
    }
};