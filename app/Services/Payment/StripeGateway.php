<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use Stripe\StripeClient;

class StripeGateway implements PaymentGatewayInterface
{
    public StripeClient $stripe;

    protected bool $isTestMode;

    public function __construct()
    {
        $this->isTestMode = config('services.stripe.mode', 'test') === 'test';
        $this->stripe = new StripeClient(
            $this->isTestMode
                ? config('services.stripe.test_secret')
                : config('services.stripe.live_secret')
        );
    }

    public function createCustomer(array $data): \Stripe\Customer
    {
        return $this->stripe->customers->create([
            'email' => $data['email'],
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'metadata' => $data['metadata'] ?? [],
        ]);
    }

    public function createSubscription(array $data): \Stripe\Subscription
    {
        $subscriptionData = [
            'customer' => $data['customer_id'],
            'items' => [
                ['price' => $data['price_id']],
            ],
            'payment_behavior' => 'default_incomplete',
            'expand' => ['latest_invoice.payment_intent'],
            'metadata' => $data['metadata'] ?? [],
        ];

        // Add trial if specified
        if (! empty($data['trial_days'])) {
            $subscriptionData['trial_period_days'] = $data['trial_days'];
        }

        // Add coupon if specified
        if (! empty($data['coupon'])) {
            $subscriptionData['coupon'] = $data['coupon'];
        }

        return $this->stripe->subscriptions->create($subscriptionData);
    }

    public function createCheckoutSession(array $data): \Stripe\Checkout\Session
    {
        $sessionData = [
            'customer' => $data['customer_id'] ?? null,
            'customer_email' => $data['customer_email'] ?? null,
            'success_url' => $data['success_url'].'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $data['cancel_url'],
            'mode' => $data['mode'] ?? 'subscription',
            'allow_promotion_codes' => $data['allow_promotion_codes'] ?? true,
            'metadata' => $data['metadata'] ?? [],
        ];

        // Add line items based on mode
        if ($sessionData['mode'] === 'subscription') {
            $sessionData['line_items'] = [[
                'price' => $data['price_id'],
                'quantity' => 1,
            ]];

            // Subscription-specific settings
            $sessionData['subscription_data'] = [
                'metadata' => $data['subscription_metadata'] ?? [],
                'trial_period_days' => $data['trial_days'] ?? null,
            ];
        } else {
            // One-time payment
            $sessionData['line_items'] = [[
                'price_data' => [
                    'currency' => $data['currency'] ?? 'usd',
                    'product_data' => [
                        'name' => $data['product_name'],
                    ],
                    'unit_amount' => $data['unit_amount'], // in cents
                ],
                'quantity' => 1,
            ]];
            $sessionData['payment_intent_data'] = [
                'metadata' => $data['metadata'] ?? [],
            ];
        }

        return $this->stripe->checkout->sessions->create($sessionData);
    }

    public function cancelSubscription(string $subscriptionId): \Stripe\Subscription
    {
        return $this->stripe->subscriptions->cancel($subscriptionId, [
            'invoice_now' => false,
            'prorate' => false,
        ]);
    }

    public function retrieveSubscription(string $subscriptionId): \Stripe\Subscription
    {
        return $this->stripe->subscriptions->retrieve($subscriptionId);
    }

    public function updateSubscription(string $subscriptionId, array $data): \Stripe\Subscription
    {
        return $this->stripe->subscriptions->update($subscriptionId, $data);
    }

    public function createInvoice(array $data): \Stripe\Invoice
    {
        return $this->stripe->invoices->create($data);
    }

    public function retrieveInvoice(string $invoiceId): \Stripe\Invoice
    {
        return $this->stripe->invoices->retrieve($invoiceId);
    }

    // Additional useful methods
    public function createPrice(array $data): \Stripe\Price
    {
        return $this->stripe->prices->create([
            'product' => $data['product_id'],
            'unit_amount' => $data['unit_amount'], // in cents
            'currency' => $data['currency'] ?? 'usd',
            'recurring' => [
                'interval' => $data['interval'] ?? 'month',
                'interval_count' => $data['interval_count'] ?? 1,
            ],
            'metadata' => $data['metadata'] ?? [],
        ]);
    }

    public function createProduct(array $data): \Stripe\Product
    {
        return $this->stripe->products->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'metadata' => $data['metadata'] ?? [],
        ]);
    }

    public function webhookConstructEvent(string $payload, string $signature): \Stripe\Event
    {
        return \Stripe\Webhook::constructEvent(
            $payload,
            $signature,
            $this->isTestMode
                ? config('services.stripe.test_webhook_secret')
                : config('services.stripe.live_webhook_secret')
        );
    }
}
