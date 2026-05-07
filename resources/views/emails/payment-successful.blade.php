@component('emails.layout')
@slot('subject')
{{ __('mail.payment_successful') }}
@endslot

<div class="text-center">
    <div style="width: 64px; height: 64px; background: #dcfce7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span style="font-size: 28px;">✅</span>
    </div>
    <h2 style="color: #0f172a; font-size: 24px; margin: 0 0 8px;">{{ __('mail.payment_received') }}</h2>
    <p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">{{ __('mail.thank_you_payment') }}</p>
</div>

<div class="card">
    <div style="text-align: center; margin-bottom: 16px;">
        <p style="margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('mail.amount_paid') }}</p>
        <p style="margin: 4px 0 0; font-weight: 700; color: #16a34a; font-size: 32px;">{{ config('app.currency_symbol') }}{{ number_format($payment->amount, 2) }}</p>
    </div>

    <div class="divider"></div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div style="text-align: center;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.payment_id') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a; font-size: 12px;">{{ $payment->transaction_id ?? $payment->id }}</p>
        </div>
        <div style="text-align: center;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.date') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $payment->created_at?->toDateString() ?? now()->toDateString() }}</p>
        </div>
        <div style="text-align: center;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.method') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ ucfirst($payment->payment_method ?? 'Card') }}</p>
        </div>
        <div style="text-align: center;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.status') }}</p>
            <span class="badge badge-success">{{ ucfirst($payment->status ?? 'completed') }}</span>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/billing') }}" class="btn btn-success">
        {{ __('mail.view_receipt') }}
    </a>
</div>
@endcomponent
