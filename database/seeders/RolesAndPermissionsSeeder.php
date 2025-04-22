<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();




          // Define permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'assign users to projects',
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            'update task status',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Give all permissions to Admin
        $adminRole->syncPermissions(Permission::all());

        // Give limited permissions to User
        $userRole->syncPermissions([
            'view users',
            'view projects',
            'view tasks',
            'update task status',
        ]);
    }
}
