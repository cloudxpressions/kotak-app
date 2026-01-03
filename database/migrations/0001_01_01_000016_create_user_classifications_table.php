<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_classifications', function (Blueprint $table) {
            $table->id();

            $table->string('name');                    // Student, Working Professional, etc.
            $table->string('type')->nullable();        // category group
            $table->string('icon')->nullable();
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_classifications');
    }
};