<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoringRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant to associate the rules with.
        // In a real application, you might want to handle this differently.
        $tenant = DB::table('tenants')->first();

        if ($tenant) {
            DB::table('scoring_rules')->insert([
                [
                    'tenant_id' => $tenant->id,
                    'field' => 'industry',
                    'operator' => 'equals',
                    'value' => 'Technology',
                    'points' => 20,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tenant_id' => $tenant->id,
                    'field' => 'industry',
                    'operator' => 'equals',
                    'value' => 'Finance',
                    'points' => 15,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tenant_id' => $tenant->id,
                    'field' => 'source',
                    'operator' => 'equals',
                    'value' => 'Website',
                    'points' => 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tenant_id' => $tenant->id,
                    'field' => 'source',
                    'operator' => 'equals',
                    'value' => 'Referral',
                    'points' => 15,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tenant_id' => $tenant->id,
                    'field' => 'email',
                    'operator' => 'contains',
                    'value' => 'gmail.com',
                    'points' => -5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'tenant_id' => $tenant->id,
                    'field' => 'company_name',
                    'operator' => 'equals',
                    'value' => 'Acme Corporation',
                    'points' => 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        } else {
            $this->command->info('No tenants found, skipping scoring rule seeding.');
        }
    }
}
