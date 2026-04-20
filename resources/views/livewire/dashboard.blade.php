<div class="mx-auto p-6 z-0 flex-1 bg-white shadow-sm min-h-screen">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Operational Dashboard</h1>
            <p class="text-sm text-gray-500">Overview of your store's inventory and financial health.</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium text-gray-500">{{ \Carbon\Carbon::now()->toFormattedDateString() }}</p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full"></span>
                Live Updates
            </span>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Stock --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-blue-50 rounded-lg">
                    <i class='bx bx-package text-2xl text-blue-600'></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500">Total Units</p>
            <h2 class="text-2xl font-bold text-gray-800">{{ number_format($summary['totalInventory']) }}</h2>
        </div>

        {{-- Inventory Equity --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-indigo-50 rounded-lg">
                    <i class='bx bx-wallet text-2xl text-indigo-600'></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500">Inventory Equity (Cost)</p>
            <h2 class="text-2xl font-bold text-gray-800">${{ number_format($summary['inventoryEquity'], 2) }}</h2>
        </div>

        {{-- Potential Revenue --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-emerald-50 rounded-lg">
                    <i class='bx bx-trending-up text-2xl text-emerald-600'></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500">Potential Revenue</p>
            <h2 class="text-2xl font-bold text-gray-800">${{ number_format($summary['potentialRevenue'], 2) }}</h2>
        </div>

        {{-- Expected Profit --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-amber-50 rounded-lg">
                    <i class='bx bx-coin-stack text-2xl text-amber-600'></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500">Expected Profit</p>
            <h2 class="text-2xl font-bold text-emerald-600">${{ number_format($summary['potentialProfit'], 2) }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Main Chart --}}
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">Inventory Distribution</h3>
                <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Top 10 Items</span>
            </div>
            <div class="h-64">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class='bx bx-error-circle mr-2 text-rose-500'></i>
                Low Stock Alerts
            </h3>
            <div class="space-y-4">
                @forelse($lowStockItems as $itemStat)
                    <div class="flex items-center justify-between p-3 bg-rose-50 rounded-lg border border-rose-100">
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $itemStat->item->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $itemStat->item->sku }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-rose-600">{{ $itemStat->current_quantity }} left</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class='bx bx-check-circle text-4xl text-emerald-100 mb-2'></i>
                        <p class="text-sm text-gray-500">All stock levels healthy!</p>
                    </div>
                @endforelse
            </div>
            @if(count($lowStockItems) > 0)
                <div class="mt-4">
                    <a href="{{ route(tenancy()->initialized ? 'tenant.items' : 'items') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center justify-center">
                        SEE ALL ITEMS <i class='bx bx-right-arrow-alt ml-1'></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Secondary Metrics --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Stock In vs Out (Total)</h3>
            <div class="flex items-center space-x-8">
               <div class="flex-1 text-center border-r border-gray-100">
                   <p class="text-xs font-medium text-gray-400 uppercase">Life-time Stock In</p>
                   <p class="text-2xl font-bold text-blue-600">{{ number_format($summary['stockIn']) }}</p>
               </div>
               <div class="flex-1 text-center">
                    <p class="text-xs font-medium text-gray-400 uppercase">Life-time Stock Out</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ number_format($summary['stockOut']) }}</p>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        const ctx = document.getElementById('inventoryChart').getContext('2d');
        const inventoryData = @json($totalInventoryData);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: inventoryData.map(item => item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name),
                datasets: [{
                    label: 'Current Quantity',
                    data: inventoryData.map(item => item.quantity),
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderColor: 'rgb(79, 70, 229)',
                    borderWidth: 2,
                    borderRadius: 5,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#4b5563', font: { weight: 'bold' } }
                    }
                }
            }
        });
    </script>
    @endscript
</div>