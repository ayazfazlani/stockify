<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class StripeWebhookController extends CashierWebhookController
{
    /**
     * Handle incoming Stripe webhooks.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['type'] ?? 'unknown';
        
        // Try to get tenant_id from metadata first
        $tenantId = Arr::get($payload, 'data.object.metadata.tenant_id');
        
        // If not in metadata, try to find tenant by customer ID
        $customerId = Arr::get($payload, 'data.object.customer');
        $tenant = null;
        
        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
        } elseif ($customerId) {
            $tenant = Tenant::where('stripe_id', $customerId)->first();
        }

        Log::info('Stripe webhook received', [
            'event' => $eventType,
            'tenant_id' => $tenant ? $tenant->id : 'none',
            'customer' => $customerId,
        ]);

        return parent::handleWebhook($request);
    }

    /**
     * Handle a successful checkout session.
     */
    protected function handleCheckoutSessionCompleted(array $payload)
    {
        $session = (object) $payload['data']['object'];
        
        $paymentService = app(PaymentService::class);
        $paymentService->handleCheckoutSessionCompleted($session->id);

        return $this->successMethod();
    }

    /**
     * Handle invoice payment succeeded.
     * Cashier will also handle this, but we override to record in our payments table.
     */
    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        $invoice = (object) $payload['data']['object'];
        $customerId = $invoice->customer;
        
        $tenant = Tenant::where('stripe_id', $customerId)->first();
        
        if ($tenant) {
            $subscription = $tenant->subscriptions()
                ->where('stripe_id', $invoice->subscription)
                ->first();

            if ($subscription) {
                $paymentService = app(PaymentService::class);
                $paymentService->recordPayment($subscription, $invoice);
            } else {
                Log::warning('Subscription not found for payment recording', [
                    'tenant_id' => $tenant->id,
                    'stripe_subscription_id' => $invoice->subscription
                ]);
            }
        }

        return $this->successMethod();
    }
}
