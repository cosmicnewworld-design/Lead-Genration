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
        Schema::create('campaign_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->integer('delay_in_days')->nullable();
            $table->integer('delay_in_hours')->nullable();
            $table->integer('delay_in_minutes')->nullable();
            $table->integer('order');
            $table->json('settings')->nullable();
            $table->string('ab_test_variant', 1)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_steps');
    }
};
