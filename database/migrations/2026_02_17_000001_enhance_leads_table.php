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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('company')->nullable()->after('phone');
            $table->string('job_title')->nullable()->after('company');
            $table->string('website')->nullable()->after('job_title');
            $table->text('notes')->nullable()->after('website');
            $table->integer('score')->default(0)->after('status');
            $table->foreignId('pipeline_stage_id')->nullable()->after('score');
            $table->foreignId('assigned_to_user_id')->nullable()->after('pipeline_stage_id');
            $table->json('custom_fields')->nullable()->after('assigned_to_user_id');
            $table->json('enrichment_data')->nullable()->after('custom_fields');
            $table->timestamp('last_contacted_at')->nullable()->after('enrichment_data');
            $table->string('source')->nullable()->after('last_contacted_at');
            $table->boolean('is_qualified')->default(false)->after('source');
            $table->dropUnique(['email']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'score']);
            $table->index(['assigned_to_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'company',
                'job_title',
                'website',
                'notes',
                'score',
                'pipeline_stage_id',
                'assigned_to_user_id',
                'custom_fields',
                'enrichment_data',
                'last_contacted_at',
                'source',
                'is_qualified'
            ]);
            $table->unique('email');
        });
    }
};
