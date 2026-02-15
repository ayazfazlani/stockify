<?php

// app/Services/Subscriptions/SubscriptionService.php

namespace App\Services\Subscriptions;  // ← Correct namespace

use App\Models\Plan;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class SubscriptionService
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create a plan in Stripe AND database automatically
     */
    public function createPlan(array $data)
    {

        // Generate slug if not provided
        $slug = $data['slug'] ?? Str::slug($data['name']);

        // Create in Stripe first
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
        ]);

        $stripePrice = $this->stripe->prices->create([
            'product' => $stripeProduct->id,
            'unit_amount' => $data['amount'] * 100, // Convert to cents
            'currency' => $data['currency'] ?? 'usd',
            'recurring' => ['interval' => $data['interval'] ?? 'month'],
        ]);

        // dd($stripePrice);
        $data = Plan::create([
            'name' => $data['name'],
            'slug' => $slug,
            'stripe_product_id' => $stripeProduct->id,
            'stripe_price_id' => $stripePrice->id,
            'amount' => $data['amount'] * 100,
            'currency' => $data['currency'] ?? 'usd',
            'interval' => $data['interval'] ?? 'month',
            'features' => $data['features'] ?? '',
            'active' => $data['active'] ?? true,
        ]);

        // Create in database with Stripe IDs
        return $data;
    }

    /**
     * Update a plan
     */
    public function updatePlan($planId, array $data)
    {
        $plan = Plan::findOrFail($planId);

        // Update in Stripe if needed
        if (isset($data['name'])) {
            $this->stripe->products->update($plan->stripe_product_id, [
                'name' => $data['name'],
            ]);
        }

        // Update in database
        $updateData = [];

        if (isset($data['name'])) {
            $updateData['name'] = $data['name'];
        }
        if (isset($data['slug'])) {
            $updateData['slug'] = $data['slug'];
        }
        if (isset($data['features'])) {
            $updateData['features'] = $data['features'];
        }
        if (isset($data['active'])) {
            $updateData['active'] = $data['active'];
        }
        if (isset($data['amount'])) {
            $updateData['amount'] = $data['amount'] * 100;
        }

        return $plan->update($updateData);
    }

    /**
     * Delete a plan
     */
    public function deletePlan($planId)
    {
        $plan = Plan::findOrFail($planId);

        // dd($plan);
        // Archive in Stripe (we don't delete to preserve history)
        $this->stripe->products->update($plan->stripe_product_id, [
            'active' => false,
        ]);

        // Delete from our database
        return $plan->delete();
    }

    /**
     * Toggle plan active status
     */
    public function toggleActive($planId)
    {
        $plan = Plan::findOrFail($planId);

        // Update in Stripe
        $this->stripe->products->update($plan->stripe_product_id, [
            'active' => ! $plan->active,
        ]);

        // Update in database
        return $plan->update(['active' => ! $plan->active]);
    }
}
