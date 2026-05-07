<?php

namespace App\Mail;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $tenant;

    public $isOwner;

    public function __construct(User $user, ?Tenant $tenant = null, bool $isOwner = false)
    {
        $this->user = $user;
        $this->tenant = $tenant;
        $this->isOwner = $isOwner;
    }

    public function build()
    {
        $subject = $this->isOwner ? __('mail.new_user_joined') : __('mail.welcome_to_stockify');

        return $this->subject($subject)
            ->view('emails.user-registered')
            ->with([
                'user' => $this->user,
                'tenant' => $this->tenant,
                'isOwner' => $this->isOwner,
            ]);
    }
}
