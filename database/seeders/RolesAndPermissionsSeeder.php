<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'manage leads']);
        Permission::create(['name' => 'view leads']);
        Permission::create(['name' => 'manage campaigns']);
        Permission::create(['name' => 'view campaigns']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view users']);


        // create roles and assign created permissions

        $role = Role::create(['name' => 'Sales Agent']);
        $role->givePermissionTo(['view leads', 'view campaigns']);

        $role = Role::create(['name' => 'Sales Manager']);
        $role->givePermissionTo(['manage leads', 'view leads', 'manage campaigns', 'view campaigns', 'view users']);

        $role = Role::create(['name' => 'Tenant Admin']);
        $role->givePermissionTo(Permission::all());
    }
}
