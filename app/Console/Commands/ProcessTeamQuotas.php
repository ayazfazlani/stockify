<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Models\StoreQuota;
use App\Notifications\QuotaResetNotification;
use Illuminate\Console\Command;

class ProcessTeamQuotas extends Command
{
    protected $signature = 'stores:process-quotas';
    protected $description = 'Process store quotas and reset usage counters for new billing cycles';

    public function handle()
    {
        // Get all active stores (stores with active subscriptions)
        $stores = Store::whereHas('subscriptions', function ($query) {
            $query->where('stripe_status', 'active');
        })->get();

        foreach ($stores as $store) {
            $quota = StoreQuota::where('store_id', $store->id)->first();
            
            if (!$quota) {
                $quota = StoreQuota::create([
                    'store_id' => $store->id,
                    'quota_name' => 'default', // Using dummy quota name for now as the logic is broad
                    'used' => 0,
                    'limit' => 0,
                    'reset_at' => now()->addMonth(),
                ]);
            }

            // Check if billing cycle has ended (reset_at)
            if ($quota->reset_at && now()->greaterThan($quota->reset_at)) {
                // Reset usage counters
                $quota->used = 0;
                
                // Update billing cycle dates
                $quota->reset_at = now()->addMonth();
                $quota->save();
                
                // Notify store owner
                if ($store->owner) {
                    $store->owner->notify(new QuotaResetNotification($store));
                }
                
                $this->info("Processed quotas for store: {$store->name}");
            }
        }

        $this->info('Store quotas processing completed.');
    }
}