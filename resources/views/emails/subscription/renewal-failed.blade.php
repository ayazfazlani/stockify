@component('mail::message')
# Subscription Renewal Failed

Dear {{ $team->owner->name }},

We were unable to process your subscription renewal payment for {{ config('app.name') }}.

**Error Details:**
- Amount: ${{ number_format($payment->amount() / 100, 2) }}
- Date: {{ $payment->created_at->format('F j, Y') }}

To ensure uninterrupted service, please update your payment information by clicking the button below:

@component('mail::button', ['url' => route('cashier.payment', [$payment->id])])
Update Payment Method
@endcomponent

Your subscription will remain active during a grace period of 7 days. After this period, your service may be suspended
if payment is not received.

If you need assistance, please don't hesitate to contact our support team.

Thank you for your prompt attention to this matter.

Best regards,<br>
{{ config('app.name') }}
@endcomponent