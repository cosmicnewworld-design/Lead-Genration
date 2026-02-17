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
        Schema::table('campaign_steps', function (Blueprint $table) {
            $table->string('type')->default('email')->after('campaign_id');
            $table->integer('delay_in_hours')->nullable()->after('delay_in_days');
            $table->integer('delay_in_minutes')->nullable()->after('delay_in_hours');
            $table->json('settings')->nullable()->after('order');
            $table->string('ab_test_variant')->nullable()->after('settings');
            $table->boolean('is_active')->default(true)->after('ab_test_variant');
            
            $table->index(['campaign_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_steps', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'delay_in_hours',
                'delay_in_minutes',
                'settings',
                'ab_test_variant',
                'is_active',
            ]);
        });
    }
};
