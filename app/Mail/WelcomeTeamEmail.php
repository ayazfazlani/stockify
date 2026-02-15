<?php

namespace App\Mail;

use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeTeamEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $team;
    public $user;

    public function __construct(Team $team, User $user)
    {
        $this->team = $team;
        $this->user = $user;
    }

    public function build()
    {
        return $this->markdown('emails.welcome.team')
                    ->subject('Welcome to ' . config('app.name') . '!')
                    ->with([
                        'setupUrl' => url('/onboarding'),
                        'dashboardUrl' => url('/dashboard'),
                        'docsUrl' => url('/docs'),
                    ]);
    }
}