<?php

namespace App\Livewire\Admin\Subscriptions;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeCheckout extends Component
{
    public $plan;

    public $paymentMethodId;

    public $error;

    public function mount($planId)
    {
        $this->plan = Plan::findOrFail($planId);
    }

    public function processSubscription()
    {
        $user = Auth::user();

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Create or get Stripe customer
            if (! $user->stripe_customer_id) {
                $customer = $stripe->customers->create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                $user->update(['stripe_customer_id' => $customer->id]);
            }

            // Create subscription
            $subscription = $stripe->subscriptions->create([
                'customer' => $user->stripe_customer_id,
                'items' => [[
                    'price' => $this->plan->stripe_price_id,
                ]],
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            // Save to database
            $user->subscription()->create([
                'type' => 'default',
                'stripe_id' => $user->stripe_customer_id,
                'stripe_subscription_id' => $subscription->id,
                'stripe_status' => $subscription->status,
                'stripe_price' => $this->plan->stripe_price_id,
                'plan_id' => $this->plan->id,
                'quantity' => 1,
                'trial_ends_at' => null,
                'ends_at' => null,
            ]);

            session()->flash('message', 'Subscription created successfully!');

            return redirect()->route('dashboard');

        } catch (ApiErrorException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.admin.subscriptions.stripe-checkout');
    }
}
