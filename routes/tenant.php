<?php

declare(strict_types=1);

use App\Http\Controllers\PlansController;
use App\Http\Controllers\SubscriptionController;
use App\Livewire\Analytic;
use App\Livewire\Auth\Login;
use App\Livewire\ItemList;
use App\Livewire\StockInComponent;
use App\Livewire\StockOutComponent;
use App\Livewire\SubscriptionManagement;
use App\Livewire\TeamManagement;
use App\Livewire\Transactions;
use App\Livewire\UserManagement;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

// use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::prefix('{tenant}')->middleware([
    InitializeTenancyByPath::class,
])->group(function () {
    Route::post('stripe/webhook', 'App\Http\Controllers\StripeWebhookController@handleWebhook')
        ->name('tenant.cashier.webhook');
});

Route::prefix('{tenant}')->name('tenant.')->middleware([
    'web',
    InitializeTenancyByPath::class,
    \App\Http\Middleware\CheckTenantAccess::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    // / ---------------- Authenticated Routes ----------------
    Route::get('/login', Login::class)->name('login');

    Route::middleware(['auth'])->group(function () {
        // Your existing authenticated routes...
        Route::get('/home', ItemList::class)->name('home');
        Route::get('/itemlist', ItemList::class)->name('items');
        Route::get('/stockin', StockInComponent::class)->name('stock-in');
        Route::get('/stockout', StockOutComponent::class)->name('stock-out');
        Route::get('/transactions', Transactions::class)->name('transactions');
        Route::get('/analytics', Analytic::class)->name('analytics');
        Route::get('/user', UserManagement::class)->name('user');
        Route::get('/admin', TeamManagement::class)->name('admin');
        Route::get('/plans', [PlansController::class, 'index'])->name('plans.index');
        Route::get('/plans/{plan}', [PlansController::class, 'show'])->name('plans.show');

        // Route::stripeWebhooks('stripe/webhook');   // ← this is the Cashier default
        // // Subscriptions
        Route::prefix('subscription')->group(function () {
            Route::get('/manage', SubscriptionManagement::class)->name('subscription.manage');
            Route::get('/', [SubscriptionController::class, 'index'])->name('subscription.index');
            Route::get('/show', [SubscriptionController::class, 'show'])->name('subscription.show');
            Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
            Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.process');
            Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
            Route::post('/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
            Route::get('/success', [SubscriptionController::class, 'success'])->name('subscription.success');

        });

    });

});
