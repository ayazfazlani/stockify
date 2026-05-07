@component('emails.layout')
@slot('subject')
{{ $isOwner ? __('mail.new_user_joined') : __('mail.welcome_to_stockify') }}
@endslot

@if($isOwner)
<h2 style="color: #0f172a; font-size: 22px; margin: 0 0 8px;">{{ __('mail.new_team_member') }}</h2>
<p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">
    {{ __('mail.user_joined_desc', ['name' => $user->name, 'tenant' => $tenant?->name ?? 'your team']) }}
</p>

<div class="card">
    <div class="item-row" style="border-bottom: none;">
        <div class="item-img">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff" alt="">
        </div>
        <div style="flex: 1;">
            <p style="margin: 0; font-weight: 600; color: #0f172a; font-size: 16px;">{{ $user->name }}</p>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">{{ $user->email }}</p>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/team') }}" class="btn">
        {{ __('mail.manage_team') }}
    </a>
</div>
@else
<div class="text-center">
    <div style="width: 64px; height: 64px; background: #eff6ff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span style="font-size: 28px;">👋</span>
    </div>
    <h2 style="color: #0f172a; font-size: 24px; margin: 0 0 8px;">{{ __('mail.welcome_aboard') }}</h2>
    <p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">
        {{ __('mail.welcome_desc', ['tenant' => $tenant?->name ?? 'Stockify']) }}
    </p>
</div>

<div class="card">
    <div style="text-align: center;">
        <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.your_login') }}</p>
        <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a; font-size: 16px;">{{ $user->email }}</p>
    </div>
</div>

<div style="background: #eff6ff; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #1e40af; font-size: 13px; text-align: center;">
        {{ __('mail.get_started_guide') }}
    </p>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/dashboard') }}" class="btn">
        {{ __('mail.go_to_dashboard') }}
    </a>
</div>
@endif
@endcomponent
