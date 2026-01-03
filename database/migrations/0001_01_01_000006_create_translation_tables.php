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
        Schema::create('translation_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // auth.login
            $table->string('module')->nullable(); // auth, blog, menu
            $table->string('file')->nullable(); // auth.php
            $table->enum('type', ['extracted', 'manual', 'system'])->default('extracted');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('translation_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('translation_key_id')
                ->constrained('translation_keys')
                ->onDelete('cascade');

            $table->foreignId('language_id')
                ->constrained('languages')
                ->onDelete('cascade');

            $table->text('value')->nullable();
            $table->boolean('is_auto_translated')->default(false);

            $table->foreignId('last_updated_by')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['translation_key_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_values');
        Schema::dropIfExists('translation_keys');
    }
};
