<?php

namespace App\Services;

class PermissionService
{
    /**
     * Get all available permissions in the system grouped by category.
     */
    public static function getPermissions(): array
    {
        return [
            'Inventory' => [
                'view items' => 'View product list and details',
                'create items' => 'Add new products to catalog',
                'edit items' => 'Modify existing product details',
                'delete items' => 'Remove products from catalog',
                'view item cost' => 'View purchase cost of items',
                'view item margin' => 'View profit margins',
            ],
            'Stock Management' => [
                'view stock' => 'View current stock levels',
                'manage stock' => 'Perform stock in, stock out, and adjustments',
                'view transactions' => 'View stock movement history',
            ],
            'Expenses' => [
                'view expenses' => 'View business expenses',
                'manage expenses' => 'Record and manage business expenses',
            ],
            'Purchase Orders' => [
                'view purchase orders' => 'View sent purchase orders',
                'manage purchase orders' => 'Create and send purchase orders to suppliers',
            ],
            'Analytics' => [
                'view analytics' => 'Access dashboard analytics and reports',
                'view financial metrics' => 'View revenue and profit analytics',
                'view summary' => 'Access summary reports',
            ],
            'Team' => [
                'manage team members' => 'Invite users and assign roles',
                'manage roles' => 'Create, edit, and delete custom roles',
            ],
            'Settings' => [
                'manage admin settings' => 'Access store and tenant settings',
                'manage marketplace' => 'Configure marketplace and store profile',
                'manage stores' => 'Create and manage multiple stores',
            ],
        ];
    }

    /**
     * Get a flat list of permission names.
     */
    public static function getAllPermissionNames(): array
    {
        $names = [];
        foreach (self::getPermissions() as $category => $permissions) {
            foreach ($permissions as $name => $description) {
                $names[] = $name;
            }
        }

        return $names;
    }
}
