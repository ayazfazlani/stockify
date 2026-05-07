@component('emails.layout')
@slot('subject')
{{ __('mail.password_reset') }}
@endslot

<div class="text-center">
    <div style="width: 64px; height: 64px; background: #eff6ff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span style="font-size: 28px;">🔐</span>
    </div>
    <h2 style="color: #0f172a; font-size: 24px; margin: 0 0 8px;">{{ __('mail.reset_password') }}</h2>
    <p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">{{ __('mail.reset_desc') }}</p>
</div>

<div class="text-center mt-4">
    <a href="{{ $resetUrl }}" class="btn">
        {{ __('mail.reset_password_btn') }}
    </a>
</div>

<div style="background: #f8fafc; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #64748b; font-size: 12px; text-align: center;">
        {{ __('mail.link_expires') }} {{ __('mail.if_not_you') }}
    </p>
</div>

<p style="color: #64748b; font-size: 12px; text-align: center; word-break: break-all;">
    {{ __('mail.cant_click') }}<br>
    <a href="{{ $resetUrl }}" style="color: #2563eb;">{{ $resetUrl }}</a>
</p>
@endcomponent
