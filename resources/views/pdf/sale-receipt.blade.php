<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 3mm; size: 80mm auto; }
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 10px; margin: 0; }
        .receipt { width: 74mm; margin: 0 auto; }
        .card { border: 1px dashed #9ca3af; border-radius: 4px; overflow: hidden; }
        .header { background: #111827; color: #ffffff; padding: 8px; text-align: center; }
        .header h1 { margin: 0; font-size: 12px; letter-spacing: 0.4px; }
        .header p { margin: 2px 0 0; font-size: 9px; color: #d1d5db; }
        .meta { padding: 8px; border-bottom: 1px dashed #9ca3af; }
        .meta-row { margin-bottom: 2px; }
        .content { padding: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px dashed #d1d5db; padding: 3px 2px; }
        th { text-align: left; font-size: 9px; }
        .right { text-align: right; }
        .summary { margin-top: 6px; width: 100%; }
        .summary td { border: none; padding: 2px 0; }
        .grand { font-size: 11px; font-weight: bold; border-top: 1px dashed #9ca3af; padding-top: 5px !important; }
        .footer { padding: 8px; text-align: center; border-top: 1px dashed #9ca3af; color: #4b5563; font-size: 9px; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="card">
            <div class="header">
                <h1>{{ $sale->store->name ?? 'Stockify' }}</h1>
                <p>Sales Receipt</p>
            </div>

            <div class="meta">
                <div class="meta-row"><strong>Receipt #:</strong> {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="meta-row"><strong>Date:</strong> {{ optional($sale->created_at)->format('M d, Y h:i A') }}</div>
                <div class="meta-row"><strong>Cashier:</strong> {{ $sale->user->name ?? 'System' }}</div>
                <div class="meta-row"><strong>Customer:</strong> {{ $sale->customer_name ?: 'Walk-in #'.$sale->id }}</div>
                <div class="meta-row"><strong>Phone:</strong> {{ $sale->customer_phone ?: '-' }}</div>
            </div>

            <div class="content">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="right">Qty</th>
                            <th class="right">Price</th>
                            <th class="right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->transactions as $line)
                            <tr>
                                <td>{{ $line->item_name }}</td>
                                <td class="right">{{ $line->quantity }}</td>
                                <td class="right">{{ number_format((float) $line->unit_price, 2) }}</td>
                                <td class="right">{{ number_format((float) $line->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="summary">
                    <tr>
                        <td></td>
                        <td class="right grand">TOTAL: {{ number_format((float) $sale->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                Thank you for your purchase
            </div>
        </div>
    </div>
</body>
</html>
