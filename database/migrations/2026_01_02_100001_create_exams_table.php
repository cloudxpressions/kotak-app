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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('exam_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->string('language_code', 10);
            $table->string('name', 255);
            $table->text('description');
            $table->timestamps();

            // Make unique per language
            $table->unique(['exam_id', 'language_code'], 'exam_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_translations');
        Schema::dropIfExists('exams');
    }
};