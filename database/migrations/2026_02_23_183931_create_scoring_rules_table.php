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
        Schema::create('scoring_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('condition_field'); // e.g., 'source', 'status', 'email_opened'
            $table->string('condition_value'); // e.g., 'Website Form', 'verified', 'true'
            $table->integer('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scoring_rules');
    }
};
