<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockInConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $items;

    public $transactionData;

    public $isTeamMember;

    public function __construct(User $user, array $items, array $transactionData, bool $isTeamMember = false)
    {
        $this->user = $user;
        $this->items = $items;
        $this->transactionData = $transactionData;
        $this->isTeamMember = $isTeamMember;
    }

    public function build()
    {
        return $this->subject(__('mail.stock_in_subject'))
            ->view('emails.stock-in-confirmation')
            ->with([
                'user' => $this->user,
                'items' => $this->items,
                'transactionData' => $this->transactionData,
                'isTeamMember' => $this->isTeamMember,
            ]);
    }
}
