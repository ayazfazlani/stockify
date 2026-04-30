<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\RobotsTxtController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Livewire\Admin\Analytics;
use App\Livewire\Admin\Billing;
use App\Livewire\Admin\Blog\Blog;
use App\Livewire\Admin\Blog\Category;
use App\Livewire\Admin\Blog\Show;
use App\Livewire\Admin\BlogCategoryManager;
use App\Livewire\Admin\BlogManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\PageManager;
use App\Livewire\Admin\SeoManager;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Support;
use App\Livewire\Admin\Tenants;
use App\Livewire\Admin\Users;
use App\Livewire\Auth\FindStore;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\InviteUser;
use App\Livewire\Marketplace\Checkout;
use App\Livewire\Marketplace\MyOrders;
use App\Livewire\Marketplace\ProductDetails;
use App\Livewire\Marketplace\Search;
use App\Livewire\SuperAdmin\PricingPlans;
use App\Livewire\SuperAdmin\SubscriptionManager;
use App\Livewire\Tenant\Register;
use App\Livewire\Web\Home;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

// ---------------- Super Admin Routes ----------------
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', App\Livewire\SuperAdmin\Auth\Login::class)->name('login');
        Route::get('/register', App\Livewire\SuperAdmin\Auth\Register::class)->name('register');
        Route::get('/forgot-password', App\Livewire\SuperAdmin\Auth\ForgotPassword::class)->name('forgot-password');
        Route::get('/reset-password', App\Livewire\SuperAdmin\Auth\ResetPassword::class)->name('password.reset');

        // Google OAuth Routes for Super Admin
        Route::get('/auth/google/redirect', function () {
            return Socialite::driver('google')->redirect();
        })->name('google.redirect');

        Route::get('/auth/google/callback', function () {
            try {
                /** @var AbstractProvider $driver */
                $driver = Socialite::driver('google');
                $googleUser = $driver->stateless()->user();

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

            } catch (Exception $e) {
                Log::error('Google OAuth Error: '.$e->getMessage());

                return redirect()->route('super-admin.login')
                    ->with('error', 'Google authentication failed. Please try again.');
            }
        })->name('google.callback');
    });

    Route::middleware(['auth', 'super-admin'])->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('subscriptionManager', SubscriptionManager::class)->name('subscription-manager');
        Route::get('/users', Users::class)->name('users');
        Route::get('/tenants', Tenants::class)->name('tenants');
        Route::get('/analytics', Analytics::class)->name('analytics');
        Route::get('/settings', Settings::class)->name('settings');
        Route::get('/billing', Billing::class)->name('billing');
        Route::get('/plans', PricingPlans::class)->name('plans');
        Route::get('/support', Support::class)->name('support');

        // CMS Routes
        Route::get('/pages', PageManager::class)->name('pages');
        Route::get('/blog', BlogManager::class)->name('blog');
        Route::get('/blog-categories', BlogCategoryManager::class)->name('blog-categories');
        Route::get('/seo', SeoManager::class)->name('seo');

        Route::get('/pricing-plans', PricingPlans::class)->name('pricing-plans');
        // Invoice Downloads
        Route::get('/invoices/{payment}/download', [InvoiceController::class, 'download'])->name('invoices.download');

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
Route::get('/', Home::class)->name('home');

// ---------------- Marketplace Routes ----------------
Route::get('/marketplace', App\Livewire\Marketplace\Home::class)->name('marketplace.index');
Route::get('/marketplace/search', Search::class)->name('marketplace.search');
Route::get('/marketplace/category/{category}', Search::class)->name('marketplace.category');
Route::get('/marketplace/product/{item}', ProductDetails::class)->name('marketplace.product');
Route::get('/marketplace/checkout', Checkout::class)->name('marketplace.checkout');
Route::get('/marketplace/my-orders', MyOrders::class)->name('marketplace.my-orders');
Route::get('/marketplace/store/{slug}', Search::class)->name('marketplace.store'); // We'll update this later to a specific StoreView component

Route::get('/invite', InviteUser::class)->name('invite');
Route::get('/login', Login::class)->name('login');
Route::get('/register', App\Livewire\Auth\Register::class)->name('register');

Route::get('/forgot-password', ForgotPassword::class)
    ->name('password.request')
    ->middleware('guest');

Route::get('/reset-password/{token}', ResetPassword::class)
    ->name('password.reset')
    ->middleware('guest');

Route::get('/find-store', FindStore::class)
    ->name('find-store')
    ->middleware('guest');

// Regular user Google OAuth routes
Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('super-admin.google.redirect');

Route::get('tenant-register', Register::class)->name('tenant.register.post');

Route::get('tenant-register', Register::class)->name('tenant.register.post');

// redirect to the subdomain or domain based rote

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('cashier.webhook');

// ---------------- SEO & CMS Public Routes ----------------
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [RobotsTxtController::class, 'index'])->name('robots');

// Blog
Route::get('/blog', Blog::class)->name('blog.index');
Route::get('/blog/category/{slug}', Category::class)->name('blog.category');
Route::get('/blog/{slug}', Show::class)->name('blog.show');

// CMS Pages (catch-all — MUST be last)
Route::get('/{slug}', [CmsController::class, 'cmsPage'])->name('cms.page')->where('slug', '^(?!super-admin|login|register|invite|find-store|tenant-register|forgot-password|reset-password|checkout|auth|stripe).*$');
