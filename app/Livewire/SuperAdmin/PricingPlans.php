<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Plan;
use App\Services\Subscriptions\SubscriptionService; // ← Fixed import
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class PricingPlans extends Component
{
    use WithPagination;

    public $showModal = false;

    public $editingId = null;

    // Form fields
    public $name = '';

    public $slug = '';

    public $stripe_price_id = '';

    public $stripe_product_id = '';

    public $amount = '';

    public $currency = 'usd';

    public $interval = 'month';

    public $features = '';

    public $active = true;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            // 'slug' => ['required', 'string', 'max:255', Rule::unique('plans', 'slug')->ignore($this->editingId)],
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'interval' => 'required|in:month,year',
            'features' => 'nullable|string',
            'active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        $this->editingId = $id;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->stripe_price_id = $plan->stripe_price_id;
        $this->stripe_product_id = $plan->stripe_product_id;
        $this->amount = $plan->amount / 100; // Convert from cents
        $this->currency = $plan->currency;
        $this->interval = $plan->interval;
        $this->features = $plan->features;
        $this->active = $plan->active;
        $this->showModal = true;
    }

    public function save(SubscriptionService $subscriptionService) // ← Fixed parameter name
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'interval' => $this->interval,
            'features' => $this->features,
            'active' => $this->active,
        ];

        try {
            if ($this->editingId) {
                $subscriptionService->updatePlan($this->editingId, $data);
                session()->flash('message', 'Plan updated successfully!');
            } else {
                $subscriptionService->createPlan($data);
                session()->flash('message', 'Plan created successfully!');
            }

            $this->showModal = false;
            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Operation failed: '.$e->getMessage());
        }
    }

    public function delete($id, SubscriptionService $subscriptionService) // ← Fixed parameter name
    {
        try {
            $subscriptionService->deletePlan($id);
            session()->flash('message', 'Plan deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Delete failed: '.$e->getMessage());
        }
    }

    public function toggleActive($id, SubscriptionService $subscriptionService) // ← Fixed parameter name
    {
        try {
            $subscriptionService->toggleActive($id);
            session()->flash('message', 'Plan status updated!');
        } catch (\Exception $e) {
            session()->flash('error', 'Status update failed: '.$e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->stripe_price_id = '';
        $this->stripe_product_id = '';
        $this->amount = '';
        $this->currency = 'usd';
        $this->interval = 'month';
        $this->features = '';
        $this->active = true;
    }

    public function getStatsProperty()
    {
        return [
            'total_plans' => Plan::count(),
            'active_plans' => Plan::where('active', true)->count(),
            'monthly_plans' => Plan::where('interval', 'month')->count(),
            'yearly_plans' => Plan::where('interval', 'year')->count(),
        ];
    }

    #[\Livewire\Attributes\Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.super-admin.pricing-plans', [
            'plans' => Plan::latest()->paginate(10),
            'stats' => $this->stats,
        ]);
    }
}
