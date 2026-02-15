<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Mail\SubscriptionRenewalNotice;
use App\Mail\SubscriptionRenewalFailed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Exceptions\IncompletePayment;

class ProcessSubscriptionRenewals extends Command
{
    protected $signature = 'subscriptions:process-renewals';
    protected $description = 'Process subscription renewals and send notifications';

    public function handle()
    {
        $teams = Team::whereNotNull('stripe_id')
            ->where('subscription_plan', '!=', null)
            ->get();

        foreach ($teams as $team) {
            $subscription = $team->subscription('default');
            
            if (!$subscription) {
                continue;
            }

            // Check if subscription is due for renewal in the next 3 days
            if ($subscription->onTrial()) {
                $this->handleTrialEnding($team);
                continue;
            }

            // Handle renewal
            if ($subscription->recurring() && $subscription->onGracePeriod()) {
                $this->handleRenewal($team);
            }
        }

        $this->info('Subscription renewals processed successfully.');
    }

    protected function handleTrialEnding(Team $team)
    {
        $subscription = $team->subscription('default');
        
        // Send trial ending notification 3 days before
        if ($subscription->trial_ends_at->subDays(3)->isToday()) {
            Mail::to($team->owner->email)->send(new SubscriptionRenewalNotice($team, true));
        }
    }

    protected function handleRenewal(Team $team)
    {
        try {
            // Attempt to charge the customer
            $team->invoice();

            // Send successful renewal notification
            Mail::to($team->owner->email)->send(new SubscriptionRenewalNotice($team));
        } catch (IncompletePayment $exception) {
            // Handle failed payment
            Mail::to($team->owner->email)->send(new SubscriptionRenewalFailed($team, $exception->payment));
        }
    }
}