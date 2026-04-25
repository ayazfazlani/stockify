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
        
        // 1. Try metadata in various possible locations (Checkout, Subscription, or Invoice)
        $tenantId = Arr::get($payload, 'data.object.metadata.tenant_id') ?? 
                   Arr::get($payload, 'data.object.subscription_details.metadata.tenant_id') ?? 
                   Arr::get($payload, 'data.object.lines.data.0.metadata.tenant_id');
        
        // 2. Try customer lookup
        $customerId = Arr::get($payload, 'data.object.customer');
        
        $tenant = null;
        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
        }
        
        if (!$tenant && $customerId) {
            $tenant = Tenant::where('stripe_id', $customerId)->first();
        }

        // 3. Link customer ID if found via metadata but not yet linked
        if ($tenant && $customerId && (!$tenant->stripe_id || $tenant->stripe_id !== $customerId)) {
            $tenant->update(['stripe_id' => $customerId]);
        }

        Log::info('Stripe webhook received', [
            'event' => $eventType,
            'tenant_id' => $tenant ? $tenant->id : 'none',
            'customer' => $customerId,
            'meta_tenant_id' => $tenantId ?? 'none',
            'found_tenant' => $tenant ? 'yes' : 'no',
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
        return $this->processInvoicePayment($payload);
    }

    /**
     * Handle invoice paid event.
     */
    protected function handleInvoicePaid(array $payload)
    {
        return $this->processInvoicePayment($payload);
    }

    protected function processInvoicePayment(array $payload)
    {
        $invoice = $payload['data']['object'];
        $customerId = $invoice['customer'] ?? null;
        
        // Comprehensive tenant lookup from metadata
        $tenantId = Arr::get($invoice, 'metadata.tenant_id') ?? 
                   Arr::get($invoice, 'subscription_details.metadata.tenant_id') ?? 
                   Arr::get($invoice, 'lines.data.0.metadata.tenant_id');
        
        $tenant = $tenantId ? Tenant::find($tenantId) : Tenant::where('stripe_id', $customerId)->first();
        
        if ($tenant) {
            // Ensure stripe_id is linked if we found via metadata
            if ($customerId && (!$tenant->stripe_id || $tenant->stripe_id !== $customerId)) {
                $tenant->update(['stripe_id' => $customerId]);
            }

            $subscriptionId = $invoice['subscription'] ?? null;
            $subscription = null;
            
            if ($subscriptionId) {
                $subscription = $tenant->subscriptions()
                    ->where('stripe_id', $subscriptionId)
                    ->first();
            }

            // Fallback: If subscription_id is missing from invoice object, 
            // use the tenant's default/most recent subscription
            if (!$subscription) {
                $subscription = $tenant->subscriptions()->latest()->first();
            }

            if ($subscription) {
                app(PaymentService::class)->recordPayment($subscription, (object)$invoice);
            } else {
                Log::warning('Subscription not found for payment recording', [
                    'tenant_id' => $tenant->id,
                    'stripe_subscription_id' => $subscriptionId,
                    'event' => $payload['type']
                ]);
            }
        } else {
            Log::error('Tenant not found for invoice payment', [
                'stripe_customer_id' => $customerId,
                'stripe_invoice_id' => $invoice['id'] ?? 'unknown',
                'event' => $payload['type']
            ]);
        }

        return $this->successMethod();
    }
}
