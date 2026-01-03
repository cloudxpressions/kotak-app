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
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('blog_tag_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_tag_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('slug');
            $table->string('name');
            $table->timestamps();

            // Ensure slug is unique per language
            $table->unique(['slug', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_tag_translations');
        Schema::dropIfExists('blog_tags');
    }
};