<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PlansController;

// Subscription Routes
// Route::middleware(['auth'])->group(function () {
//     // View available plans
//     Route::get('/plans', [PlansController::class, 'index'])->name('plans.index');
    
//     // Subscription management
//     Route::prefix('subscription')->group(function () {
//         Route::get('/manage', function () {
//             return view('subscription.manage');
//         })->name('subscription.manage');
        
//         // Checkout process
//         Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])
//             ->name('subscription.checkout');
            
//         Route::post('/process', [SubscriptionController::class, 'process'])
//             ->name('subscription.process');
            
//         // Cancel subscription
//         Route::post('/cancel', [SubscriptionController::class, 'cancel'])
//             ->name('subscription.cancel');
        
//         // Resume subscription
//         Route::post('/resume', [SubscriptionController::class, 'resume'])
//             ->name('subscription.resume');
        
//         // Update payment method
//         Route::post('/update-payment', [SubscriptionController::class, 'updatePayment'])
//             ->name('subscription.update-payment');
//     });
// });