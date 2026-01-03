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
        Schema::create('one_liners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->onDelete('cascade');
            $table->integer('order_no')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('one_liner_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('one_liner_id')->constrained()->onDelete('cascade');
            $table->string('language_code', 10);
            $table->text('content');
            $table->timestamps();

            // Make unique per language
            $table->unique(['one_liner_id', 'language_code'], 'onel_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('one_liner_translations');
        Schema::dropIfExists('one_liners');
    }
};