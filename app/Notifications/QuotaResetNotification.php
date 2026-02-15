<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotaResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Usage Quotas Reset for ' . $this->team->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your team\'s usage quotas have been reset for the new billing cycle.')
            ->line('Here are your current limits:')
            ->line('- Items: ' . $this->team->quota->items_limit)
            ->line('- Storage: ' . $this->team->quota->storage_limit . 'MB')
            ->line('- Users: ' . $this->team->quota->users_limit)
            ->action('View Dashboard', url('/dashboard'))
            ->line('Thank you for using our application!');
    }
}