<?php

return [
    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'price' => 9.99,
            'features' => [
                'team_members' => 5,
                'storage' => 5120, // 5GB in MB
                'analytics' => false,
                'api_access' => false,
            ],
            'stripe_price_id' => env('STRIPE_STARTER_PRICE_ID'),
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
            'stripe_price_id' => env('STRIPE_PROFESSIONAL_PRICE_ID'),
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
            'stripe_price_id' => env('STRIPE_ENTERPRISE_PRICE_ID'),
        ],
    ],

    'trial_days' => 14,
    
    'features' => [
        'analytics' => [
            'name' => 'Analytics',
            'description' => 'Access to detailed analytics and reporting',
        ],
        'api_access' => [
            'name' => 'API Access',
            'description' => 'Access to our REST API',
        ],
    ],
];