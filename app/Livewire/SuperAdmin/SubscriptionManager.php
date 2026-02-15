<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionManager extends Component
{
    use WithPagination;

    // ====================
    // COMPONENT STATES
    // ====================

    // UI State
    public $activeTab = 'user_plans'; // 'user_plans', 'admin_plans', 'admin_subscriptions'

    public $showPlanModal = false;

    public $showSubscriptionModal = false;

    public $showCancelModal = false;

    // Plan Management
    public $editingPlanId = null;

    public $planName = '';

    public $planSlug = '';

    public $planDescription = '';

    public $planPrice = '';

    public $planInterval = 'month';

    public $planCurrency = 'usd';

    public $planStripePriceId = '';

    public $planStripeProductId = '';

    public $planFeatures = '';

    public $planIsActive = true;

    public $planIsFeatured = false;

    public $planSortOrder = 0;

    public $planTrialDays = 0;

    // User Subscription
    public $selectedUserPlanId = null;

    public $selectedBillingCycle = 'month';

    public $promoCode = '';

    public $isProcessingPayment = false;

    // Admin Subscription Management
    public $editingSubscriptionId = null;

    public $subscriptionUserId = '';

    public $subscriptionPlanId = '';

    public $subscriptionStatus = 'active';

    public $subscriptionTrialEndsAt = '';

    public $subscriptionEndsAt = '';

    // Filters & Search
    public $search = '';

    public $statusFilter = '';

    public $intervalFilter = '';

    public $dateRange = '';

    // ====================
    // LIFECYCLE METHODS
    // ====================

    public function mount()
    {
        $this->selectedUserPlanId = Plan::where('active', true)->first()?->id;
    }

    // ====================
    // VALIDATION RULES
    // ====================

    protected function planRules()
    {
        return [
            'planName' => 'required|string|max:255',
            'planSlug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('plans', 'slug')->ignore($this->editingPlanId),
            ],
            'planDescription' => 'nullable|string|max:1000',
            'planPrice' => 'required|numeric|min:0',
            'planInterval' => 'required|in:month,year,lifetime',
            'planCurrency' => 'required|string|size:3',
            'planFeatures' => 'nullable|string',
            'planIsActive' => 'boolean',
            'planIsFeatured' => 'boolean',
            'planSortOrder' => 'integer|min:0',
            'planTrialDays' => 'integer|min:0|max:365',
        ];
    }

    protected function subscriptionRules()
    {
        return [
            'subscriptionUserId' => 'required|exists:users,id',
            'subscriptionPlanId' => 'required|exists:plans,id',
            'subscriptionStatus' => 'required|in:active,trialing,canceled,past_due,unpaid,incomplete,incomplete_expired',
            'subscriptionTrialEndsAt' => 'nullable|date|after_or_equal:today',
            'subscriptionEndsAt' => 'nullable|date|after_or_equal:today',
        ];
    }

    // ====================
    // USER SUBSCRIPTION FLOW
    // ====================

    public function selectPlan($planId)
    {
        $this->selectedUserPlanId = $planId;
    }

    public function subscribe()
    {
        $this->validate([
            'selectedUserPlanId' => 'required|exists:plans,id',
            'selectedBillingCycle' => 'required|in:month,year',
        ]);

        $this->isProcessingPayment = true;

        try {
            $user = Auth::user();
            $plan = Plan::find($this->selectedUserPlanId);

            $paymentService = app(PaymentService::class);
            $result = $paymentService->subscribe($user, $plan, $this->selectedBillingCycle);

            // Redirect to Stripe Checkout
            return redirect()->away($result['url']);

        } catch (\Exception $e) {
            session()->flash('error', 'Payment failed: '.$e->getMessage());
            $this->isProcessingPayment = false;
        }
    }

    public function cancelUserSubscription()
    {
        $subscription = Auth::user()->activeSubscription;

        if (! $subscription) {
            session()->flash('error', 'No active subscription found.');

            return;
        }

        try {
            $paymentService = app(PaymentService::class);
            $success = $paymentService->cancelSubscription($subscription);

            if ($success) {
                session()->flash('message', 'Your subscription has been cancelled successfully.');
                $this->showCancelModal = false;
            } else {
                session()->flash('error', 'Failed to cancel subscription.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error: '.$e->getMessage());
        }
    }

    public function changePlan($planId)
    {
        $subscription = Auth::user()->activeSubscription;
        $newPlan = Plan::find($planId);

        if (! $subscription || ! $newPlan) {
            session()->flash('error', 'Invalid operation.');

            return;
        }

        try {
            $paymentService = app(PaymentService::class);
            $success = $paymentService->changePlan($subscription, $newPlan);

            if ($success) {
                session()->flash('message', 'Plan changed successfully.');
            } else {
                session()->flash('error', 'Failed to change plan.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error: '.$e->getMessage());
        }
    }

    // ====================
    // ADMIN PLAN MANAGEMENT
    // ====================

    public function createPlan()
    {
        $this->resetPlanForm();
        $this->showPlanModal = true;
    }

    public function editPlan($planId)
    {
        $plan = Plan::findOrFail($planId);
        $this->editingPlanId = $planId;
        $this->planName = $plan->name;
        $this->planSlug = $plan->slug;
        $this->planDescription = $plan->description;
        $this->planPrice = $plan->price;
        $this->planInterval = $plan->interval;
        $this->planCurrency = $plan->currency;
        $this->planStripePriceId = $plan->stripe_price_id;
        $this->planStripeProductId = $plan->stripe_product_id;
        $this->planFeatures = $plan->features;
        $this->planIsActive = $plan->active;
        $this->planIsFeatured = $plan->is_featured;

        $this->planTrialDays = $plan->trial_days;
        $this->showPlanModal = true;
    }

    public function savePlan()
    {
        $this->validate($this->planRules());

        try {
            $data = [
                'name' => $this->planName,
                'slug' => $this->planSlug,
                'description' => $this->planDescription,
                'price' => $this->planPrice,
                'interval' => $this->planInterval,
                'currency' => $this->planCurrency,
                'stripe_price_id' => $this->planStripePriceId,
                'stripe_product_id' => $this->planStripeProductId,
                'features' => $this->planFeatures,
                'active' => $this->planIsActive,
                'is_featured' => $this->planIsFeatured,
                'sort_order' => $this->planSortOrder,
                'trial_days' => $this->planTrialDays,
            ];

            if ($this->editingPlanId) {
                Plan::find($this->editingPlanId)->update($data);
                session()->flash('message', 'Plan updated successfully!');
            } else {
                Plan::create($data);
                session()->flash('message', 'Plan created successfully!');
            }

            $this->showPlanModal = false;
            $this->resetPlanForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Operation failed: '.$e->getMessage());
        }
    }

    public function deletePlan($planId)
    {
        try {
            $plan = Plan::findOrFail($planId);

            // Check if plan has active subscriptions
            if ($plan->activeSubscriptions()->count() > 0) {
                session()->flash('error', 'Cannot delete plan with active subscriptions.');

                return;
            }

            $plan->delete();
            session()->flash('message', 'Plan deleted successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Delete failed: '.$e->getMessage());
        }
    }

    public function togglePlanStatus($planId)
    {
        try {
            $plan = Plan::findOrFail($planId);
            $plan->update(['active' => ! $plan->active]);
            session()->flash('message', 'Plan status updated!');
        } catch (\Exception $e) {
            session()->flash('error', 'Status update failed: '.$e->getMessage());
        }
    }

    private function resetPlanForm()
    {
        $this->editingPlanId = null;
        $this->planName = '';
        $this->planSlug = '';
        $this->planDescription = '';
        $this->planPrice = '';
        $this->planInterval = 'month';
        $this->planCurrency = 'usd';
        $this->planStripePriceId = '';
        $this->planStripeProductId = '';
        $this->planFeatures = '';
        $this->planIsActive = true;
        $this->planIsFeatured = false;
        $this->planSortOrder = 0;
        $this->planTrialDays = 0;
    }

    // ====================
    // ADMIN SUBSCRIPTION MANAGEMENT
    // ====================

    public function createSubscription()
    {
        $this->resetSubscriptionForm();
        $this->showSubscriptionModal = true;
    }

    public function editSubscription($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);
        $this->editingSubscriptionId = $subscriptionId;
        $this->subscriptionUserId = $subscription->user_id;
        $this->subscriptionPlanId = $subscription->plan_id;
        $this->subscriptionStatus = $subscription->stripe_status;
        $this->subscriptionTrialEndsAt = $subscription->trial_ends_at?->format('Y-m-d');
        $this->subscriptionEndsAt = $subscription->ends_at?->format('Y-m-d');
        $this->showSubscriptionModal = true;
    }

    public function saveSubscription()
    {
        $this->validate($this->subscriptionRules());

        try {
            $data = [
                'user_id' => $this->subscriptionUserId,
                'plan_id' => $this->subscriptionPlanId,
                'status' => $this->subscriptionStatus,
                'trial_ends_at' => $this->subscriptionTrialEndsAt ?: null,
                'ends_at' => $this->subscriptionEndsAt ?: null,
            ];

            if ($this->editingSubscriptionId) {
                Subscription::find($this->editingSubscriptionId)->update($data);
                session()->flash('message', 'Subscription updated successfully!');
            } else {
                Subscription::create($data);
                session()->flash('message', 'Subscription created successfully!');
            }

            $this->showSubscriptionModal = false;
            $this->resetSubscriptionForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Operation failed: '.$e->getMessage());
        }
    }

    public function cancelAdminSubscription($subscriptionId)
    {
        try {
            $subscription = Subscription::findOrFail($subscriptionId);
            $subscription->update([
                'stripe_status' => 'canceled',
                'canceled_at' => now(),
                'ends_at' => now(),
            ]);
            session()->flash('message', 'Subscription cancelled successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Cancellation failed: '.$e->getMessage());
        }
    }

    private function resetSubscriptionForm()
    {
        $this->editingSubscriptionId = null;
        $this->subscriptionUserId = '';
        $this->subscriptionPlanId = '';
        $this->subscriptionStatus = 'active';
        $this->subscriptionTrialEndsAt = '';
        $this->subscriptionEndsAt = '';
    }

    // ====================
    // COMPUTED PROPERTIES
    // ====================

    public function getStatsProperty()
    {
        return [
            'total_plans' => Plan::count(),
            'active_plans' => Plan::where('active', true)->count(),
            'total_subscriptions' => Subscription::count(),
            'active_subscriptions' => Subscription::where('stripe_status', 'active')->count(),
            'monthly_revenue' => $this->calculateMonthlyRevenue(),
            'yearly_revenue' => $this->calculateYearlyRevenue(),
        ];
    }

    public function getFilteredPlansProperty()
    {
        return Plan::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%');
            })
            ->when($this->intervalFilter, function ($query) {
                $query->where('interval', $this->intervalFilter);
            })
            ->when($this->statusFilter === 'active', function ($query) {
                $query->where('active', true);
            })
            ->when($this->statusFilter === 'inactive', function ($query) {
                $query->where('active', false);
            })
            ->paginate(10);
    }

    public function getFilteredSubscriptionsProperty()
    {
        return Subscription::query()
            ->with(['user', 'plan'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                })->orWhereHas('plan', function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('stripe_status', $this->statusFilter);
            })
            ->when($this->dateRange, function ($query) {
                $dates = explode(' to ', $this->dateRange);
                if (count($dates) == 2) {
                    $query->whereBetween('created_at', [$dates[0], $dates[1]]);
                }
            })
            ->latest()
            ->paginate(10);
    }

    public function getUserPlansProperty()
    {
        return Plan::where('active', 1)
            ->get();
    }

    public function getUserSubscriptionProperty()
    {
        return Auth::user()->activeSubscription;
    }

    // ====================
    // HELPER METHODS
    // ====================

    private function calculateMonthlyRevenue()
    {
        return Subscription::where('stripe_status', 'active')
            ->whereHas('plan', function ($query) {
                $query->where('interval', 'month');
            })
            ->get()
            ->sum(function ($subscription) {
                return $subscription->plan->price;
            });
    }

    private function calculateYearlyRevenue()
    {
        return Subscription::where('stripe_status', 'active')
            ->whereHas('plan', function ($query) {
                $query->where('interval', 'year');
            })
            ->get()
            ->sum(function ($subscription) {
                return $subscription->plan->price;
            });
    }

    public function formatPrice($price)
    {
        return '$'.number_format($price, 2);
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'active' => 'bg-green-100 text-green-800',
            'trialing' => 'bg-blue-100 text-blue-800',
            'canceled' => 'bg-gray-100 text-gray-800',
            'past_due', 'unpaid' => 'bg-red-100 text-red-800',
            'incomplete' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getIntervalColor($interval)
    {
        return match ($interval) {
            'month' => 'bg-blue-100 text-blue-800',
            'year' => 'bg-purple-100 text-purple-800',
            'lifetime' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    #[\Livewire\Attributes\Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.super-admin.subscription-manager', [
            'userPlans' => $this->userPlans,
            'userSubscription' => $this->userSubscription,
            'plans' => $this->filteredPlans,
            'subscriptions' => $this->filteredSubscriptions,
            'stats' => $this->stats,
        ]);
    }
}
