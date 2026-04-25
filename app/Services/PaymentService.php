<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\Payment\StripeGateway;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    // This class can be used to manage payment services in the future

    protected StripeGateway $gateway;

    public function __construct(StripeGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function subscribe(Tenant $tenant, Plan $plan, ?string $paymentMethodId = null): array
    {
        return DB::transaction(function () use ($tenant, $plan) {
            // Get or create Stripe customer
            $customerId = $tenant->stripe_id; // For Tenants, billable trait uses stripe_id
            if (!$customerId) {
                $customer = $this->gateway->createCustomer([
                    'email' => $tenant->owner->email ?? null,
                    'name' => $tenant->name,
                    'metadata' => ['tenant_id' => $tenant->id],
                ]);
                $customerId = $customer->id;
                $tenant->update(['stripe_id' => $customerId]);
            }

            // Create checkout session
            $checkoutSession = $this->gateway->createCheckoutSession([
                'customer_id' => $customerId,
                'price_id' => $plan->stripe_price_id,
                'success_url' => route('subscription.success'),
                'cancel_url' => route('subscription.cancel'),
                'mode' => 'subscription',
                'metadata' => [
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                ],
                'subscription_metadata' => [
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                ],
                'trial_days' => $plan->trial_days,
            ]);

            // Store pending subscription
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'stripe_id' => $checkoutSession->id, // Initial session ID, will be updated to subscription ID
                'stripe_price' => $plan->stripe_price_id,
                'stripe_status' => 'incomplete',
                'type' => 'default',
            ]);

            return [
                'session_id' => $checkoutSession->id,
                'url' => $checkoutSession->url,
                'customer_id' => $customerId,
            ];
        });
    }

    /**
     * Handle successful checkout session
     */
    public function handleCheckoutSessionCompleted(string $sessionId): bool
    {
        $session = $this->gateway->stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['subscription', 'subscription.latest_invoice'],
        ]);

        if (!$session->subscription) {
            return false;
        }

        $subscription = $session->subscription;
        $metadata = $session->metadata;

        // Ensure tenant has stripe_id linked
        if (isset($metadata['tenant_id'])) {
            $tenant = Tenant::find($metadata['tenant_id']);
            if ($tenant && (!$tenant->stripe_id || $tenant->stripe_id !== $session->customer)) {
                $tenant->update(['stripe_id' => $session->customer]);
            }
        }

        // Find and update subscription
        $tenantSubscription = Subscription::where('tenant_id', $metadata['tenant_id'] ?? null)
            ->where('plan_id', $metadata['plan_id'] ?? null)
            ->latest()
            ->first();

        if ($tenantSubscription) {
            $tenantSubscription->update([
                'stripe_id' => $subscription->id,
                'stripe_price' => $subscription->items->data[0]->price->id,
                'stripe_status' => $subscription->status,
                'trial_ends_at' => $subscription->trial_end ? date('Y-m-d H:i:s', $subscription->trial_end) : null,
                'ends_at' => $subscription->ended_at ? date('Y-m-d H:i:s', $subscription->ended_at) : null,
            ]);

            // Record payment if invoice is paid
            if ($subscription->latest_invoice && $subscription->latest_invoice->paid) {
                $this->recordPayment($tenantSubscription, $subscription->latest_invoice);
            }

            return true;
        }

        return false;
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Subscription $subscription): bool
    {
        if (!$subscription->stripe_id) {
            $subscription->update([
                'stripe_status' => 'canceled',
                'ends_at' => now(),
            ]);

            return true;
        }

        try {
            $stripeSubscription = $this->gateway->cancelSubscription(
                $subscription->stripe_id
            );

            $subscription->update([
                'stripe_status' => $stripeSubscription->status,
                'ends_at' => date('Y-m-d H:i:s', $stripeSubscription->ended_at),
            ]);

            return true;
        } catch (\Exception $e) {
            logger()->error('Failed to cancel subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Update subscription plan
     */
    public function changePlan(Subscription $subscription, Plan $newPlan): bool
    {
        if (!$subscription->stripe_id) {
            return false;
        }

        try {
            $stripeSubscription = $this->gateway->updateSubscription(
                $subscription->stripe_id,
                [
                    'items' => [
                        [
                            'id' => $subscription->stripe_id, // This might need the item ID, but let's assume it's direct for now
                            'price' => $newPlan->stripe_price_id,
                        ]
                    ],
                    'proration_behavior' => 'create_prorations',
                    'metadata' => ['plan_id' => $newPlan->id],
                ]
            );

            $subscription->update([
                'plan_id' => $newPlan->id,
                'stripe_price' => $newPlan->stripe_price_id,
            ]);

            return true;
        } catch (\Exception $e) {
            logger()->error('Failed to change plan', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Record payment in database
     */
    public function recordPayment(Subscription $subscription, $invoice): Payment
    {
        // Handle metadata which could be an object or array
        $metadata = is_object($invoice->metadata) ? (array) $invoice->metadata : ($invoice->metadata ?? []);

        return Payment::updateOrCreate(
            ['stripe_invoice_id' => $invoice->id],
            [
                'tenant_id' => $subscription->tenant_id,
                'subscription_id' => $subscription->id,
                'stripe_payment_intent_id' => $invoice->payment_intent ?? null,
                'amount' => $invoice->amount_paid / 100, // Convert cents to dollars
                'currency' => $invoice->currency,
                'status' => $invoice->status === 'succeeded' ? 'paid' : $invoice->status,
                'payment_method' => $invoice->payment_settings->payment_method_types[0] ?? null,
                'metadata' => $metadata,
                'paid_at' => date('Y-m-d H:i:s', $invoice->created),
            ]
        );
    }
}
