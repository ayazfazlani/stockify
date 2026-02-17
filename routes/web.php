<?php

use App\Http\Controllers\PlansController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SubscriptionController;
use App\Livewire\Admin\Analytics;
use App\Livewire\Admin\Billing;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Support;
use App\Livewire\Admin\Tenants;
use App\Livewire\Admin\Users;
use App\Livewire\Analytic;
use App\Livewire\Auth\Login;
use App\Livewire\InviteUser;
use App\Livewire\ItemList;
use App\Livewire\ManageRoles;
use App\Livewire\StockInComponent;
use App\Livewire\StockOutComponent;
use App\Livewire\SubscriptionManagement;
use App\Livewire\SuperAdmin\PricingPlans;
use App\Livewire\TeamManagement;
use App\Livewire\Transactions;
use App\Livewire\UserManagement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

// ---------------- Super Admin Routes ----------------
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', \App\Livewire\SuperAdmin\Auth\Login::class)->name('login');
        Route::get('/register', \App\Livewire\SuperAdmin\Auth\Register::class)->name('register');
        Route::get('/forgot-password', \App\Livewire\SuperAdmin\Auth\ForgotPassword::class)->name('forgot-password');
        Route::get('/reset-password', \App\Livewire\SuperAdmin\Auth\ResetPassword::class)->name('password.reset');

        // Google OAuth Routes for Super Admin
        Route::get('/auth/google/redirect', function () {
            return Socialite::driver('google')->redirect();
        })->name('google.redirect');

        Route::get('/auth/google/callback', function () {
            try {
                $googleUser = Socialite::driver('google')->stateless()->user();

                // Find or create super admin user
                $user = User::where('email', $googleUser->getEmail())->first();

                if (! $user) {
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'is_super_admin' => true,
                        'email_verified_at' => now(),
                    ]);
                } else {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'is_super_admin' => true,
                    ]);
                }

                Auth::login($user, true);

                return redirect()->route('super-admin.dashboard');

            } catch (\Exception $e) {
                \Log::error('Google OAuth Error: '.$e->getMessage());

                return redirect()->route('super-admin.login')
                    ->with('error', 'Google authentication failed. Please try again.');
            }
        })->name('google.callback');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('subscriptionManager', \App\Livewire\SuperAdmin\SubscriptionManager::class)->name('subscription-manager');
        Route::get('/users', Users::class)->name('users');
        Route::get('/tenants', Tenants::class)->name('tenants');
        Route::get('/analytics', Analytics::class)->name('analytics');
        Route::get('/settings', Settings::class)->name('settings');
        Route::get('/billing', Billing::class)->name('billing');
        Route::get('/plans', PricingPlans::class)->name('plans');
        Route::get('/support', Support::class)->name('support');

        Route::get('/pricing-plans', PricingPlans::class)->name('support');
        Route::post('/logout', function () {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('super-admin.login');
        })->name('logout');
    });
});

Route::get('/checkout/success', function () {
    // You can retrieve session here if needed
    // $session = \Stripe\Checkout\Session::retrieve(request('session_id'));
    return view('subscription.success');
})->name('checkout.success');

Route::get('/checkout/cancel', function () {
    return view('subscription.cancel');
})->name('checkout.cancel');
// ---------------- Public Routes ----------------
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/invite', InviteUser::class)->name('invite');
Route::get('/login', Login::class)->name('login');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/forgot-password', App\Livewire\Auth\ForgotPassword::class)
    ->name('password.request')
    ->middleware('guest');

Route::get('/reset-password/{token}', App\Livewire\Auth\ResetPassword::class)
    ->name('password.reset')
    ->middleware('guest');

// Regular user Google OAuth routes
Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('super-admin.google.redirect');

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Find or create super admin user
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(24)), // Add random password
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'is_super_admin' => true,
                'email_verified_at' => now(),
            ]);
        } else {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'is_super_admin' => true,
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('super-admin.dashboard');

    } catch (\Exception $e) {
        \Log::error('Google OAuth Error: '.$e->getMessage());

        return redirect()->route('super-admin.login')
            ->with('error', 'Google authentication failed. Please try again.');
    }
})->name('google.callback');

// ---------------- Authenticated Routes ----------------
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

    // Subscriptions
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

    // Stripe Webhook (central endpoint for Stripe CLI & webhook forwarding)
    // Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])
    //     ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    //     ->name('cashier.webhook');
    Route::get('/roles/{userId}', ManageRoles::class)->name('manage-roles');
});

Route::get('tenant-register', \App\Livewire\Tenant\Register::class)->name('tenant.register.post');

// redirect to the subdomain or domain based rote

// Central Stripe webhook (public endpoint for Stripe CLI and forwarded webhooks)
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class, \App\Http\Middleware\Authenticate::class])
    ->name('cashier.webhook');
