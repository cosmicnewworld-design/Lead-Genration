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
            $table->renameColumn('phone_number', 'phone');
            $table->renameColumn('linkedin_url', 'linkedin_profile');
            $table->string('designation')->nullable()->after('name');
            $table->string('company_name')->nullable()->after('designation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('phone', 'phone_number');
            $table->renameColumn('linkedin_profile', 'linkedin_url');
            $table->dropColumn(['designation', 'company_name']);
        });
    }
};
