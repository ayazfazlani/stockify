@component('emails.layout')
@slot('subject')
{{ __('mail.item_updated') }}
@endslot

<h2 style="color: #0f172a; font-size: 22px; margin: 0 0 8px;">{{ __('mail.item_changes') }}</h2>
<p style="color: #64748b; font-size: 14px; margin: 0 0 24px;">{{ __('mail.item_updated_desc', ['name' => $item->name]) }}</p>

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
            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">{{ $item->sku }}</p>
        </div>
    </div>

    <div class="divider"></div>

    <h3 style="color: #0f172a; font-size: 14px; margin: 0 0 12px;">{{ __('mail.changes_made') }}</h3>

    @foreach($changes as $field => $change)
    <div style="display: flex; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
        <div style="width: 120px;">
            <span style="color: #64748b; font-size: 12px; text-transform: capitalize;">{{ $field }}</span>
        </div>
        <div style="flex: 1; display: flex; align-items: center; gap: 8px;">
            <span style="color: #dc2626; font-size: 13px; text-decoration: line-through;">{{ $change['old'] }}</span>
            <i class="fas fa-arrow-right" style="color: #94a3b8; font-size: 10px;"></i>
            <span style="color: #16a34a; font-size: 13px; font-weight: 600;">{{ $change['new'] }}</span>
        </div>
    </div>
    @endforeach
</div>

<div class="text-center mt-4">
    <a href="{{ url('/items/' . $item->id) }}" class="btn">
        {{ __('mail.view_item') }}
    </a>
</div>
@endcomponent
