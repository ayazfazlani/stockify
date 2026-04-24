<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];

    public array $revenueSeries = [];

    public function mount(): void
    {
        $totalUsers = User::count();
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_active', true)->count();
        $blockedTenants = max(0, $totalTenants - $activeTenants);

        $activeSubscriptions = Subscription::whereIn('stripe_status', ['active', 'trialing', 'past_due'])->count();
        $monthlyRevenueCents = (int) Plan::query()
            ->whereHas('subscriptions', function ($query) {
                $query->whereIn('stripe_status', ['active', 'trialing', 'past_due']);
            })
            ->sum('amount');
        $churnBase = max(1, $totalTenants);
        $churnRate = round(($blockedTenants / $churnBase) * 100, 1);

        $this->stats = [
            'total_users' => $totalUsers,
            'active_subscriptions' => $activeSubscriptions,
            'monthly_revenue' => $monthlyRevenueCents / 100,
            'churn_rate' => $churnRate,
            'active_tenants' => $activeTenants,
            'blocked_tenants' => $blockedTenants,
        ];

        $this->revenueSeries = $this->buildMonthlyRevenueSeries();
    }

    protected function buildMonthlyRevenueSeries(): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $labels[] = $month->format('M');

            $monthActiveSubs = Subscription::whereIn('stripe_status', ['active', 'trialing', 'past_due'])
                ->where('created_at', '<=', $month->copy()->endOfMonth())
                ->count();
            $avgPlanCents = (int) Plan::avg('amount');
            $data[] = round(($monthActiveSubs * $avgPlanCents) / 100, 2);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function getRecentTenantsProperty()
    {
        return Tenant::with('owner')
            ->latest()
            ->limit(8)
            ->get();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'recentTenants' => $this->recentTenants,
        ]);
    }
}
