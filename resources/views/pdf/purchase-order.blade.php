<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order #{{ $po->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        .header { margin-bottom: 16px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .meta { margin: 4px 0; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .right { text-align: right; }
        .total { margin-top: 12px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Purchase Order #{{ $po->id }}</p>
        <p class="meta">Supplier: {{ $po->supplier?->name ?? 'N/A' }}</p>
        <p class="meta">Expected Date: {{ optional($po->expected_date)->format('M d, Y') ?? 'N/A' }}</p>
        <p class="meta">Status: {{ ucfirst($po->status) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>SKU</th>
                <th class="right">Ordered</th>
                <th class="right">Received</th>
                <th class="right">Unit Cost</th>
                <th class="right">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->items as $line)
                <tr>
                    <td>{{ $line->item?->name }}</td>
                    <td>{{ $line->item?->sku }}</td>
                    <td class="right">{{ $line->ordered_qty }}</td>
                    <td class="right">{{ $line->received_qty }}</td>
                    <td class="right">{{ number_format((float) $line->unit_cost, 2) }}</td>
                    <td class="right">{{ number_format((float) $line->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total Amount: {{ number_format((float) $po->total_amount, 2) }}</p>
</body>
</html>
