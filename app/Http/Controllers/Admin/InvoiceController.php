<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Download a PDF invoice for a specific payment.
     */
    public function download(string $paymentId)
    {
        $payment = Payment::with(['tenant', 'subscription'])->findOrFail($paymentId);
        $user = Auth::user();

        // Security check: Only super admin or the tenant owner can download the invoice
        if (!$user->isSuperAdmin() && $payment->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $data = [
            'payment' => $payment,
            'tenant' => $payment->tenant,
            'subscription' => $payment->subscription,
            'invoice_number' => 'INV-' . strtoupper(substr($payment->stripe_invoice_id, -8)),
            'date' => $payment->paid_at->format('M d, Y'),
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);

        return $pdf->download($data['invoice_number'] . '.pdf');
    }
}
