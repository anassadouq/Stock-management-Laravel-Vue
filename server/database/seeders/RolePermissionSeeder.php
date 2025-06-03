<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = ['read', 'create', 'edit', 'delete', 'manage users', 'register'];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo($permissions);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(['read', 'create', 'edit', 'delete']);

        $controllerRole = Role::firstOrCreate(['name' => 'controller']);
        $controllerRole->givePermissionTo(['read']);
    }
}

// run this command when you add new role : php artisan db:seed --class="Database\Seeders\RolePermissionSeeder"