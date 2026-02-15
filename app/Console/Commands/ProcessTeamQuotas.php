<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Models\TeamQuota;
use App\Notifications\QuotaResetNotification;
use Illuminate\Console\Command;

class ProcessTeamQuotas extends Command
{
    protected $signature = 'teams:process-quotas';
    protected $description = 'Process team quotas and reset usage counters for new billing cycles';

    public function handle()
    {
        $teams = Team::whereHas('subscription', function ($query) {
            $query->where('stripe_status', 'active');
        })->get();

        foreach ($teams as $team) {
            $quota = $team->quota;
            
            if (!$quota) {
                $quota = TeamQuota::create([
                    'team_id' => $team->id,
                    'billing_cycle_start' => now(),
                    'billing_cycle_end' => now()->addMonth(),
                ]);
            }

            // Check if billing cycle has ended
            if (now()->greaterThan($quota->billing_cycle_end)) {
                // Reset usage counters
                $quota->resetUsage();
                
                // Update billing cycle dates
                $quota->updateBillingCycle();
                
                // Notify team owner
                $team->owner->notify(new QuotaResetNotification($team));
                
                $this->info("Processed quotas for team: {$team->name}");
            }
        }

        $this->info('Team quotas processing completed.');
    }
}