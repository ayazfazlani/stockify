@component('emails.layout')
@slot('subject')
{{ __('mail.item_created') }}
@endslot

<h2 style="color: #0f172a; font-size: 22px; margin: 0 0 8px;">{{ __('mail.new_item_added') }}</h2>
<p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">{{ __('mail.item_created_desc') }}</p>

<div class="card">
    <div class="item-row" style="border-bottom: none;">
        <div class="item-img">
            @if($item->image)
            <img src="{{ Storage::url($item->image) }}" alt="">
            @else
            <span style="color: #94a3b8; font-size: 18px;">📦</span>
            @endif
        </div>
        <div style="flex: 1;">
            <p style="margin: 0; font-weight: 600; color: #0f172a; font-size: 16px;">{{ $item->name }}</p>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">{{ $item->sku }} · {{ $item->brand }}</p>
        </div>
    </div>

    <div class="divider"></div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.price') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ config('app.currency_symbol') }}{{ number_format($item->price, 2) }}</p>
        </div>
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.initial_qty') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $item->quantity }}</p>
        </div>
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.type') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $item->type }}</p>
        </div>
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.tracking') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ ucfirst($item->tracking_type ?? 'standard') }}</p>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/items/' . $item->id) }}" class="btn">
        {{ __('mail.view_item') }}
    </a>
</div>
@endcomponent
