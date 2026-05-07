<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockOutConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $items;

    public $transactionData;

    public function __construct(User $user, array $items, array $transactionData)
    {
        $this->user = $user;
        $this->items = $items;
        $this->transactionData = $transactionData;
    }

    public function build()
    {
        return $this->subject(__('mail.stock_out_subject'))
            ->view('emails.stock-out-confirmation')
            ->with([
                'user' => $this->user,
                'items' => $this->items,
                'transactionData' => $this->transactionData,
            ]);
    }
}
