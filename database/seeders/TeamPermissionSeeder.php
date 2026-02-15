<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class TeamPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create team-specific permissions
        $teamPermissions = [
            'view items',
            'create items',
            'edit items',
            'delete items',
            'manage stock',
            'view analytics',
            'export reports',
            'invite users',
            'manage team members',
            'manage team settings',
            'view transactions',
            'create transactions',
        ];

        foreach ($teamPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create default team roles with permissions
        $roles = [
            'team admin' => [
                'view items',
                'create items',
                'edit items',
                'delete items',
                'manage stock',
                'view analytics',
                'export reports',
                'invite users',
                'manage team members',
                'manage team settings',
                'view transactions',
                'create transactions',
            ],
            'team manager' => [
                'view items',
                'create items',
                'edit items',
                'manage stock',
                'view analytics',
                'view transactions',
                'create transactions',
            ],
            'team member' => [
                'view items',
                'create items',
                'view transactions',
                'create transactions',
            ],
            'viewer' => [
                'view items',
                'view transactions',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        // Create super admin role with all permissions
        $superAdminRole = Role::create(['name' => 'super admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());
    }
}