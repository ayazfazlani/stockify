<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Billing extends Component
{
    public function getStatsProperty()
    {
        $revenueStats = $this->getRevenueStats();
        $subStats = $this->getSubscriptionStats();

        return [
            'monthly_revenue' => $revenueStats['current'],
            'monthly_revenue_change' => $revenueStats['change_pct'],
            'active_subscriptions' => $subStats['current'],
            'active_subscriptions_change' => $subStats['change_pct'],
            'churn_rate' => $this->calculateChurnRate(),
            'churn_rate_change' => $this->calculateChurnChange(),
            'arpu' => $this->calculateARPU(),
            'arpu_change' => $this->calculateARPUChange(),
        ];
    }

    public function getPlansProperty()
    {
        return Plan::where('active', true)->get();
    }

    public function getInvoicesProperty()
    {
        return Payment::with(['tenant', 'subscription.plan'])
            ->latest('paid_at')
            ->get();
    }

    public function getChartDataProperty()
    {
        $labels = [];
        $revenue = [];
        $newSubs = [];
        $churn = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M');
            
            $revenue[] = Payment::whereIn('status', ['paid', 'succeeded'])
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');

            $newSubs[] = Subscription::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $churn[] = Subscription::where('stripe_status', 'canceled')
                ->whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->count();
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'new_subs' => $newSubs,
            'churn' => $churn,
        ];
    }

    public function getUsageStatsProperty()
    {
        return [
            'users_count' => User::count(),
            'tenants_count' => Tenant::count(),
            'active_subs' => Subscription::where('stripe_status', 'active')->count(),
        ];
    }

    private function getRevenueStats()
    {
        $currentMonth = Payment::whereIn('status', ['paid', 'succeeded'])
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $lastMonth = Payment::whereIn('status', ['paid', 'succeeded'])
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->whereYear('paid_at', now()->subMonth()->year)
            ->sum('amount');

        $change = $lastMonth > 0 ? (($currentMonth - $lastMonth) / $lastMonth) * 100 : 0;

        return [
            'current' => $currentMonth,
            'change_pct' => round($change, 1)
        ];
    }

    private function getSubscriptionStats()
    {
        $current = Subscription::where('stripe_status', 'active')->count();
        $lastMonth = Subscription::where('stripe_status', 'active')
            ->where('created_at', '<', now()->startOfMonth())
            ->count();

        $change = $lastMonth > 0 ? (($current - $lastMonth) / $lastMonth) * 100 : 0;

        return [
            'current' => $current,
            'change_pct' => round($change, 1)
        ];
    }

    private function calculateChurnRate()
    {
        $canceledThisMonth = Subscription::where('stripe_status', 'canceled')
            ->whereMonth('updated_at', now()->month)
            ->count();
        $activeAtStart = Subscription::where('stripe_status', 'active')
            ->where('created_at', '<', now()->startOfMonth())
            ->count();

        return $activeAtStart > 0 ? round(($canceledThisMonth / $activeAtStart) * 100, 1) : 0;
    }

    private function calculateChurnChange()
    {
        // Dummy logic for now or could be real MoM churn change
        return -0.5; 
    }

    private function calculateARPU()
    {
        $activeSubs = Subscription::where('stripe_status', 'active')->count();
        if ($activeSubs === 0) return 0;

        $monthlyRevenue = Payment::whereIn('status', ['paid', 'succeeded'])
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');

        return round($monthlyRevenue / $activeSubs, 2);
    }

    private function calculateARPUChange()
    {
        return 2.10; // Placeholder for growth calculation
    }

    public function getRecentPaymentsProperty()
    {
        return Payment::with(['tenant', 'subscription.plan'])
            ->latest('paid_at')
            ->limit(10)
            ->get();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.billing', [
            'stats' => $this->stats,
            'recentPayments' => $this->recentPayments,
            'allPlans' => $this->plans,
            'allInvoices' => $this->invoices,
            'charts' => $this->chartData,
            'usage' => $this->usageStats,
        ]);
    }
}
