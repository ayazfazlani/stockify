<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionRenewalFailed;
use App\Mail\SubscriptionRenewalNotice;
use App\Models\Plan;
use App\Models\Tenant;
use App\Notifications\InvoiceGenerated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('subscription.index', [
            'plans' => Plan::all(),
            'tenant' => tenant(),
        ]);
    }

    public function show(Request $request)
    {
        $tenant = tenant();

        return view('subscription.show', [
            // 'intent' => $tenant->createSetupIntent(),
            'plans' => Plan::all(),
            'tenant' => $tenant,
        ]);
    }

    public function subscribe(Request $request)
    {
        $plans = Plan::all()->pluck('id', 'slug')->keys()->implode(',');
        $request->validate([
            'plan' => 'required|string|in:'.$plans,
            'payment_method' => 'required|string',
        ]);

        $tenant = tenant();
        $planSlug = $request->plan;
        $plan = Plan::where('slug', $planSlug)->firstOrFail();
        $paymentMethod = $request->payment_method;

        try {
            // If the tenant is already subscribed, we'll swap their subscription
            if ($tenant->subscribed('default')) {
                $tenant->subscription('default')->swap($plan->stripe_price_id);
            } else {
                // Create a new subscription
                $tenant->newSubscription('default', $plan->stripe_price_id)
                    ->trialDays($plan->trial_days)
                    ->create($paymentMethod);
            }

            // Update tenant's plan and features
            $tenant->update([
                'subscription_plan' => $planSlug,
                'plan_id' => $plan->id,
                'is_active' => true,
            ]);

            return redirect()->route('subscription.success');
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('subscription.show')]
            );
        }
    }

    public function cancel(Request $request)
    {
        $tenant = tenant();

        // Cancel the subscription at the end of the period
        $tenant->subscription('default')->cancel();

        return redirect()->route('subscription.show')
            ->with('success', 'Your subscription has been cancelled. You will have access until the end of your billing period.');
    }

    public function resume(Request $request)
    {
        $tenant = tenant();

        // Resume the subscription
        $tenant->subscription('default')->resume();

        return redirect()->route('subscription.show')
            ->with('success', 'Your subscription has been resumed.');
    }

    public function success()
    {
        return view('subscription.success');
    }

    public function checkout(Request $request, $planSlug)
    {
        // Validate the plan exists
        // dd($planSlug);
        $plan = Plan::where('slug', $planSlug)->firstOrFail();

        $tenant = tenant();
        
        // Ensure tenant is resolved
        if (!$tenant) {
            return abort(403, 'Tenant not found or not initialized.');
        }

        $checkout = $tenant->newSubscription('default', $plan->stripe_price_id)
            ->checkout([
                'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                // optional
                'billing_address_collection' => 'required',
                'customer_update' => ['address' => 'auto'],
            ]);

        // This does the 303 redirect to Stripe Checkout
        return $checkout;
    }

    public function webhook(Request $request)
    {
        $stripeWebhook = json_decode($request->getContent(), true);
        $eventType = $stripeWebhook['type'];

        switch ($eventType) {
            case 'invoice.payment_succeeded':
                $this->handleSuccessfulPayment($stripeWebhook['data']['object']);
                break;

            case 'invoice.payment_failed':
                $this->handleFailedPayment($stripeWebhook['data']['object']);
                break;

            case 'customer.subscription.trial_will_end':
                $this->handleTrialEnding($stripeWebhook['data']['object']);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleSuccessfulPayment($invoice)
    {
        $tenant = Tenant::where('stripe_id', $invoice['customer'])->first();

        if ($tenant) {
            // Generate and send invoice notification
            $cashierInvoice = $tenant->findInvoice($invoice['id']);
            $tenant->owner->notify(new InvoiceGenerated($cashierInvoice));
        }
    }

    protected function handleFailedPayment($invoice)
    {
        $tenant = Tenant::where('stripe_id', $invoice['customer'])->first();

        if ($tenant) {
            Mail::to($tenant->owner->email)
                ->send(new SubscriptionRenewalFailed($tenant, $tenant->subscription('default')->latestPayment()));
        }
    }

    protected function handleTrialEnding($subscription)
    {
        $tenant = Tenant::where('stripe_id', $subscription['customer'])->first();

        if ($tenant) {
            Mail::to($tenant->owner->email)
                ->send(new SubscriptionRenewalNotice($tenant, true));
        }
    }
}
