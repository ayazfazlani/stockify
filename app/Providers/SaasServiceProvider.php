<?php

namespace App\Providers;

use App\Models\Team;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class SaasServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Set the Stripe API Version
        Cashier::useCustomerModel(Team::class);
        
        // Define subscription plans
        config(['saas.plans' => [
            'starter' => [
                'name' => 'Starter',
                'price' => 9.99,
                'features' => [
                    'team_members' => 5,
                    'storage' => 5120, // 5GB in MB
                    'analytics' => false,
                    'api_access' => false,
                ],
            ],
            'professional' => [
                'name' => 'Professional',
                'price' => 29.99,
                'features' => [
                    'team_members' => 10,
                    'storage' => 20480, // 20GB in MB
                    'analytics' => true,
                    'api_access' => false,
                ],
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 99.99,
                'features' => [
                    'team_members' => 50,
                    'storage' => 102400, // 100GB in MB
                    'analytics' => true,
                    'api_access' => true,
                ],
            ],
        ]]);
    }
}