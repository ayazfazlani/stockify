<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCancelledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $subscription;

    public function __construct(User $user, $subscription)
    {
        $this->user = $user;
        $this->subscription = $subscription;
    }

    public function build()
    {
        return $this->subject(__('mail.subscription_cancelled'))
            ->view('emails.subscription.cancelled')
            ->with([
                'user' => $this->user,
                'subscription' => $this->subscription,
            ]);
    }
}
