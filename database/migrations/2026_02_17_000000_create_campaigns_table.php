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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('type')->nullable();
            $table->json('schedule_settings')->nullable();
            $table->boolean('ab_test_enabled')->default(false);
            $table->foreignId('sender_email_id')->nullable()->constrained('connected_emails')->onDelete('set null');
            $table->string('reply_to_email')->nullable();
            $table->boolean('track_opens')->default(true);
            $table->boolean('track_clicks')->default(true);
            $table->boolean('auto_follow_up')->default(false);
            $table->boolean('stop_on_reply')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
