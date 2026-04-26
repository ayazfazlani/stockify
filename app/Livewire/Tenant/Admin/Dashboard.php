<?php

namespace App\Livewire\Tenant\Admin;

use App\Enums\PlanFeature;
use App\Models\Item;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\InventoryAudit;
use App\Models\Payment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Dashboard extends Component
{
    use WithFileUploads;

    // ── Active Settings Tab ─────────────────────────
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

    public function mount()
    {
        $this->syncPlanFromActiveSubscription();

        $tenant = $this->resolveTenant();
        $user = Auth::user();

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

    public function fetchMarginLeaders(string $tenantId): void
    {
        $storeIds = Store::where('tenant_id', $tenantId)->pluck('id');

        $this->marginLeaders = Item::query()
            ->whereIn('store_id', $storeIds)
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
        $storeIds = Store::where('tenant_id', $tenantId)->pluck('id');

        $this->recentAudits = InventoryAudit::with(['item', 'user'])
            ->whereIn('store_id', $storeIds)
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
            if (!$user->isStoreAdmin() && !$user->isSuperAdmin() && tenant('owner_id') !== $user->id) {
                abort(403, 'Unauthorized access to billing.');
            }
        }
        $this->activeSection = $section;
    }

    /**
     * Save general settings
     */
    public function saveGeneralSettings()
    {
        $this->validate([
            'companyName' => 'required|string|max:255',
            'contactEmail' => 'required|email|max:255',
            'companyDescription' => 'nullable|string|max:1000',
            'timezone' => 'required|string',
            'dateFormat' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $tenant = tenant();
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
        }

        session()->flash('settings-success', 'General settings saved successfully!');
    }

    /**
     * Save notification preferences
     */
    public function saveNotificationPrefs()
    {
        $tenant = tenant();
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
        $tenant = $this->resolveTenant();
        if (!$tenant) {
            return null;
        }

        if ($tenant->plan_id) {
            $plan = Plan::with('planFeatures')->find($tenant->plan_id);
            if ($plan) {
                return $plan;
            }
        }

        $subscription = $this->currentSubscription;
        if (!$subscription) {
            return null;
        }

        if ($subscription->plan_id) {
            $plan = Plan::with('planFeatures')->find($subscription->plan_id);
            if ($plan) {
                return $plan;
            }
        }

        return Plan::with('planFeatures')->where('stripe_price_id', $subscription->stripe_price)->first();
    }

    /**
     * Get the current subscription
     */
    public function getCurrentSubscriptionProperty()
    {
        $tenant = $this->resolveTenant();
        if (!$tenant) {
            return null;
        }

        return Subscription::where('tenant_id', $tenant->id)
            ->whereIn('stripe_status', ['active', 'trialing', 'past_due'])
            ->latest()
            ->first();
    }

    /**
     * Get the tenant's payment history
     */
    public function getPaymentHistoryProperty()
    {
        $tenant = $this->resolveTenant();
        if (!$tenant) {
            return collect([]);
        }

        return Payment::where('tenant_id', $tenant->id)
            ->latest('paid_at')
            ->take(20)
            ->get();
    }

    public function changePlan(int $planId): void
    {
        $tenant = tenant();
        if (!$tenant) {
            session()->flash('settings-error', 'Tenant not found.');
            return;
        }

        $plan = Plan::where('active', true)->find($planId);
        if (!$plan) {
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

            session()->flash('settings-success', "Plan changed to {$plan->name} successfully.");
        } catch (\Throwable $e) {
            report($e);
            session()->flash('settings-error', 'Unable to change plan right now. Please try again.');
        }
    }

    protected function syncPlanFromActiveSubscription(): void
    {
        $tenant = $this->resolveTenant();
        if (!$tenant) {
            return;
        }

        $activeSubscription = Subscription::query()
            ->where('tenant_id', $tenant->id)
            ->whereIn('stripe_status', ['active', 'trialing', 'past_due'])
            ->latest()
            ->first();

        if (!$activeSubscription) {
            return;
        }

        $plan = null;
        if ($activeSubscription->plan_id) {
            $plan = Plan::find($activeSubscription->plan_id);
        }

        if (!$plan && $activeSubscription->stripe_price) {
            $plan = Plan::where('stripe_price_id', $activeSubscription->stripe_price)->first();
        }

        if (!$plan) {
            return;
        }

        if ((int) $activeSubscription->plan_id !== (int) $plan->id) {
            $activeSubscription->update(['plan_id' => $plan->id]);
        }

        if ((int) $tenant->plan_id !== (int) $plan->id || !$tenant->is_active || $tenant->subscription_plan !== $plan->slug) {
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
        $tenant = tenant();
        if (!$tenant) {
            return [];
        }

        $storeCount = Store::where('tenant_id', $tenant->id)->count();
        $storeIds = Store::where('tenant_id', $tenant->id)->pluck('id');
        $itemCount = Item::whereIn('store_id', $storeIds)->count();
        $memberCount = User::where('tenant_id', $tenant->id)->count();

        $storeLimit = $tenant->getFeatureLimit(PlanFeature::MAX_STORES);
        $itemLimit = $tenant->getFeatureLimit(PlanFeature::MAX_ITEMS);
        $memberLimit = $tenant->getFeatureLimit(PlanFeature::MAX_TEAM_MEMBERS);

        return [
            'stores' => [
                'used' => $storeCount,
                'limit' => $storeLimit,
                'label' => 'Stores',
                'icon' => 'fa-store',
                'color' => '#8b5cf6',
            ],
            'items' => [
                'used' => $itemCount,
                'limit' => $itemLimit,
                'label' => 'Items',
                'icon' => 'fa-boxes',
                'color' => '#3b82f6',
            ],
            'members' => [
                'used' => $memberCount,
                'limit' => $memberLimit,
                'label' => 'Team Members',
                'icon' => 'fa-users',
                'color' => '#10b981',
            ],
        ];
    }

    /**
     * Get all available plans for upgrade comparison
     */
    public function getAvailablePlansProperty()
    {
        return Plan::where('active', true)
            ->with('planFeatures')
            ->orderBy('amount')
            ->get();
    }

    /**
     * Get boolean features for current plan (for display)
     */
    public function getPlanFeaturesProperty()
    {
        $plan = $this->currentPlan;
        if (!$plan) {
            return [];
        }

        return $plan->planFeatures
            ->map(function ($pf) {
                $enum = PlanFeature::tryFrom($pf->feature);
                if (!$enum) return null;
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
    }

    /**
     * Check if a specific feature is available
     */
    public function tenantHasFeature(string $feature): bool
    {
        $tenant = $this->resolveTenant();
        return $tenant ? $tenant->hasFeature($feature) : false;
    }

    protected function resolveTenant(): ?Tenant
    {
        $resolved = tenant();
        if ($resolved) {
            return $resolved;
        }

        $tenantId = Auth::user()?->tenant_id;
        if (!$tenantId) {
            return null;
        }

        return Tenant::query()->find($tenantId);
    }

    public function render()
    {
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
