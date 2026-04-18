<?php

namespace App\Livewire\SuperAdmin;

use App\Enums\PlanFeature;
use App\Models\Plan;
use App\Services\Subscriptions\SubscriptionService;
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

    public $features = '';  // Legacy text features (for display on pricing page)

    public $active = true;

    // ── Feature Assignment ──────────────────────────
    public array $selectedFeatures = [];   // ['analytics' => true, 'bulk-import' => false, ...]

    public array $featureValues = [];      // ['max-items' => '500', 'max-stores' => '3', ...]

    // ── View feature details modal ──────────────────
    public $showFeaturesModal = false;

    public $viewingPlanId = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
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
        $plan = Plan::with('planFeatures')->findOrFail($id);
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

        // Load assigned features
        $this->selectedFeatures = [];
        $this->featureValues = [];

        foreach (PlanFeature::cases() as $feature) {
            $this->selectedFeatures[$feature->value] = false;
            $this->featureValues[$feature->value] = '';
        }

        foreach ($plan->planFeatures as $pf) {
            $this->selectedFeatures[$pf->feature] = true;
            if ($pf->value !== null) {
                $this->featureValues[$pf->feature] = $pf->value;
            }
        }

        $this->showModal = true;
    }

    public function save(SubscriptionService $subscriptionService)
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
                $plan = Plan::find($this->editingId);
            } else {
                $plan = $subscriptionService->createPlan($data);
            }

            // Sync features to the plan
            if ($plan) {
                $featuresToSync = $this->buildFeatureArray();
                $plan->syncFeatures($featuresToSync);
            }

            session()->flash('message', $this->editingId ? 'Plan updated successfully!' : 'Plan created successfully!');

            $this->showModal = false;
            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Operation failed: '.$e->getMessage());
        }
    }

    /**
     * Build the feature array from selected checkboxes and values.
     *
     * Returns: ['analytics' => null, 'max-items' => '500', ...]
     */
    private function buildFeatureArray(): array
    {
        $features = [];

        foreach ($this->selectedFeatures as $key => $enabled) {
            if ($enabled) {
                $enum = PlanFeature::tryFrom($key);
                if ($enum) {
                    $features[$key] = $enum->type() === 'quota'
                        ? ($this->featureValues[$key] ?? null)
                        : null;
                }
            }
        }

        return $features;
    }

    public function delete($id, SubscriptionService $subscriptionService)
    {
        try {
            $subscriptionService->deletePlan($id);
            session()->flash('message', 'Plan deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Delete failed: '.$e->getMessage());
        }
    }

    public function toggleActive($id, SubscriptionService $subscriptionService)
    {
        try {
            $subscriptionService->toggleActive($id);
            session()->flash('message', 'Plan status updated!');
        } catch (\Exception $e) {
            session()->flash('error', 'Status update failed: '.$e->getMessage());
        }
    }

    /**
     * View features for a specific plan.
     */
    public function viewFeatures($planId)
    {
        $this->viewingPlanId = $planId;
        $this->showFeaturesModal = true;
    }

    public function closeFeaturesModal()
    {
        $this->showFeaturesModal = false;
        $this->viewingPlanId = null;
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

        // Reset feature selections
        $this->selectedFeatures = [];
        $this->featureValues = [];
        foreach (PlanFeature::cases() as $feature) {
            $this->selectedFeatures[$feature->value] = false;
            $this->featureValues[$feature->value] = '';
        }
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
            'plans' => Plan::with('planFeatures')->latest()->paginate(10),
            'stats' => $this->stats,
            'featureGroups' => PlanFeature::grouped(),
            'viewingPlan' => $this->viewingPlanId ? Plan::with('planFeatures')->find($this->viewingPlanId) : null,
        ]);
    }
}
