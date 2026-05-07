<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpenseLoggedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $expense;

    public $isAdmin;

    public function __construct(User $user, $expense, bool $isAdmin = false)
    {
        $this->user = $user;
        $this->expense = $expense;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->subject($this->isAdmin ? __('mail.new_expense_logged') : __('mail.expense_confirmation'))
            ->view('emails.expense-logged')
            ->with([
                'user' => $this->user,
                'expense' => $this->expense,
                'isAdmin' => $this->isAdmin,
            ]);
    }
}
