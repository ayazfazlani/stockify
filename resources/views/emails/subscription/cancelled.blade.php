@component('emails.layout')
@slot('subject')
{{ __('mail.subscription_cancelled') }}
@endslot

<div class="text-center">
    <div style="width: 64px; height: 64px; background: #fee2e2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span style="font-size: 28px;">😔</span>
    </div>
    <h2 style="color: #0f172a; font-size: 24px; margin: 0 0 8px;">{{ __('mail.subscription_ended') }}</h2>
    <p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">{{ __('mail.cancelled_desc') }}</p>
</div>

<div class="card">
    <div style="text-align: center;">
        <p style="margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('mail.plan') }}</p>
        <p style="margin: 4px 0 0; font-weight: 700; color: #0f172a; font-size: 20px;">{{ $subscription->plan?->name ?? 'Premium' }}</p>
    </div>

    <div class="divider"></div>

    <div style="text-align: center;">
        <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.access_until') }}</p>
        <p style="margin: 4px 0 0; font-weight: 600; color: #dc2626; font-size: 18px;">{{ $subscription->ends_at?->toDateString() ?? 'End of billing period' }}</p>
    </div>
</div>

<div style="background: #fef3c7; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #92400e; font-size: 13px; text-align: center;">
        <strong>{{ __('mail.changed_mind') }}</strong> {{ __('mail.reactivate_anytime') }}
    </p>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/subscription') }}" class="btn btn-outline">
        {{ __('mail.reactivate') }}
    </a>
</div>
@endcomponent
