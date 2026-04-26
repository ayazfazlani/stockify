<x-mail::message>
# Daily Business Summary: {{ $data['store_name'] }}
Date: {{ now()->format('M d, Y') }}

Here is your daily snapshot for **{{ $data['store_name'] }}**.

<x-mail::panel>
### Financial Performance (Today)
- **Total Sales Revenue:** ${{ number_format($data['total_sales'], 2) }}
- **Transactions Processed:** {{ $data['transaction_count'] }}
- **Daily Net (Estimated):** ${{ number_format($data['net_total'], 2) }}
</x-mail::panel>

### Stock Overview
- **Total Inventory Value:** ${{ number_format($data['total_stock_value'], 2) }}
- **Low Stock Items:** {{ $data['low_stock_count'] }} items need attention.

@if($data['low_stock_count'] > 0)
<x-mail::button :url="$data['low_stock_url']" color="error">
View Low Stock Items
</x-mail::button>
@endif

### Business Intelligence
- **Top Moving Item:** {{ $data['top_item'] }}
- **Active Subscriptions:** {{ $data['active_subs'] }}

<x-mail::button :url="$data['dashboard_url']">
Open Full Analytics
</x-mail::button>

Stay on top of your business!

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
