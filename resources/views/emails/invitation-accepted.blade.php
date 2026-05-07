@component('emails.layout')
@slot('subject')
{{ __('mail.invitation_accepted') }}
@endslot

<div class="text-center">
    <div style="width: 64px; height: 64px; background: #dcfce7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span style="font-size: 28px;">🎉</span>
    </div>
    <h2 style="color: #0f172a; font-size: 24px; margin: 0 0 8px;">{{ __('mail.they_joined') }}</h2>
    <p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">
        {{ __('mail.invite_accepted_desc', ['name' => $newMember->name, 'tenant' => $tenant->name]) }}
    </p>
</div>

<div class="card">
    <div class="item-row" style="border-bottom: none;">
        <div class="item-img">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($newMember->name) }}&background=16a34a&color=fff" alt="">
        </div>
        <div style="flex: 1;">
            <p style="margin: 0; font-weight: 600; color: #0f172a; font-size: 16px;">{{ $newMember->name }}</p>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">{{ $newMember->email }}</p>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/team') }}" class="btn btn-success">
        {{ __('mail.view_team') }}
    </a>
</div>
@endcomponent
