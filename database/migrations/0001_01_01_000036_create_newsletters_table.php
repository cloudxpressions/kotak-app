<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();

            $table->string('subject');
            $table->string('slug')->unique(); // for internal reference / preview URL

            // main content
            $table->text('body_text')->nullable();
            $table->longText('body_html')->nullable();

            // draft / scheduled / sending / sent / cancelled
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'cancelled'])
                  ->default('draft');

            // when you plan to send
            $table->timestamp('scheduled_for')->nullable();

            // when it actually went out
            $table->timestamp('sent_at')->nullable();

            // basic stats (can be updated after sends)
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('total_sent')->default(0);
            $table->unsignedInteger('total_opened')->default(0);
            $table->unsignedInteger('total_clicked')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};