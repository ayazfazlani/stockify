<?php

namespace App\Livewire\Admin\Subscriptions;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PlansList extends Component
{
    public $currentSubscription;

    public function mount()
    {
        $this->currentSubscription = Auth::user()->subscription;
    }

    public function subscribe($planId)
    {
        $user = Auth::user();
        $plan = Plan::findOrFail($planId);

        // Check if user already has active subscription
        if ($user->hasActiveSubscription()) {
            session()->flash('error', 'You already have an active subscription.');

            return;
        }

        // Redirect to Stripe checkout
        return redirect()->route('stripe.checkout', ['plan' => $plan->id]);
    }

    public function cancelSubscription()
    {
        $subscription = Auth::user()->subscription;

        if ($subscription) {
            $subscription->update(['stripe_status' => 'canceled']);
            session()->flash('message', 'Subscription cancelled successfully.');
        }
    }

    public function render()
    {
        return view('livewire.admin.subscriptions.plans-list', [
            'plans' => Plan::where('active', true)->get(),
        ]);
    }
}
