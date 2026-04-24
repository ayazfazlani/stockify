<div style="font-family: Arial, sans-serif; max-width: 640px; margin: 0 auto;">
    <h2>Low Stock Alert</h2>
    <p>The following items are below reorder level:</p>
    <ul>
        @foreach($alerts as $alert)
            <li>
                <strong>{{ $alert['name'] }}</strong>
                (SKU: {{ $alert['sku'] }}) -
                Current: {{ $alert['current'] }},
                Reorder level: {{ $alert['reorder_level'] }},
                Suggested reorder: {{ $alert['suggested_order'] }}
            </li>
        @endforeach
    </ul>
</div>
