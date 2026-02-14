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
            $table->unsignedBigInteger('pipeline_stage_id')->nullable()->after('tenant_id');
            $table->integer('score')->default(0)->after('email');

            $table->foreign('pipeline_stage_id')->references('id')->on('pipeline_stages')->onDelete('set null');
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
            // $table->dropForeign(['pipeline_stage_id']); // Removed for SQLite compatibility
            $table->dropColumn(['pipeline_stage_id', 'score']);
        });
    }
};
