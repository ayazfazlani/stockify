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
            // Core Inventory
            'view items',
            'create items',
            'edit items',
            'delete items',
            'manage stock',
            
            // Sensitive Data
            'view financial metrics',
            'view item cost',
            'view item margin',
            'manage pricing',
            
            // Reports & Analytics
            'view analytics',
            'export reports',
            'view transactions',
            'create transactions',
            'view expenses',
            'view purchase orders',
            
            // Management
            'invite users',
            'manage team members',
            'manage team settings',
            'manage roles',
            'manage custom roles', // Specific to higher plans
        ];

        foreach ($teamPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create default team roles with permissions
        $roles = [
            'team admin' => [
                'view items',
                'create items',
                'edit items',
                'delete items',
                'manage stock',
                'view financial metrics',
                'view item cost',
                'view item margin',
                'manage pricing',
                'view analytics',
                'export reports',
                'invite users',
                'manage team members',
                'manage team settings',
                'manage roles',
                'manage custom roles',
                'view transactions',
                'create transactions',
                'view expenses',
                'view purchase orders',
            ],
            'team manager' => [
                'view items',
                'create items',
                'edit items',
                'manage stock',
                'view financial metrics',
                'view analytics',
                'view transactions',
                'create transactions',
                'view expenses',
                'view purchase orders',
            ],
            'team member' => [
                'view items',
                'create items',
                'manage stock',
                'view transactions',
                'create transactions',
            ],
            'viewer' => [
                'view items',
                'view transactions',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        // Create super admin role with all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());
    }
}