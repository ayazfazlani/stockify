<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionRenewalFailed;
use App\Mail\SubscriptionRenewalNotice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Notifications\InvoiceGenerated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

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

    // public function subscribe(Request $request)
    // {
    //     $plans = Plan::all()->pluck('id', 'slug')->keys()->implode(',');
    //     $request->validate([
    //         'plan' => 'required|string|in:'.$plans,
    //         'payment_method' => 'required|string',
    //     ]);

    //     $tenant = tenant();
    //     $planSlug = $request->plan;
    //     $plan = Plan::where('id', $planSlug)->firstOrFail();
    //     $paymentMethod = $request->payment_method;

    //     try {
    //         // If the tenant is already subscribed, we'll swap their subscription
    //         if ($tenant->subscribed('default')) {
    //             $tenant->subscription('default')->swap($plan->stripe_price_id);
    //             $subscription = $tenant->subscription('default');
    //             $subscription->tenant_id = $tenant->id;
    //             $subscription->save();
    //         } else {
    //             // Create a new subscription
    //             $tenant->newSubscription('default', $plan->stripe_price_id)
    //                 ->trialDays($plan->trial_days)
    //                 ->create($paymentMethod);
    //         }

    //         // Update tenant's plan and features
    //         $tenant->update([
    //             'subscription_plan' => $planSlug,
    //             'plan_id' => $plan->id,
    //             'is_active' => true,
    //         ]);

    //         return redirect()->route('subscription.success');
    //     } catch (IncompletePayment $exception) {
    //         return redirect()->route(
    //             'cashier.payment',
    //             [$exception->payment->id, 'redirect' => route('subscription.show')]
    //         );
    //     }
    // }

    public function subscribe(Request $request)
    {
        // Get all valid plan IDs for validation
        $validPlanIds = Plan::all()->pluck('id')->implode(',');

        $request->validate([
            'plan' => 'required|integer|in:'.$validPlanIds,
            'payment_method' => 'required|string',
        ]);

        $tenant = tenant(); // Current tenant (billable model)

        // Since 'plan' is now validated as integer/ID
        $planId = $request->plan;
        $plan = Plan::findOrFail($planId); // Safer than where('id', ...) + firstOrFail()

        $paymentMethod = $request->payment_method;

        $this->purgeNonStripeSubscriptionRecords($tenant);
        $tenant->refresh();

        try {
            $existing = $tenant->subscription('default');

            if ($existing && $this->isStripeSubscriptionId($existing->stripe_id)) {
                // Swap to new price/plan on real Stripe subscription
                $existing->swap($plan->stripe_price_id);
            } else {
                // Create brand new subscription
                $tenant->newSubscription('default', $plan->stripe_price_id)
                    ->trialDays($plan->trial_days ?? 0) // Fallback if null
                    ->create($paymentMethod);
            }

            // Optional: Force tenant_id if observer/global scope not yet in place
            // (Remove this once you implement the observer below)
            $subscription = $tenant->subscription('default');
            if ($subscription->tenant_id !== $tenant->id) {
                $subscription->tenant_id = $tenant->id;
                $subscription->saveQuietly(); // Avoid firing events if not needed
            }

            // Sync tenant's local plan reference
            $tenant->update([
                'subscription_plan' => $plan->slug, // Better to store slug here if you use it elsewhere
                'plan_id' => $plan->id,
                'is_active' => true,
            ]);

            return redirect()->route('subscription.success')
                ->with('success', 'Subscription updated successfully!');
        } catch (IncompletePayment $exception) {
            return redirect()->route('cashier.payment', [
                $exception->payment->id,
                'redirect' => route('subscription.show'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Subscription processing failed', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
            ]);

            return back()->withErrors(['subscription' => 'Failed to process subscription. Please try again or contact support.']);
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
        $tenant = tenant();
        if ($tenant) {
            $sessionId = request()->query('session_id');

            if ($sessionId) {
                $this->syncTenantPlanFromCheckoutSession($tenant, $sessionId);
            } else {
                $this->syncTenantPlanFromSubscription($tenant);
            }
        }

        return view('subscription.success');
    }

    public function checkout(Request $request, $planSlug)
    {
        // Validate the plan exists
        // dd($planSlug);
        $plan = Plan::where('id', $planSlug)->firstOrFail();

        $tenant = tenant();

        // Ensure tenant is resolved
        if (! $tenant) {
            return abort(403, 'Tenant not found or not initialized.');
        }

        // Drop fake/local subscription rows (e.g. stripe_id = free_trial_<uuid>) so Cashier does not report
        // "subscribed" or call swap() with an id Stripe never issued.
        $this->purgeNonStripeSubscriptionRecords($tenant);

        // Only swap when the default subscription is a real Stripe subscription (ids start with sub_).
        if ($tenant->subscribed('default')) {
            $currentSubscription = $tenant->subscription('default');

            if ($currentSubscription && $this->isStripeSubscriptionId($currentSubscription->stripe_id)) {
                try {
                    if ($currentSubscription->stripe_price !== $plan->stripe_price_id) {
                        $currentSubscription->swap($plan->stripe_price_id);
                    }

                    Subscription::query()->updateOrCreate(
                        ['tenant_id' => $tenant->id, 'type' => 'default'],
                        [
                            'plan_id' => $plan->id,
                            'stripe_price' => $plan->stripe_price_id,
                            'stripe_status' => 'active',
                        ]
                    );

                    $tenant->update([
                        'subscription_plan' => $plan->slug,
                        'plan_id' => $plan->id,
                        'is_active' => true,
                    ]);

                    if (method_exists($tenant, 'clearPlanCache')) {
                        $tenant->clearPlanCache();
                    }

                    return redirect()
                        ->route('tenant.subscription.success', ['tenant' => $tenant->slug])
                        ->with('success', 'Plan updated successfully.');
                } catch (InvalidRequestException $e) {
                    if ($currentSubscription) {
                        $this->deleteSubscriptionRecord($currentSubscription);
                    }
                    $tenant->refresh();
                }
            }
        }

        $checkout = $tenant->newSubscription('default', $plan->stripe_price_id)
            ->checkout([
                'success_url' => route('tenant.subscription.success', ['tenant' => $tenant->slug]).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('tenant.subscription.show', ['tenant' => $tenant->slug]),
                // optional
                'billing_address_collection' => 'required',
                'customer_update' => ['address' => 'auto'],
                'metadata' => [
                    'tenant_id' => $tenant->getKey(),
                    'tenant_slug' => $tenant->slug,
                    'plan_id' => $plan->id,
                ],
            ]);

        // This does the 303 redirect to Stripe Checkout
        return $checkout;
    }

    protected function syncTenantPlanFromSubscription(Tenant $tenant): void
    {
        $subscription = $tenant->subscription('default');
        if (! $subscription) {
            return;
        }

        if (! $this->isStripeSubscriptionId($subscription->stripe_id)) {
            return;
        }

        $plan = Plan::query()
            ->where('stripe_price_id', $subscription->stripe_price)
            ->first();

        if (! $plan) {
            return;
        }

        $tenant->update([
            'subscription_plan' => $plan->slug,
            'plan_id' => $plan->id,
            'is_active' => true,
        ]);
    }

    protected function syncTenantPlanFromCheckoutSession(Tenant $tenant, string $sessionId): void
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['subscription', 'line_items.data.price'],
            ]);

            $metadataPlanId = (int) ($session->metadata->plan_id ?? 0);
            $subscriptionObject = $session->subscription ?? null;
            $stripePriceId = null;

            if ($subscriptionObject && isset($subscriptionObject->items->data[0]->price->id)) {
                $stripePriceId = $subscriptionObject->items->data[0]->price->id;
            } elseif (!empty($session->line_items->data[0]->price->id)) {
                $stripePriceId = $session->line_items->data[0]->price->id;
            }

            $plan = null;
            if ($metadataPlanId > 0) {
                $plan = Plan::find($metadataPlanId);
            }

            if (!$plan && $stripePriceId) {
                $plan = Plan::where('stripe_price_id', $stripePriceId)->first();
            }

            if (!$plan) {
                $this->syncTenantPlanFromSubscription($tenant);
                return;
            }

            if ($subscriptionObject) {
                Subscription::query()->updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'type' => 'default',
                    ],
                    [
                        'plan_id' => $plan->id,
                        'stripe_id' => $subscriptionObject->id,
                        'stripe_price' => $stripePriceId ?: $plan->stripe_price_id,
                        'stripe_status' => $subscriptionObject->status ?? 'active',
                        'trial_ends_at' => !empty($subscriptionObject->trial_end)
                            ? date('Y-m-d H:i:s', (int) $subscriptionObject->trial_end)
                            : null,
                        'ends_at' => !empty($subscriptionObject->ended_at)
                            ? date('Y-m-d H:i:s', (int) $subscriptionObject->ended_at)
                            : null,
                    ]
                );
            }

            $tenant->update([
                'subscription_plan' => $plan->slug,
                'plan_id' => $plan->id,
                'is_active' => true,
            ]);

            if (method_exists($tenant, 'clearPlanCache')) {
                $tenant->clearPlanCache();
            }
        } catch (\Throwable $e) {
            \Log::warning('Checkout sync fallback used', [
                'tenant_id' => $tenant->id,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            $this->syncTenantPlanFromSubscription($tenant);
        }
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

    /**
     * Stripe subscription ids always start with "sub_". Local placeholders (e.g. "free_trial_<uuid>")
     * must not be sent to the Stripe API (swap, cancel, etc.).
     */
    protected function isStripeSubscriptionId(?string $stripeId): bool
    {
        if ($stripeId === null || $stripeId === '') {
            return false;
        }

        return str_starts_with($stripeId, 'sub_');
    }

    protected function purgeNonStripeSubscriptionRecords(Tenant $tenant): void
    {
        $subscriptions = Subscription::query()
            ->where('tenant_id', $tenant->id)
            ->get();

        foreach ($subscriptions as $subscription) {
            if (! $this->isStripeSubscriptionId($subscription->stripe_id)) {
                $this->deleteSubscriptionRecord($subscription);
            }
        }
    }

    protected function deleteSubscriptionRecord(Subscription $subscription): void
    {
        DB::table('subscription_items')->where('subscription_id', $subscription->id)->delete();
        $subscription->delete();
    }
}
