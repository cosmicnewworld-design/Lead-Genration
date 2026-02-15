<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenant::first();

        Lead::create([
            'tenant_id' => $tenant->id,
            'name' => 'Lead 1',
            'email' => 'lead1@example.com',
            'status' => 'new',
        ]);

        Lead::create([
            'tenant_id' => $tenant->id,
            'name' => 'Lead 2',
            'email' => 'lead2@example.com',
            'status' => 'contacted',
        ]);

        Lead::create([
            'tenant_id' => $tenant->id,
            'name' => 'Lead 3',
            'email' => 'lead3@example.com',
            'status' => 'qualified',
        ]);

        Lead::create([
            'tenant_id' => $tenant->id,
            'name' => 'Lead 4',
            'email' => 'lead4@example.com',
            'status' => 'lost',
        ]);

        Lead::create([
            'tenant_id' => $tenant->id,
            'name' => 'Lead 5',
            'email' => 'lead5@example.com',
            'status' => 'won',
        ]);
    }
}
