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
        Schema::create('insurance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->integer('order_no')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('insurance_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_category_id')->constrained()->onDelete('cascade');
            $table->string('language_code', 10);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->timestamps();

            // Make unique per language
            $table->unique(['insurance_category_id', 'language_code'], 'ins_cat_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_category_translations');
        Schema::dropIfExists('insurance_categories');
    }
};