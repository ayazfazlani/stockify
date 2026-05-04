<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Item;
use App\Models\Transaction;
use App\Mail\StockInMail;
use App\Mail\StockOutMail;
use App\Mail\LowStockMail;
use App\Mail\PurchaseOrderMail;
use App\Mail\UserInvitationMail;
use App\Mail\SubscriptionActivatedMail;
use App\Mail\SubscriptionCancelledMail;
use App\Mail\PaymentSuccessfulMail;
use App\Mail\PaymentFailedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    public static function notifyStockIn(Transaction $transaction, array $serialNumbers = []): void
    {
        $tenant = tenant();
        if (!$tenant || !$tenant->hasFeature('low-stock-alerts')) {
            return;
        }

        $users = $tenant->users()->whereNotNull('email')->get();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->queue(new StockInMail($transaction, $serialNumbers));
            } catch (\Exception $e) {
                Log::error('Stock In email failed: ' . $e->getMessage());
            }
        }
    }

    public static function notifyStockOut(Transaction $transaction): void
    {
        $tenant = tenant();
        if (!$tenant || !$tenant->hasFeature('low-stock-alerts')) {
            return;
        }

        $users = $tenant->users()->whereNotNull('email')->get();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->queue(new StockOutMail($transaction));
            } catch (\Exception $e) {
                Log::error('Stock Out email failed: ' . $e->getMessage());
            }
        }
    }

    public static function notifyLowStock(Item $item): void
    {
        $tenant = tenant();
        if (!$tenant || !$tenant->hasFeature('low-stock-alerts')) {
            return;
        }

        $users = $tenant->users()->whereNotNull('email')->get();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->queue(new LowStockMail($item));
            } catch (\Exception $e) {
                Log::error('Low stock email failed: ' . $e->getMessage());
            }
        }
    }

    public static function notifyPurchaseOrder($purchaseOrder): void
    {
        $tenant = tenant();
        if (!$tenant)
            return;

        $users = $tenant->users()->whereNotNull('email')->get();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->queue(new PurchaseOrderMail($purchaseOrder));
            } catch (\Exception $e) {
                Log::error('Purchase order email failed: ' . $e->getMessage());
            }
        }
    }

    public static function notifyUserInvitation(User $user, string $invitationLink): void
    {
        try {
            Mail::to($user->email)->queue(new UserInvitationMail($user, $invitationLink));
        } catch (\Exception $e) {
            Log::error('Invitation email failed: ' . $e->getMessage());
        }
    }

    public static function notifySubscriptionActivated(Tenant $tenant): void
    {
        $user = $tenant->owner;
        if (!$user || !$user->email)
            return;

        try {
            Mail::to($user->email)->queue(new SubscriptionActivatedMail($tenant));
        } catch (\Exception $e) {
            Log::error('Subscription activation email failed: ' . $e->getMessage());
        }
    }

    public static function notifySubscriptionCancelled(Tenant $tenant): void
    {
        $user = $tenant->owner;
        if (!$user || !$user->email)
            return;

        try {
            Mail::to($user->email)->queue(new SubscriptionCancelledMail($tenant));
        } catch (\Exception $e) {
            Log::error('Subscription cancellation email failed: ' . $e->getMessage());
        }
    }

    public static function notifyPaymentSuccess($payment): void
    {
        $tenant = $payment->tenant;
        if (!$tenant)
            return;

        $user = $tenant->owner;
        if (!$user || !$user->email)
            return;

        try {
            Mail::to($user->email)->queue(new PaymentSuccessfulMail($payment));
        } catch (\Exception $e) {
            Log::error('Payment success email failed: ' . $e->getMessage());
        }
    }

    public static function notifyPaymentFailed($payment): void
    {
        $tenant = $payment->tenant;
        if (!$tenant)
            return;

        $user = $tenant->owner;
        if (!$user || !$user->email)
            return;

        try {
            Mail::to($user->email)->queue(new PaymentFailedMail($payment));
        } catch (\Exception $e) {
            Log::error('Payment failed email failed: ' . $e->getMessage());
        }
    }
}