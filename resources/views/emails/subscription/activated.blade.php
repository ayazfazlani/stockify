@component('emails.layout')
@slot('subject')
{{ __('mail.subscription_activated') }}
@endslot

<div class="text-center">
    <div style="width: 64px; height: 64px; background: #dcfce7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span style="font-size: 28px;">🎉</span>
    </div>
    <h2 style="color: #0f172a; font-size: 24px; margin: 0 0 8px;">{{ __('mail.welcome_premium') }}</h2>
    <p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">{{ __('mail.subscription_active_desc') }}</p>
</div>

<div class="card">
    <div style="text-align: center; margin-bottom: 16px;">
        <p style="margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('mail.plan') }}</p>
        <p style="margin: 4px 0 0; font-weight: 700; color: #0f172a; font-size: 20px;">{{ $subscription->plan?->name ?? 'Premium' }}</p>
    </div>

    <div class="divider"></div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div style="text-align: center;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.billing_cycle') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ ucfirst($subscription->billing_cycle ?? 'monthly') }}</p>
        </div>
        <div style="text-align: center;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.next_billing') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $subscription->next_billing_date?->toDateString() ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<div style="background: #eff6ff; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #1e40af; font-size: 13px; text-align: center;">
        <strong>{{ __('mail.receipt_sent') }}</strong> {{ __('mail.check_inbox') }}
    </p>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/subscription') }}" class="btn btn-success">
        {{ __('mail.manage_subscription') }}
    </a>
</div>
@endcomponent
