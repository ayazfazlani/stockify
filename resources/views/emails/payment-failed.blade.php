@component('emails.layout')
@slot('subject')
{{ __('mail.payment_failed') }}
@endslot

<div class="text-center">
    <div style="width: 64px; height: 64px; background: #fee2e2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span style="font-size: 28px;">❌</span>
    </div>
    <h2 style="color: #0f172a; font-size: 24px; margin: 0 0 8px;">{{ __('mail.payment_unsuccessful') }}</h2>
    <p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">{{ __('mail.payment_failed_desc') }}</p>
</div>

<div class="card">
    <div style="text-align: center; margin-bottom: 16px;">
        <p style="margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('mail.amount_due') }}</p>
        <p style="margin: 4px 0 0; font-weight: 700; color: #dc2626; font-size: 32px;">{{ config('app.currency_symbol') }}{{ number_format($payment->amount, 2) }}</p>
    </div>

    <div class="divider"></div>

    <div style="background: #fef2f2; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
        <p style="margin: 0; color: #991b1b; font-size: 13px; text-align: center;">
            <strong>{{ __('mail.reason') }}:</strong> {{ $payment->failure_reason ?? __('mail.card_declined') }}
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div style="text-align: center;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.payment_id') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a; font-size: 12px;">{{ $payment->transaction_id ?? $payment->id }}</p>
        </div>
        <div style="text-align: center;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.date') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $payment->created_at?->toDateString() ?? now()->toDateString() }}</p>
        </div>
    </div>
</div>

<div style="background: #fef3c7; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #92400e; font-size: 13px; text-align: center;">
        <strong>{{ __('mail.urgent') }}</strong> {{ __('mail.update_payment_method') }}
    </p>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/billing') }}" class="btn btn-danger">
        {{ __('mail.update_payment') }}
    </a>
</div>
@endcomponent
