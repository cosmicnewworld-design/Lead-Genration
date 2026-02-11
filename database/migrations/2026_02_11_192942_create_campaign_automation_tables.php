
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
            $table->string('subject');
            $table->text('body');
            $table->unsignedInteger('delay_in_days')->default(0);
            $table->unsignedInteger('order');
            $table->timestamps();
        });

        Schema::create('campaign_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at');
            $table->string('status')->default('running'); // e.g., running, completed
            $table->timestamps();
        });

        Schema::create('campaign_run_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_run_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('current_step_id')->nullable()->constrained('campaign_steps')->onDelete('set null');
            $table->string('status')->default('pending'); // e.g., pending, sent, failed
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_run_leads');
        Schema::dropIfExists('campaign_runs');
        Schema::dropIfExists('campaign_steps');
    }
};
