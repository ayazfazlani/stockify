<?php

namespace App\Mail;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInvitationAcceptedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $inviter;

    public $newMember;

    public $tenant;

    public function __construct(User $inviter, User $newMember, Tenant $tenant)
    {
        $this->inviter = $inviter;
        $this->newMember = $newMember;
        $this->tenant = $tenant;
    }

    public function build()
    {
        return $this->subject(__('mail.invitation_accepted'))
            ->view('emails.invitation-accepted')
            ->with([
                'inviter' => $this->inviter,
                'newMember' => $this->newMember,
                'tenant' => $this->tenant,
            ]);
    }
}
