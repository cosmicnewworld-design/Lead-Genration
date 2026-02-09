<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Find all valid business IDs
        $businessIds = DB::table('businesses')->pluck('id');

        // Step 2: Delete leads that have an invalid or null business_id
        DB::table('leads')->whereNotIn('business_id', $businessIds)->delete();

        // Step 3: Now that the data is clean, add the foreign key constraint
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'business_id')) {
                $table->foreignId('business_id')->after('id')->constrained()->cascadeOnDelete();
            } else {
                 // The column exists, so we just need to add the constraint.
                 // We must ensure the column type is correct. We'll assume it is for now.
                $table->foreign('business_id')->references('id')->on('businesses')->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // The column might not exist if the up() migration failed midway
            if (Schema::hasColumn('leads', 'business_id')) {
                // Drop the foreign key constraint first
                $table->dropForeign(['business_id']);
            }
        });
    }
};
