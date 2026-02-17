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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('stripe_plan_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('billing_period')->default('month'); // month, year
            $table->text('description')->nullable();
            $table->integer('max_leads')->default(0); // 0 = unlimited
            $table->integer('max_campaigns')->default(0);
            $table->integer('max_team_members')->default(1);
            $table->integer('max_emails_per_month')->default(0);
            $table->boolean('email_warmup')->default(false);
            $table->boolean('lead_enrichment')->default(false);
            $table->boolean('api_access')->default(false);
            $table->boolean('webhook_access')->default(false);
            $table->boolean('advanced_analytics')->default(false);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
