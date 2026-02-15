<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscriptionManagement extends Component
{
    public $team;

    public $currentPlan;

    public $showCancelModal = false;

    public $invoices;

    public function mount()
    {
        $this->team = Auth::user()->currentTeam;
        // $this->currentPlan = config('saas.plans')[$this->team->subscription_plan] ?? null;
        $this->loadInvoices();
    }

    public function loadInvoices()
    {
        // if ($this->team->hasStripeId()) {
        $this->invoices = $this->team->invoices();
        // } else {
        $this->invoices = [];
        // }
    }

    public function confirmCancel()
    {
        $this->showCancelModal = true;
    }

    public function cancelSubscription()
    {
        $this->team->subscription('default')->cancel();
        $this->showCancelModal = false;
        session()->flash('success', 'Your subscription has been cancelled. You will have access until the end of your billing period.');
    }

    public function resumeSubscription()
    {
        $this->team->subscription('default')->resume();
        session()->flash('success', 'Your subscription has been resumed.');
    }

    public function downloadInvoice($invoiceId)
    {
        return $this->team->downloadInvoice($invoiceId, [
            'vendor' => config('app.name'),
            'product' => $this->currentPlan['name'].' Plan',
        ]);
    }

    public function render()
    {
        return view('livewire.subscription-management', [
            'plans' => config('saas.plans'),
        ]);
    }
}
