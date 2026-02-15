<?php

namespace App\Mail;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewalNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $team;
    public $isTrialEnding;

    public function __construct(Team $team, bool $isTrialEnding = false)
    {
        $this->team = $team;
        $this->isTrialEnding = $isTrialEnding;
    }

    public function build()
    {
        return $this->markdown('emails.subscription.renewal-notice')
            ->subject($this->isTrialEnding ? 'Trial Period Ending Soon' : 'Subscription Renewal Notice');
    }
}