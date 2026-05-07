@component('emails.layout')
@slot('subject')
{{ __('mail.stock_in_subject') }}
@endslot

<h2 style="color: #0f172a; font-size: 22px; margin: 0 0 8px;">
    {{ $isTeamMember ? __('mail.stock_in_team_title') : __('mail.stock_in_title') }}
</h2>
<p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">
    {{ $isTeamMember ? __('mail.stock_in_team_desc', ['name' => $user->name]) : __('mail.stock_in_desc') }}
</p>

<div class="card">
    <div class="stat-grid">
        <div class="stat-cell">
            <div class="stat-value" style="color: #16a34a;">+{{ array_sum(array_column($items, 'quantity')) }}</div>
            <div class="stat-label">{{ __('mail.total_qty') }}</div>
        </div>
        <div class="stat-cell">
            <div class="stat-value">{{ count($items) }}</div>
            <div class="stat-label">{{ __('mail.items') }}</div>
        </div>
        <div class="stat-cell">
            <div class="stat-value">{{ config('app.currency_symbol') }}{{ number_format($transactionData['total_value'] ?? 0, 2) }}</div>
            <div class="stat-label">{{ __('mail.total_value') }}</div>
        </div>
    </div>
</div>

<div class="divider"></div>

<h3 style="color: #0f172a; font-size: 16px; margin: 0 0 16px;">{{ __('mail.items_received') }}</h3>

@foreach($items as $item)
<div class="item-row">
    <div class="item-img">
        @if($item['image'])
        <img src="{{ $item['image'] }}" alt="">
        @else
        <span style="color: #94a3b8; font-size: 18px;">📦</span>
        @endif
    </div>
    <div style="flex: 1;">
        <p style="margin: 0; font-weight: 600; color: #0f172a; font-size: 14px;">{{ $item['name'] }}</p>
        <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">{{ $item['sku'] }} · {{ config('app.currency_symbol') }}{{ number_format($item['unit_price'], 2) }} {{ __('mail.per_unit') }}</p>
    </div>
    <div style="text-align: right;">
        <p style="margin: 0; font-weight: 700; color: #16a34a; font-size: 16px;">+{{ $item['quantity'] }}</p>
        <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">{{ __('mail.new_total') }}: {{ $item['new_total'] }}</p>
    </div>
</div>
@endforeach

<div class="divider"></div>

<div style="background: #eff6ff; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #1e40af; font-size: 13px;">
        <strong>{{ __('mail.transaction_id') }}:</strong> {{ $transactionData['id'] ?? 'N/A' }}<br>
        <strong>{{ __('mail.date') }}:</strong> {{ $transactionData['date'] ?? now()->toDateTimeString() }}<br>
        <strong>{{ __('mail.by') }}:</strong> {{ $user->name }}
    </p>
</div>

<div class="text-center mt-4">
    <a href="{{ $transactionData['url'] ?? url('/transactions') }}" class="btn">
        {{ __('mail.view_transactions') }}
    </a>
</div>
@endcomponent
