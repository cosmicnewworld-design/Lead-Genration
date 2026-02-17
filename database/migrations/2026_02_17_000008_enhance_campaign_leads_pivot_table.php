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
        Schema::table('campaign_lead', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('lead_id');
            $table->foreignId('current_step_id')->nullable()->after('status');
            $table->timestamp('processed_at')->nullable()->after('current_step_id');
            $table->timestamp('opened_at')->nullable()->after('processed_at');
            $table->timestamp('clicked_at')->nullable()->after('opened_at');
            $table->timestamp('replied_at')->nullable()->after('clicked_at');
            
            $table->index(['campaign_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_lead', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'current_step_id',
                'processed_at',
                'opened_at',
                'clicked_at',
                'replied_at',
            ]);
        });
    }
};
