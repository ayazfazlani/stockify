<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ItemUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $item;

    public $changes;

    public function __construct(User $user, $item, array $changes)
    {
        $this->user = $user;
        $this->item = $item;
        $this->changes = $changes;
    }

    public function build()
    {
        return $this->subject(__('mail.item_updated'))
            ->view('emails.item-updated')
            ->with([
                'user' => $this->user,
                'item' => $this->item,
                'changes' => $this->changes,
            ]);
    }
}
