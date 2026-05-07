<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $purchaseOrder;

    public $type;

    public function __construct($purchaseOrder, string $type = 'created')
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type === 'supplier'
            ? __('mail.new_purchase_order_supplier')
            : __('mail.purchase_order_updated');

        return $this->subject($subject)
            ->view('emails.purchase-order')
            ->with([
                'purchaseOrder' => $this->purchaseOrder,
                'type' => $this->type,
            ]);
    }
}
