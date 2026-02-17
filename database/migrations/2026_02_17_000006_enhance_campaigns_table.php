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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('description');
            $table->string('type')->default('email')->after('status');
            $table->json('schedule_settings')->nullable()->after('type');
            $table->boolean('ab_test_enabled')->default(false)->after('schedule_settings');
            $table->foreignId('sender_email_id')->nullable()->after('ab_test_enabled');
            $table->string('reply_to_email')->nullable()->after('sender_email_id');
            $table->boolean('track_opens')->default(true)->after('reply_to_email');
            $table->boolean('track_clicks')->default(true)->after('track_opens');
            $table->boolean('auto_follow_up')->default(false)->after('track_clicks');
            $table->boolean('stop_on_reply')->default(true)->after('auto_follow_up');
            
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'type',
                'schedule_settings',
                'ab_test_enabled',
                'sender_email_id',
                'reply_to_email',
                'track_opens',
                'track_clicks',
                'auto_follow_up',
                'stop_on_reply',
            ]);
        });
    }
};
