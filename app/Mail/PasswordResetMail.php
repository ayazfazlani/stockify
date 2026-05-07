<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $token;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject(__('mail.password_reset'))
            ->view('emails.password-reset')
            ->with([
                'user' => $this->user,
                'token' => $this->token,
                'resetUrl' => url('/reset-password?token='.$this->token.'&email='.urlencode($this->user->email)),
            ]);
    }
}
