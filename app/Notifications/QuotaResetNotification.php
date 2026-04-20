<?php

namespace App\Notifications;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotaResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Usage Quotas Reset for ' . $this->store->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your store\'s usage quotas have been reset for the new billing cycle.')
            ->action('View Dashboard', url('/dashboard'))
            ->line('Thank you for using our application!');
    }
}