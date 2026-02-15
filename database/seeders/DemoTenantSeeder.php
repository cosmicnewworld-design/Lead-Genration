<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Team;
use App\Models\Business;
use App\Models\User;

class DemoTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenant::create([
            'name' => 'Demo Tenant',
            'slug' => 'demo-tenant'
        ]);

        $user = User::factory()->create([
            'email' => 'admin@test.com'
        ]);

        $team = Team::create([
            'name' => 'Demo Team',
            'user_id' => $user->id,
        ]);

        $business = Business::create([
            'name' => 'Demo Business',
            'tenant_id' => $tenant->id
        ]);

        // $tenant->makeCurrent();
    }
}
