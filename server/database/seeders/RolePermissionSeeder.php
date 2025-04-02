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
        // Create permissions
        Permission::create(['name' => 'read']);
        Permission::create(['name' => 'create']);
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'delete']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'register']);
    
        // Super admin
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo(['read', 'create', 'edit', 'delete', 'manage users', 'register']);

        // Admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['read', 'create', 'edit', 'delete']);
    }
}