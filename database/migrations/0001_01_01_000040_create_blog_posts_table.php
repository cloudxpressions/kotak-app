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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('image_url')->nullable();
            $table->text('image_description')->nullable();
            $table->boolean('is_visible')->default(1);
            $table->boolean('is_slider')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_breaking')->default(0);
            $table->boolean('is_recommended')->default(0);
            $table->boolean('registered_only')->default(0);
            $table->boolean('is_paid_only')->default(0);
            $table->enum('publish_status', ['draft', 'scheduled', 'published'])->default('draft');
            $table->dateTime('publish_date')->nullable();
            $table->boolean('show_author')->default(1);
            $table->decimal('average_rating', 3, 1)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->boolean('allow_print_pdf')->default(0);
            $table->timestamps();
        });

        Schema::create('blog_post_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('summary');
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_post_translations');
        Schema::dropIfExists('blog_posts');
    }
};
