<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant1 = Tenant::create(['name' => 'Tenant 1', 'slug' => Str::slug('Tenant 1')]);
        $tenant2 = Tenant::create(['name' => 'Tenant 2', 'slug' => Str::slug('Tenant 2')]);

        User::create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant1->id,
        ]);

        User::create([
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant2->id,
        ]);
    }
}
