<?php

namespace App\Livewire\Tenant\Admin;

use App\Enums\PlanFeature;
use App\Models\InventoryAudit;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class Dashboard extends Component
{
    use WithFileUploads;

    // ── Active Settings Tab ─────────────────────────
    #[Url]
    public string $activeSection = 'general';

    // ── General Settings ────────────────────────────
    public string $companyName = '';

    public string $contactEmail = '';

    public string $companyDescription = '';

    public string $timezone = 'UTC';

    public string $dateFormat = 'Y-m-d';

    public string $tenantSlug = '';

    public $avatar;

    public ?string $currentAvatar = null;

    // ── Notification Preferences ────────────────────
    public bool $notifySecurityAlerts = true;

    public bool $notifyBilling = true;

    public bool $notifyProductUpdates = false;

    public bool $notifyLowStock = true;

    // ── Analytics ───────────────────────────────────
    public array $marginLeaders = [];

    public $recentAudits = [];

    // ── Cached Billing Data ─────────────────────────
    protected $cachedCurrentPlan = null;

    protected $cachedSubscription = null;

    protected $cachedUsageStats = null;

    protected $cachedAvailablePlans = null;

    protected $cachedPlanFeatures = null;

    // ── Listeners ───────────────────────────────────
    protected $listeners = ['refreshBilling' => 'refreshBillingData'];

    public function mount()
    {
        $this->syncPlanFromActiveSubscription();

        $tenant = $this->resolveTenant();
        $user = Auth::user();
        if (! $user->isAdmin()) {
            abort(403, 'Unauthorized access to admin settings.');
        }

        if ($tenant) {
            $this->tenantSlug = (string) $tenant->slug;
            $this->companyName = $tenant->name ?? '';
            $this->contactEmail = $user->email ?? '';
            $this->companyDescription = $tenant->description ?? '';
            $this->timezone = $tenant->timezone ?? 'UTC';
            $this->dateFormat = $tenant->date_format ?? 'Y-m-d';
            $this->currentAvatar = $tenant->avatar ? Storage::url($tenant->avatar) : null;

            $this->fetchMarginLeaders($tenant->id);
            $this->fetchRecentAudits($tenant->id);
        }
    }

    /**
     * Refresh billing data - Called when switching to billing tab
     */
    public function refreshBillingData()
    {
        // Clear cached properties
        $this->cachedCurrentPlan = null;
        $this->cachedSubscription = null;
        $this->cachedUsageStats = null;
        $this->cachedAvailablePlans = null;
        $this->cachedPlanFeatures = null;

        // Clear tenant cache
        $tenant = $this->resolveTenant();
        if ($tenant) {
            $tenant->clearPlanCache();
        }

        // Re-sync plan from active subscription
        $this->syncPlanFromActiveSubscription();
    }

    public function fetchMarginLeaders(string $tenantId): void
    {
        $currentStoreId = Auth::user()->getCurrentStoreId();

        $this->marginLeaders = Item::query()
            ->where('store_id', $currentStoreId)
            ->where('quantity', '>', 0)
            ->orderByRaw('(price - cost) * quantity DESC')
            ->take(8)
            ->get()
            ->map(function ($item) {
                $marginValue = max(0, (float) $item->price - (float) $item->cost);
                $marginPct = $item->price > 0 ? ($marginValue / (float) $item->price) * 100 : 0;

                return [
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'qty' => (int) $item->quantity,
                    'margin_value' => $marginValue,
                    'margin_pct' => round($marginPct, 2),
                    'profit_pool' => round($marginValue * (int) $item->quantity, 2),
                ];
            })
            ->all();
    }

    public function fetchRecentAudits(string $tenantId): void
    {
        $currentStoreId = Auth::user()->getCurrentStoreId();

        $this->recentAudits = InventoryAudit::with(['item', 'user'])
            ->where('store_id', $currentStoreId)
            ->latest()
            ->take(10)
            ->get();
    }

    /**
     * Switch the active settings section
     */
    public function switchSection(string $section)
    {
        if ($section === 'billing') {
            $user = auth()->user();
            if (! $user->isStoreAdmin() && ! $user->isSuperAdmin() && tenant('owner_id') !== $user->id) {
                abort(403, 'Unauthorized access to billing.');
            }
            // Refresh billing data when switching to billing section
            $this->refreshBillingData();
        }
        $this->activeSection = $section;
    }

    public function saveGeneralSettings()
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $this->validate([
            'companyName' => 'required|string|max:255',
            'contactEmail' => 'required|email|max:255',
            'companyDescription' => 'nullable|string|max:1000',
            'timezone' => 'required|string',
            'dateFormat' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $tenant = $this->resolveTenant();
        if ($tenant) {
            $updates = [
                'name' => $this->companyName,
                'description' => $this->companyDescription,
                'timezone' => $this->timezone,
                'date_format' => $this->dateFormat,
            ];

            if ($this->avatar) {
                if ($tenant->avatar && Storage::disk('public')->exists($tenant->avatar)) {
                    Storage::disk('public')->delete($tenant->avatar);
                }
                $updates['avatar'] = $this->avatar->store('avatars', 'public');
            }

            $tenant->update($updates);
            $this->currentAvatar = $tenant->avatar ? Storage::url($tenant->avatar) : null;
            $this->reset('avatar');
            $this->dispatch('avatarUpdated');
        }

        session()->flash('settings-success', 'General settings saved successfully!');
    }

    /**
     * Save notification preferences
     */
    public function saveNotificationPrefs()
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $tenant = $this->resolveTenant();
        if ($tenant) {
            $tenant->update([
                'notify_security' => $this->notifySecurityAlerts,
                'notify_billing' => $this->notifyBilling,
                'notify_updates' => $this->notifyProductUpdates,
                'notify_low_stock' => $this->notifyLowStock,
            ]);
        }

        session()->flash('notification-success', 'Notification preferences saved!');
    }

    /**
     * Get the current plan info
     */
    public function getCurrentPlanProperty()
    {
        if ($this->cachedCurrentPlan !== null) {
            return $this->cachedCurrentPlan;
        }

        $tenant = $this->resolveTenant();
        if (! $tenant) {
            return null;
        }

        if ($tenant->plan_id) {
            $plan = Plan::with('planFeatures')->find($tenant->plan_id);
            if ($plan) {
                $this->cachedCurrentPlan = $plan;

                return $plan;
            }
        }

        $subscription = $this->currentSubscription;
        if (! $subscription) {
            return null;
        }

        if ($subscription->plan_id) {
            $plan = Plan::with('planFeatures')->find($subscription->plan_id);
            if ($plan) {
                $this->cachedCurrentPlan = $plan;

                return $plan;
            }
        }

        $plan = Plan::with('planFeatures')->where('stripe_price_id', $subscription->stripe_price)->first();
        $this->cachedCurrentPlan = $plan;

        return $plan;
    }

    /**
     * Get the current subscription
     */
    public function getCurrentSubscriptionProperty()
    {
        if ($this->cachedSubscription !== null) {
            return $this->cachedSubscription;
        }

        $tenant = $this->resolveTenant();
        if (! $tenant) {
            return null;
        }

        $subscription = Subscription::where('tenant_id', $tenant->id)
            ->whereIn('stripe_status', ['active', 'trialing', 'past_due'])
            ->latest()
            ->first();

        $this->cachedSubscription = $subscription;

        return $subscription;
    }

    /**
     * Get the tenant's payment history
     */
    public function getPaymentHistoryProperty()
    {
        $tenant = $this->resolveTenant();
        if (! $tenant) {
            return collect([]);
        }

        return Payment::where('tenant_id', $tenant->id)
            ->latest('paid_at')
            ->take(20)
            ->get();
    }

    public function changePlan(int $planId): void
    {
        $tenant = $this->resolveTenant();
        if (! $tenant) {
            session()->flash('settings-error', 'Tenant not found.');

            return;
        }

        $plan = Plan::where('active', true)->find($planId);
        if (! $plan) {
            session()->flash('settings-error', 'Selected plan is not available.');

            return;
        }

        if (! $tenant->subscribed('default')) {
            session()->flash('settings-error', 'No active subscription found. Please subscribe first.');

            return;
        }

        $currentSubscription = $tenant->subscription('default');
        if (! $currentSubscription) {
            session()->flash('settings-error', 'Subscription record not found.');

            return;
        }

        if ($currentSubscription->stripe_price === $plan->stripe_price_id) {
            session()->flash('settings-success', 'You are already on this plan.');

            return;
        }

        try {
            $currentSubscription->swap($plan->stripe_price_id);

            Subscription::query()
                ->where('tenant_id', $tenant->id)
                ->where('type', 'default')
                ->latest()
                ->first()?->update([
                    'plan_id' => $plan->id,
                    'stripe_status' => 'active',
                ]);

            $tenant->update([
                'plan_id' => $plan->id,
                'subscription_plan' => $plan->slug,
                'is_active' => true,
            ]);

            // Refresh billing data after plan change
            $this->refreshBillingData();
            session()->flash('settings-success', "Plan changed to {$plan->name} successfully.");
        } catch (\Throwable $e) {
            report($e);
            session()->flash('settings-error', 'Unable to change plan right now. Please try again.');
        }
    }

    protected function syncPlanFromActiveSubscription(): void
    {
        $tenant = $this->resolveTenant();
        if (! $tenant) {
            return;
        }

        $activeSubscription = Subscription::query()
            ->where('tenant_id', $tenant->id)
            ->whereIn('stripe_status', ['active', 'trialing', 'past_due'])
            ->latest()
            ->first();

        if (! $activeSubscription) {
            return;
        }

        $plan = null;
        if ($activeSubscription->plan_id) {
            $plan = Plan::find($activeSubscription->plan_id);
        }

        if (! $plan && $activeSubscription->stripe_price) {
            $plan = Plan::where('stripe_price_id', $activeSubscription->stripe_price)->first();
        }

        if (! $plan) {
            return;
        }

        if ((int) $activeSubscription->plan_id !== (int) $plan->id) {
            $activeSubscription->update(['plan_id' => $plan->id]);
        }

        if ((int) $tenant->plan_id !== (int) $plan->id || ! $tenant->is_active || $tenant->subscription_plan !== $plan->slug) {
            $tenant->update([
                'plan_id' => $plan->id,
                'subscription_plan' => $plan->slug,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Get usage statistics for quota features
     */
    public function getUsageStatsProperty()
    {
        if ($this->cachedUsageStats !== null) {
            return $this->cachedUsageStats;
        }

        $tenant = $this->resolveTenant();
        if (! $tenant) {
            return [];
        }

        $storeCount = Store::where('tenant_id', $tenant->id)->count();
        $storeIds = Store::where('tenant_id', $tenant->id)->pluck('id');
        $itemCount = Item::whereIn('store_id', $storeIds)->count();
        $memberCount = User::where('tenant_id', $tenant->id)->count();

        $storeLimit = $tenant->getFeatureLimit(PlanFeature::MAX_STORES);
        $itemLimit = $tenant->getFeatureLimit(PlanFeature::MAX_ITEMS);
        $memberLimit = $tenant->getFeatureLimit(PlanFeature::MAX_TEAM_MEMBERS);

        $stats = [
            'stores' => [
                'used' => $storeCount,
                'limit' => $storeLimit,
                'label' => 'Stores',
                'icon' => 'bx-store',
                'color' => '#8b5cf6',
            ],
            'items' => [
                'used' => $itemCount,
                'limit' => $itemLimit,
                'label' => 'Items',
                'icon' => 'bx-package',
                'color' => '#3b82f6',
            ],
            'members' => [
                'used' => $memberCount,
                'limit' => $memberLimit,
                'label' => 'Team Members',
                'icon' => 'bx-group',
                'color' => '#10b981',
            ],
        ];

        $this->cachedUsageStats = $stats;

        return $stats;
    }

    /**
     * Get all available plans for upgrade comparison
     */
    public function getAvailablePlansProperty()
    {
        if ($this->cachedAvailablePlans !== null) {
            return $this->cachedAvailablePlans;
        }

        $plans = Plan::where('active', true)
            ->with('planFeatures')
            ->orderBy('amount')
            ->get();

        $this->cachedAvailablePlans = $plans;

        return $plans;
    }

    /**
     * Get boolean features for current plan (for display)
     */
    public function getPlanFeaturesProperty()
    {
        if ($this->cachedPlanFeatures !== null) {
            return $this->cachedPlanFeatures;
        }

        $plan = $this->getCurrentPlanProperty();
        if (! $plan) {
            return [];
        }

        $features = $plan->planFeatures
            ->map(function ($pf) {
                $enum = PlanFeature::tryFrom($pf->feature);
                if (! $enum) {
                    return null;
                }

                return [
                    'key' => $pf->feature,
                    'label' => $enum->label(),
                    'icon' => $enum->icon(),
                    'type' => $enum->type(),
                    'value' => $pf->value,
                    'group' => $enum->group(),
                ];
            })
            ->filter()
            ->groupBy('group')
            ->toArray();

        $this->cachedPlanFeatures = $features;

        return $features;
    }

    /**
     * Check if a specific feature is available
     */
    public function tenantHasFeature(string $feature): bool
    {
        $tenant = $this->resolveTenant();
        if ($tenant) {
            return $tenant->hasFeature($feature);
        }

        return false;
    }

    protected function resolveTenant(): ?Tenant
    {
        $resolved = tenant();
        if ($resolved) {
            $resolved->refresh();

            return $resolved;
        }

        $tenantId = Auth::user()?->tenant_id;
        if (! $tenantId) {
            return null;
        }

        return Tenant::query()->find($tenantId);
    }

    public function render()
    {
        // Security check for billing access when using URL persistence
        if ($this->activeSection === 'billing') {
            $user = auth()->user();
            if (! $user->isStoreAdmin() && ! $user->isSuperAdmin() && tenant('owner_id') !== $user->id) {
                $this->activeSection = 'general';
            }
        }

        return view('livewire.tenant.admin.dashboard', [
            'currentPlan' => $this->currentPlan,
            'currentSubscription' => $this->currentSubscription,
            'paymentHistory' => $this->paymentHistory,
            'usageStats' => $this->usageStats,
            'availablePlans' => $this->availablePlans,
            'planFeatures' => $this->planFeatures,
            'allFeatures' => PlanFeature::cases(),
            'tenantSlug' => $this->tenantSlug,
        ]);
    }
}
