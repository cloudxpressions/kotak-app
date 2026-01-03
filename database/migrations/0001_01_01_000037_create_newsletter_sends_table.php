<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_sends', function (Blueprint $table) {
            $table->id();

            $table->foreignId('newsletter_id')
                  ->constrained('newsletters')
                  ->cascadeOnDelete();

            $table->foreignId('subscriber_id')
                  ->constrained('newsletter_subscribers')
                  ->cascadeOnDelete();

            // queued / sent / failed / bounced
            $table->enum('status', ['queued', 'sent', 'failed', 'bounced'])
                  ->default('queued');

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();

            $table->string('failure_reason')->nullable();

            $table->timestamps();

            $table->unique(['newsletter_id', 'subscriber_id'], 'nl_send_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_sends');
    }
};