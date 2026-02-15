<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
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

    public function subscribe(User $user, Plan $plan, ?string $paymentMethodId = null): array
    {
        return DB::transaction(function () use ($user, $plan) {
            // Get or create Stripe customer
            $customerId = $user->stripe_customer_id;
            if (! $customerId) {
                $customer = $this->gateway->createCustomer([
                    'email' => $user->email,
                    'name' => $user->name,
                    'metadata' => ['user_id' => $user->id],
                ]);
                $customerId = $customer->id;
                $user->update(['stripe_customer_id' => $customerId]);
            }

            // Create checkout session
            $checkoutSession = $this->gateway->createCheckoutSession([
                'customer_id' => $customerId,
                'price_id' => $plan->stripe_price_id,
                'success_url' => route('subscription.success'),
                'cancel_url' => route('subscription.cancel'),
                'mode' => 'subscription',
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                ],
                'subscription_metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                ],
                'trial_days' => $plan->trial_days,
            ]);

            // Store pending subscription
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'stripe_customer_id' => $customerId,
                'stripe_price_id' => $plan->stripe_price_id,
                'status' => 'incomplete',
                'stripe_data' => $checkoutSession->toArray(),
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

        if (! $session->subscription) {
            return false;
        }

        $subscription = $session->subscription;
        $metadata = $session->metadata;

        // Find and update subscription
        $userSubscription = Subscription::where('user_id', $metadata['user_id'])
            ->where('plan_id', $metadata['plan_id'])
            ->latest()
            ->first();

        if ($userSubscription) {
            $userSubscription->update([
                'stripe_subscription_id' => $subscription->id,
                'stripe_price_id' => $subscription->items->data[0]->price->id,
                'status' => $subscription->status,
                'current_period_starts_at' => date('Y-m-d H:i:s', $subscription->current_period_start),
                'current_period_ends_at' => date('Y-m-d H:i:s', $subscription->current_period_end),
                'stripe_data' => $subscription->toArray(),
            ]);

            // Record payment if invoice is paid
            if ($subscription->latest_invoice && $subscription->latest_invoice->paid) {
                $this->recordPayment($userSubscription, $subscription->latest_invoice);
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
        if (! $subscription->stripe_subscription_id) {
            $subscription->update([
                'status' => 'canceled',
                'ended_at' => now(),
            ]);

            return true;
        }

        try {
            $stripeSubscription = $this->gateway->cancelSubscription(
                $subscription->stripe_subscription_id
            );

            $subscription->update([
                'status' => $stripeSubscription->status,
                'canceled_at' => date('Y-m-d H:i:s', $stripeSubscription->canceled_at),
                'ended_at' => date('Y-m-d H:i:s', $stripeSubscription->ended_at),
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
        if (! $subscription->stripe_subscription_id) {
            return false;
        }

        try {
            $stripeSubscription = $this->gateway->updateSubscription(
                $subscription->stripe_subscription_id,
                [
                    'items' => [[
                        'id' => $subscription->stripe_subscription->items->data[0]->id,
                        'price' => $newPlan->stripe_price_id,
                    ]],
                    'proration_behavior' => 'create_prorations',
                    'metadata' => ['plan_id' => $newPlan->id],
                ]
            );

            $subscription->update([
                'plan_id' => $newPlan->id,
                'stripe_price_id' => $newPlan->stripe_price_id,
                'stripe_data' => $stripeSubscription->toArray(),
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
        return Payment::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'stripe_invoice_id' => $invoice->id,
            'stripe_payment_intent_id' => $invoice->payment_intent,
            'amount' => $invoice->amount_paid / 100, // Convert cents to dollars
            'currency' => $invoice->currency,
            'status' => $invoice->status,
            'payment_method' => $invoice->payment_method_types[0] ?? null,
            'metadata' => $invoice->metadata?->toArray(),
            'paid_at' => date('Y-m-d H:i:s', $invoice->created),
        ]);
    }
}
