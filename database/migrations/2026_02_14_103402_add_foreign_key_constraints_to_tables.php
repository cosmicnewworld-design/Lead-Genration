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
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('pipeline_stage_id')->references('id')->on('pipeline_stages')->onDelete('set null');
        });

        Schema::table('pipeline_stages', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table('lead_tag', function (Blueprint $table) {
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });

        Schema::table('scoring_rules', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table('campaign_steps', function (Blueprint $table) {
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });

        Schema::table('automation_rules', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
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
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['pipeline_stage_id']);
        });

        Schema::table('pipeline_stages', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });

        Schema::table('lead_tag', function (Blueprint $table) {
            $table->dropForeign(['lead_id']);
            $table->dropForeign(['tag_id']);
        });

        Schema::table('scoring_rules', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });

        Schema::table('campaign_steps', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
        });

        Schema::table('automation_rules', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });
    }
};
