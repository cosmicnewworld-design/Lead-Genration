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
        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('trigger_type'); // e.g., 'lead_scored', 'tag_added'
            $table->json('trigger_config'); // e.g., {'score_threshold': 100}
            $table->string('action_type'); // e.g., 'move_to_stage', 'add_to_campaign'
            $table->json('action_config'); // e.g., {'pipeline_stage_id': 5}
            $table->timestamps();

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
        Schema::dropIfExists('automation_rules');
    }
};
