<?php

namespace App\Livewire\Tenant\Admin;

use App\Enums\PlanFeature;
use App\Models\Item;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    // ── Active Settings Tab ─────────────────────────
    public string $activeSection = 'general';

    // ── General Settings ────────────────────────────
    public string $companyName = '';
    public string $contactEmail = '';
    public string $companyDescription = '';
    public string $timezone = 'UTC';
    public string $dateFormat = 'Y-m-d';

    // ── Notification Preferences ────────────────────
    public bool $notifySecurityAlerts = true;
    public bool $notifyBilling = true;
    public bool $notifyProductUpdates = false;
    public bool $notifyLowStock = true;

    public function mount()
    {
        $tenant = tenant();
        $user = Auth::user();

        if ($tenant) {
            $this->companyName = $tenant->name ?? '';
            $this->contactEmail = $user->email ?? '';
            $this->companyDescription = $tenant->description ?? '';
            $this->timezone = $tenant->timezone ?? 'UTC';
            $this->dateFormat = $tenant->date_format ?? 'Y-m-d';
        }
    }

    /**
     * Switch the active settings section
     */
    public function switchSection(string $section)
    {
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
        ]);

        $tenant = tenant();
        if ($tenant) {
            $tenant->update([
                'name' => $this->companyName,
                'description' => $this->companyDescription,
                'timezone' => $this->timezone,
                'date_format' => $this->dateFormat,
            ]);
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
        $tenant = tenant();
        if (!$tenant || !$tenant->plan_id) {
            return null;
        }

        return Plan::with('planFeatures')->find($tenant->plan_id);
    }

    /**
     * Get the current subscription
     */
    public function getCurrentSubscriptionProperty()
    {
        $tenant = tenant();
        if (!$tenant) {
            return null;
        }

        return Subscription::where('tenant_id', $tenant->id)
            ->where('stripe_status', 'active')
            ->latest()
            ->first();
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
        $tenant = tenant();
        return $tenant ? $tenant->hasFeature($feature) : false;
    }

    public function render()
    {
        return view('livewire.tenant.admin.dashboard', [
            'currentPlan' => $this->currentPlan,
            'currentSubscription' => $this->currentSubscription,
            'usageStats' => $this->usageStats,
            'availablePlans' => $this->availablePlans,
            'planFeatures' => $this->planFeatures,
            'allFeatures' => PlanFeature::cases(),
        ]);
    }
}
