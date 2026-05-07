<?php

namespace App\Mail;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Payment;

class SubscriptionRenewalFailed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $team;

    public $payment;

    public function __construct(Team $team, Payment $payment)
    {
        $this->team = $team;
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->markdown('emails.subscription.renewal-failed')
            ->subject('Subscription Renewal Failed');
    }
}
