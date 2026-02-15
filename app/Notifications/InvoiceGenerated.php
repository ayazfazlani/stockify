<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Cashier\Invoice;

class InvoiceGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = route('subscription.manage') . '#invoices';

        return (new MailMessage)
            ->subject('New Invoice Generated')
            ->greeting('Hello ' . $notifiable->name)
            ->line('A new invoice has been generated for your subscription.')
            ->line('Amount: $' . number_format($this->invoice->total() / 100, 2))
            ->line('Invoice Date: ' . $this->invoice->date()->format('F j, Y'))
            ->action('View Invoice', $url)
            ->line('Thank you for your business!');
    }

    public function toArray($notifiable)
    {
        return [
            'invoice_id' => $this->invoice->id,
            'amount' => $this->invoice->total(),
            'date' => $this->invoice->date()->toIso8601String(),
        ];
    }
}