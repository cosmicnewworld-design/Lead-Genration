<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->index('tenant_id');
            $table->index('pipeline_stage_id');
        });

        Schema::table('pipeline_stages', function (Blueprint $table) {
            $table->index('tenant_id');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->index('tenant_id');
        });

        Schema::table('lead_tag', function (Blueprint $table) {
            $table->index('lead_id');
            $table->index('tag_id');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('tenant_id');
            $table->index('lead_id');
            $table->index(['loggable_type', 'loggable_id']);
        });

        Schema::table('scoring_rules', function (Blueprint $table) {
            $table->index('tenant_id');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->index('tenant_id');
        });

        Schema::table('campaign_steps', function (Blueprint $table) {
            $table->index('campaign_id');
        });

        Schema::table('automation_rules', function (Blueprint $table) {
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
            $table->dropIndex(['pipeline_stage_id']);
        });

        Schema::table('pipeline_stages', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('lead_tag', function (Blueprint $table) {
            $table->dropIndex(['lead_id']);
            $table->dropIndex(['tag_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
            $table->dropIndex(['lead_id']);
            $table->dropIndex(['loggable_type', 'loggable_id']);
        });

        Schema::table('scoring_rules', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('campaign_steps', function (Blueprint $table) {
            $table->dropIndex(['campaign_id']);
        });

        Schema::table('automation_rules', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
        });
    }
};
