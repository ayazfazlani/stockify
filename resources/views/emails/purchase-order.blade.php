@component('emails.layout')
@slot('subject')
{{ $type === 'supplier' ? __('mail.new_purchase_order_supplier') : __('mail.purchase_order_updated') }}
@endslot

<h2 style="color: #0f172a; font-size: 22px; margin: 0 0 8px;">
    {{ $type === 'supplier' ? __('mail.new_po_from', ['company' => $purchaseOrder->tenant?->name ?? 'Stockify']) : __('mail.purchase_order_title') }}
</h2>

<div class="card">
    <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('mail.po_number') }}</p>
            <p style="margin: 4px 0 0; font-weight: 700; color: #0f172a; font-size: 18px;">#{{ $purchaseOrder->po_number ?? $purchaseOrder->id }}</p>
        </div>
        <div style="text-align: right;">
            <span class="badge {{ $purchaseOrder->status === 'approved' ? 'badge-success' : ($purchaseOrder->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                {{ ucfirst($purchaseOrder->status) }}
            </span>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between;">
        <div>
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.supplier') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $purchaseOrder->supplier?->name ?? 'N/A' }}</p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0; color: #64748b; font-size: 12px;">{{ __('mail.order_date') }}</p>
            <p style="margin: 4px 0 0; font-weight: 600; color: #0f172a;">{{ $purchaseOrder->created_at?->toDateString() ?? now()->toDateString() }}</p>
        </div>
    </div>
</div>

<div class="divider"></div>

<h3 style="color: #0f172a; font-size: 16px; margin: 0 0 16px;">{{ __('mail.order_items') }}</h3>

@foreach($purchaseOrder->items ?? [] as $poItem)
<div class="item-row">
    <div class="item-img">
        <span style="color: #94a3b8; font-size: 18px;">📦</span>
    </div>
    <div style="flex: 1;">
        <p style="margin: 0; font-weight: 600; color: #0f172a; font-size: 14px;">{{ $poItem->item?->name ?? 'Item' }}</p>
        <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">{{ $poItem->item?->sku ?? 'N/A' }}</p>
    </div>
    <div style="text-align: right;">
        <p style="margin: 0; font-weight: 600; color: #0f172a;">{{ $poItem->quantity }} x {{ config('app.currency_symbol') }}{{ number_format($poItem->unit_price, 2) }}</p>
        <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">{{ config('app.currency_symbol') }}{{ number_format($poItem->quantity * $poItem->unit_price, 2) }}</p>
    </div>
</div>
@endforeach

<div class="divider"></div>

<div style="text-align: right;">
    <p style="margin: 0; color: #64748b; font-size: 14px;">{{ __('mail.subtotal') }}: <strong>{{ config('app.currency_symbol') }}{{ number_format($purchaseOrder->subtotal ?? 0, 2) }}</strong></p>
    <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">{{ __('mail.tax') }}: <strong>{{ config('app.currency_symbol') }}{{ number_format($purchaseOrder->tax ?? 0, 2) }}</strong></p>
    <p style="margin: 8px 0 0; color: #0f172a; font-size: 18px; font-weight: 700;">{{ __('mail.total') }}: {{ config('app.currency_symbol') }}{{ number_format($purchaseOrder->total ?? 0, 2) }}</p>
</div>

<div class="text-center mt-4">
    <a href="{{ url('/purchase-orders/' . $purchaseOrder->id) }}" class="btn">
        {{ $type === 'supplier' ? __('mail.view_po_details') : __('mail.manage_po') }}
    </a>
</div>
@endcomponent
