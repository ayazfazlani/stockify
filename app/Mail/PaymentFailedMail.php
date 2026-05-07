<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentFailedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $payment;

    public function __construct(User $user, $payment)
    {
        $this->user = $user;
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->subject(__('mail.payment_failed'))
            ->view('emails.payment-failed')
            ->with([
                'user' => $this->user,
                'payment' => $this->payment,
            ]);
    }
}
