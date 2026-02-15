<?php

// app/Livewire/Subscriptions/Subscribe.php

namespace app\Livewire\Subscriptions;

use App\Models\Plan;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Subscribe extends Component
{
    public $plans;

    public $selectedPlan;

    public $isProcessing = false;

    public $errorMessage = null;

    protected $rules = [
        'selectedPlan' => 'required|exists:plans,id',
    ];

    public function mount()
    {
        $this->plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $this->selectedPlan = $this->plans->first()->id ?? null;
    }

    public function subscribe()
    {
        $this->validate();
        $this->isProcessing = true;
        $this->errorMessage = null;

        try {
            $plan = Plan::find($this->selectedPlan);
            $user = Auth::user();

            $paymentService = app(PaymentService::class);
            $result = $paymentService->subscribe($user, $plan);

            // Redirect to Stripe Checkout
            return redirect()->away($result['url']);

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.Subscriptions.subscribe');
    }
}
