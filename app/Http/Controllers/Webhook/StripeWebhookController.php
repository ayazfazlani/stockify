<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $paymentService = app(\App\Services\Payment\StripeGateway::class);
            $event = $paymentService->webhookConstructEvent($payload, $sigHeader);
        } catch (\Exception $e) {
            Log::error('Stripe webhook signature verification failed.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info('Stripe webhook received', ['type' => $event->type]);

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'invoice.paid':
                $this->handleInvoicePaid($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleCheckoutSessionCompleted($session)
    {
        $paymentService = app(PaymentService::class);
        $paymentService->handleCheckoutSessionCompleted($session->id);
    }

    protected function handleSubscriptionUpdated($subscription)
    {
        $userSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();

        if ($userSubscription) {
            $userSubscription->update([
                'status' => $subscription->status,
                'current_period_starts_at' => date('Y-m-d H:i:s', $subscription->current_period_start),
                'current_period_ends_at' => date('Y-m-d H:i:s', $subscription->current_period_end),
                'stripe_data' => $subscription->toArray(),
            ]);
        }
    }

    protected function handleSubscriptionDeleted($subscription)
    {
        $userSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();

        if ($userSubscription) {
            $userSubscription->update([
                'status' => 'canceled',
                'ended_at' => now(),
            ]);
        }
    }

    protected function handleInvoicePaid($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            $paymentService = app(PaymentService::class);
            $paymentService->recordPayment($subscription, $invoice);
        }
    }

    protected function handleInvoicePaymentFailed($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            $subscription->update(['status' => 'past_due']);

            // Notify user about failed payment
            // Mail::to($subscription->user->email)->send(new PaymentFailed($subscription));
        }
    }
}
