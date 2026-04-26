<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Observers\ItemMetricsObserver;
use App\Observers\TransactionObserver;
use App\Services\FeatureService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Invoice;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register application services if needed
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using Gate::before or can() check
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super admin') ? true : null;
        });
        // Register @feature Blade directive
        // Usage: @feature('analytics') ... @else ... @endfeature
        \Illuminate\Support\Facades\Blade::if('feature', function (string $feature) {
            $tenant = tenant();
            if (! $tenant) {
                return false;
            }

            // Super admin bypass
            $user = Auth::user();
            if ($user && $user->isSuperAdmin()) {
                return true;
            }

            return $tenant->hasFeature($feature);
        });

        Subscription::addGlobalScope('tenant', fn ($q) => $q->where('tenant_id', tenant('id')));
        // Invoice::addGlobalScope('tenant', fn ($q) => $q->where('tenant_id', tenant('id')));
        Cashier::useCustomerModel(Tenant::class);
        Cashier::useSubscriptionModel(\App\Models\Subscription::class);

        // Register observers for metrics tracking
        Transaction::observe(TransactionObserver::class);
        Item::observe(ItemMetricsObserver::class);
    }
}
