<?php

namespace App\Http\Controllers;

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
        $tenantId = Arr::get($payload, 'data.object.metadata.tenant_id', 'none');

        Log::info('Stripe webhook received', [
            'event' => $eventType,
            'tenant_id' => $tenantId,
            'customer' => Arr::get($payload, 'data.object.customer'),
        ]);

        // Let Cashier do its normal work (create/update subscription, handle payments, etc.)
        // It will verify the signature and process the event
        return parent::handleWebhook($request);
    }
}
