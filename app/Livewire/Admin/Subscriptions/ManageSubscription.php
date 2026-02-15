<?php

namespace App\Livewire\Admin\Subscriptions;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Stripe\StripeClient;

class ManageSubscription extends Component
{
    public $subscription;

    public $invoices = [];

    public function mount()
    {
        $this->subscription = Auth::user()->subscription;
        $this->loadInvoices();
    }

    public function loadInvoices()
    {
        if ($this->subscription && $this->subscription->stripe_id) {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $this->invoices = $stripe->invoices->all([
                'customer' => $this->subscription->stripe_id,
                'limit' => 5,
            ])->data;
        }
    }

    public function cancelSubscription()
    {
        if ($this->subscription) {
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Cancel in Stripe
            $stripe->subscriptions->cancel($this->subscription->stripe_subscription_id);

            // Update locally
            $this->subscription->update([
                'stripe_status' => 'canceled',
                'ends_at' => now(),
            ]);

            session()->flash('message', 'Subscription cancelled successfully.');
            $this->subscription = $this->subscription->fresh();
        }
    }

    public function resumeSubscription()
    {
        if ($this->subscription && $this->subscription->onGracePeriod()) {
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Resume in Stripe
            $stripe->subscriptions->update($this->subscription->stripe_subscription_id, [
                'cancel_at_period_end' => false,
            ]);

            // Update locally
            $this->subscription->update([
                'stripe_status' => 'active',
                'ends_at' => null,
            ]);

            session()->flash('message', 'Subscription resumed successfully.');
            $this->subscription = $this->subscription->fresh();
        }
    }

    public function render()
    {
        return view('livewire.admin.subscriptions.manage-subscription');
    }
}
