<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Enums\PlanFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Starter Plan
        $starter = Plan::updateOrCreate(
            ['slug' => 'starter-monthly'],
            [
                'name' => 'Starter Plan',
                'description' => 'Perfect for small businesses getting started with inventory management.',
                'amount' => 2900, // $29.00 in cents
                'stripe_price_id' => 'price_starter',
                'stripe_product_id' => 'prod_starter',
                'interval' => 'month',
                'currency' => 'usd',
                'active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                'trial_days' => 14,
            ]
        );

        $starter->syncFeatures([
            PlanFeature::BULK_IMPORT->value => null,
            PlanFeature::BARCODE_SCANNING->value => null,
            PlanFeature::LOW_STOCK_ALERTS->value => null,
            PlanFeature::MAX_ITEMS->value => '1000',
            PlanFeature::MAX_STORES->value => '1',
            PlanFeature::MAX_TEAM_MEMBERS->value => '2',
            PlanFeature::STORAGE_LIMIT_MB->value => '100',
        ]);

        // 2. Professional Plan
        $pro = Plan::updateOrCreate(
            ['slug' => 'pro-monthly'],
            [
                'name' => 'Professional Plan',
                'description' => 'Ideal for growing businesses that need advanced tools and multi-store management.',
                'amount' => 7900, // $79.00 in cents
                'stripe_price_id' => 'price_pro',
                'stripe_product_id' => 'prod_pro',
                'interval' => 'month',
                'currency' => 'usd',
                'active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'trial_days' => 14,
            ]
        );

        $pro->syncFeatures([
            PlanFeature::ANALYTICS->value => null,
            PlanFeature::ADVANCED_REPORTS->value => null,
            PlanFeature::BULK_IMPORT->value => null,
            PlanFeature::BULK_EXPORT->value => null,
            PlanFeature::BARCODE_SCANNING->value => null,
            PlanFeature::LOW_STOCK_ALERTS->value => null,
            PlanFeature::MULTI_STORE->value => null,
            PlanFeature::AUDIT_LOG->value => null,
            PlanFeature::MAX_ITEMS->value => '10000',
            PlanFeature::MAX_STORES->value => '5',
            PlanFeature::MAX_TEAM_MEMBERS->value => '10',
            PlanFeature::STORAGE_LIMIT_MB->value => '500',
        ]);

        // 3. Enterprise Plan
        $enterprise = Plan::updateOrCreate(
            ['slug' => 'enterprise-monthly'],
            [
                'name' => 'Enterprise Plan',
                'description' => 'Complete solution for large organizations with custom needs and dedicated support.',
                'amount' => 19900, // $199.00 in cents
                'stripe_price_id' => 'price_enterprise',
                'stripe_product_id' => 'prod_enterprise',
                'interval' => 'month',
                'currency' => 'usd',
                'active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'trial_days' => 14,
            ]
        );

        $enterprise->syncFeatures([
            PlanFeature::ANALYTICS->value => null,
            PlanFeature::ADVANCED_REPORTS->value => null,
            PlanFeature::API_ACCESS->value => null,
            PlanFeature::BULK_IMPORT->value => null,
            PlanFeature::BULK_EXPORT->value => null,
            PlanFeature::BARCODE_SCANNING->value => null,
            PlanFeature::LOW_STOCK_ALERTS->value => null,
            PlanFeature::PRIORITY_SUPPORT->value => null,
            PlanFeature::MULTI_STORE->value => null,
            PlanFeature::AUDIT_LOG->value => null,
            PlanFeature::CUSTOM_ROLES->value => null,
            PlanFeature::MAX_ITEMS->value => '-1',
            PlanFeature::MAX_STORES->value => '-1',
            PlanFeature::MAX_TEAM_MEMBERS->value => '-1',
            PlanFeature::STORAGE_LIMIT_MB->value => '2048',
        ]);
        
        $this->command->info('Plans and Features seeded successfully!');
    }
}
