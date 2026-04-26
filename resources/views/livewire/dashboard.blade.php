<div class="mx-auto p-6 z-0 flex-1 min-h-screen bg-gradient-to-br from-sky-50 via-white to-indigo-50">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class=" border border-white/60 bg-white/70 backdrop-blur-xl  p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Operational Dashboard</h1>
                    <p class="text-sm text-gray-500">Inventory flow and brand performance at a glance.</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500">{{ \Carbon\Carbon::now()->toFormattedDateString() }}
                    </p>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full"></span>
                        Live Updates
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <a href="{{ route('tenant.stock-in', ['tenant' => tenant('slug')]) }}"
                class="p-3 bg-blue-600 text-white text-center text-sm font-semibold">+ Stock In</a>
            <a href="{{ route('tenant.stock-out', ['tenant' => tenant('slug')]) }}"
                class="p-3 bg-rose-600 text-white text-center text-sm font-semibold">- Stock Out</a>
            <a href="{{ route('tenant.adjust', ['tenant' => tenant('slug')]) }}"
                class="p-3 bg-amber-600 text-white text-center text-sm font-semibold">Adjust</a>
            <a href="{{ route('tenant.purchase-orders', ['tenant' => tenant('slug')]) }}"
                class="p-3 bg-indigo-600 text-white text-center text-sm font-semibold">Purchase Order</a>
            <button wire:click="sendLowStockAlerts" class="p-3 bg-emerald-600 text-white text-sm font-semibold">Send
                Alerts</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class=" border border-white/60 bg-white/70 backdrop-blur-xl p-5">
                <p class="text-sm font-medium text-gray-500">Total Units</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['totalInventory']) }}</h2>
            </div>
            
            @can('view financial metrics')
            <div class=" border border-white/60 bg-white/70 backdrop-blur-xl p-5">
                <p class="text-sm font-medium text-gray-500">Inventory Equity</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($summary['inventoryEquity'], 2) }}
                </h2>
            </div>
            <div class=" border border-white/60 bg-white/70 backdrop-blur-xl p-5">
                <p class="text-sm font-medium text-gray-500">Potential Revenue</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($summary['potentialRevenue'], 2) }}
                </h2>
            </div>
            <div class=" border border-white/60 bg-white/70 backdrop-blur-xl p-5">
                <p class="text-sm font-medium text-gray-500">Expected Profit</p>
                <h2 class="text-2xl font-bold text-emerald-600 mt-1">
                    ${{ number_format($summary['potentialProfit'], 2) }}</h2>
            </div>
            @endcan
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="border border-white/60 bg-white/75 backdrop-blur-xl p-4 md:p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-800">Stock Flow Overview</h3>
                    <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Inventory vs
                        In/Out</span>
                </div>
                <div class="h-64">
                    <canvas id="stockFlowChart"></canvas>
                </div>
            </div>

            <div class="border border-white/60 bg-white/75 backdrop-blur-xl p-4 md:p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-800">Top Brands</h3>
                    <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Distribution</span>
                </div>
                <div class="h-64">
                    <canvas id="topBrandsChart"></canvas>
                </div>
            </div>
        </div>



        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @can('view financial metrics')
            <div class="border border-white/60 bg-white/75 backdrop-blur-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Profit & Margin Leaders</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Item</th>
                                <th class="text-left py-2">Margin %</th>
                                <th class="text-left py-2">Profit Pool</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($marginLeaders as $leader)
                                <tr class="border-b">
                                    <td class="py-2">
                                        <p class="font-semibold">{{ $leader['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $leader['sku'] }} · Qty {{ $leader['qty'] }}</p>
                                    </td>
                                    <td class="py-2">{{ number_format($leader['margin_pct'], 1) }}%</td>
                                    <td class="py-2 text-emerald-700 font-semibold">
                                        ${{ number_format($leader['profit_pool'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-3 text-gray-500">No margin data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            <div class="border border-white/60 bg-white/75 backdrop-blur-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Inventory Audit Trail</h3>
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    @forelse($recentAudits as $audit)
                        <div class="p-3 border rounded flex justify-between items-center">
                            <div>
                                <p class="text-sm font-semibold">{{ $audit->item?->name ?? 'Item' }} ·
                                    {{ strtoupper($audit->action) }}</p>
                                <p class="text-xs text-gray-500">{{ $audit->user?->name ?? 'System' }} ·
                                    {{ $audit->created_at?->diffForHumans() }}</p>
                            </div>
                            <div class="text-right text-sm">
                                <p>{{ $audit->before_qty }} → {{ $audit->after_qty }}</p>
                                <p class="text-gray-500">{{ $audit->reason ?: 'No reason' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No audit events yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class=" border border-white/60 bg-white/75 backdrop-blur-xl p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class='bx bx-error-circle mr-2 text-rose-500'></i>
                Low Stock Alerts
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($lowStockItems as $itemStat)
                    <div class="flex items-center justify-between p-3 bg-rose-50 rounded-lg border border-rose-100">
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $itemStat->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $itemStat->sku }}</p>
                            <p class="text-xs text-gray-500">Reorder suggestion: {{ $itemStat->reorder_quantity }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-rose-600">{{ $itemStat->quantity }} left</span>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center py-8">
                        <i class='bx bx-check-circle text-4xl text-emerald-100 mb-2'></i>
                        <p class="text-sm text-gray-500">All stock levels healthy.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @script
    <script>
        (() => {
            const chartState = window.stockifyDashboardCharts || {
                stockFlow: null,
                topBrands: null,
            };
            window.stockifyDashboardCharts = chartState;

            const summary = JSON.parse(@js($summaryJson) || '{}');
            const stockFlow = JSON.parse(@js($stockFlowJson) || '{}');
            const topBrands = JSON.parse(@js($topBrandsJson) || '[]');

            const brandColors = ['#0ea5e9', '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#14b8a6', '#f43f5e'];

            const renderCharts = () => {
                if (typeof Chart === 'undefined') {
                    return;
                }

                const stockFlowCanvas = document.getElementById('stockFlowChart');
                const topBrandsCanvas = document.getElementById('topBrandsChart');

                if (!stockFlowCanvas || !topBrandsCanvas) {
                    return;
                }

                if (chartState.stockFlow) {
                    chartState.stockFlow.destroy();
                }
                if (chartState.topBrands) {
                    chartState.topBrands.destroy();
                }

                chartState.stockFlow = new Chart(stockFlowCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: stockFlow.labels || ['Total Inventory', 'Stock In', 'Stock Out'],
                        datasets: [{
                            data: stockFlow.values || [
                                Number(summary.totalInventory || 0),
                                Number(summary.stockIn || 0),
                                Number(summary.stockOut || 0),
                            ],
                            backgroundColor: ['rgba(14, 165, 233, 0.25)', 'rgba(16, 185, 129, 0.25)', 'rgba(244, 63, 94, 0.25)'],
                            borderColor: ['#0ea5e9', '#10b981', '#f43f5e'],
                            borderWidth: 2,
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: '#6b7280' },
                                grid: { color: 'rgba(107, 114, 128, 0.1)' }
                            },
                            x: {
                                ticks: { color: '#4b5563', font: { weight: '600' } },
                                grid: { display: false }
                            }
                        }
                    }
                });

                const hasBrandData = Array.isArray(topBrands) && topBrands.length > 0;
                chartState.topBrands = new Chart(topBrandsCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: hasBrandData ? topBrands.map((item) => item.brand) : ['No Brand Data'],
                        datasets: [{
                            data: hasBrandData ? topBrands.map((item) => Number(item.count || 0)) : [1],
                            backgroundColor: hasBrandData ? topBrands.map((_, index) => brandColors[index % brandColors.length]) : ['#d1d5db'],
                            borderWidth: 1,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#4b5563',
                                    boxWidth: 12,
                                    padding: 12
                                }
                            }
                        },
                        cutout: '62%'
                    }
                });
            };

            const loadChartAndRender = () => {
                if (typeof Chart !== 'undefined') {
                    renderCharts();
                    return;
                }

                const existingScript = document.querySelector('script[data-chartjs="dashboard"]');
                if (existingScript) {
                    existingScript.addEventListener('load', renderCharts, { once: true });
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                script.dataset.chartjs = 'dashboard';
                script.onload = renderCharts;
                document.head.appendChild(script);
            };

            loadChartAndRender();
            document.addEventListener('livewire:navigated', loadChartAndRender);
            if (window.Livewire && typeof window.Livewire.hook === 'function') {
                window.Livewire.hook('message.processed', () => {
                    loadChartAndRender();
                });
            }
        })();
    </script>
    @endscript
</div>