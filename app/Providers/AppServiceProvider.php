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
        // Register @feature Blade directive
        \Illuminate\Support\Facades\Blade::if('feature', function ($feature) {
            $user = Auth::user();
            if (! $user) {
                return false;
            }

            return app(FeatureService::class)->teamHasFeature($user->currentTeam, $feature);
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
