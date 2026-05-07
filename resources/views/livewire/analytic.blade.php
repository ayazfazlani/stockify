<div data-stockify>
    <div class="p-6 flex justify-between items-center">
        <h1 class="sf-page-title">
            <i class='bx bx-line-chart mr-2' style="color: #4361EE;"></i>
            Analytics
        </h1>
        <button wire:click="exportExcel" class="sf-btn sf-btn-green">
            <i class='bx bx-export'></i> Export Excel
        </button>
    </div>

    <div class="px-6 pb-6 space-y-6">
        <!-- Filter Section -->
        <div class="sf-card">
            <div class="p-5">
                <h3 class="sf-section-title mb-4">
                    <i class='bx bx-filter-alt mr-2' style="color: #4361EE;"></i>
                    Filter Analytics
                </h3>
                <div class="flex w-full gap-3">
                    <div class="flex-1">
                        <div class="relative">
                            <i class='bx bx-search' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3B8; font-size: 18px;"></i>
                            <input type="text" wire:model.live="filterName" 
                                   class="sf-input" style="padding-left: 40px;"
                                   placeholder="Search by item name..." />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Table Section -->
        <div class="sf-card">
            <div class="sf-card-head">
                <h3 class="sf-card-title">
                    <i class='bx bx-data mr-2' style="color: #4361EE;"></i>
                    Item Analytics
                </h3>
                <span class="sf-badge sf-badge-gray">{{ count(json_decode($filteredAnalyticsDataJson, true)) }} items</span>
            </div>
            
            <div class="sf-analytics-container">
                <!-- Sticky Left Column (Item Names) -->
                <div class="sf-sticky-col">
                    <table class="sf-table sf-table-sticky">
                        <thead>
                            <tr>
                                <th class="sf-sticky-header">Item Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (json_decode($filteredAnalyticsDataJson, true) as $data)
                                <tr class="sf-table-row">
                                    <td class="sf-sticky-cell">
                                        <div class="font-medium text-gray-900">{{ $data['item_name'] }}</div>
                                        <div class="sf-meta-text mt-1">SKU: {{ $data['sku'] ?? 'N/A' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="sf-table-row sf-total-row">
                                <td class="sf-sticky-cell sf-total-cell">
                                    <div class="font-bold">Total Summary</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Scrollable Analytics Columns -->
                <div class="sf-scrollable-col">
                    <table class="sf-table">
                        <thead>
                            <tr>
                                <th>Current Qty</th>
                                @can('view financial metrics')
                                <th>Inventory Assets</th>
                                @endcan
                                <th>Avg Quantity</th>
                                <th>Turnover Ratio</th>
                                <th>Stock Out Days</th>
                                <th>Total Stock In</th>
                                <th>Total Stock Out</th>
                                <th>Avg Daily In</th>
                                <th>Avg Daily Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (json_decode($filteredAnalyticsDataJson, true) as $data)
                                <tr class="sf-table-row">
                                    <td class="sf-value-cell">{{ number_format($data['current_quantity']) }}</td>
                                    @can('view financial metrics')
                                    <td class="sf-value-cell sf-currency">${{ number_format($data['inventory_assets'], 2) }}</td>
                                    @endcan
                                    <td class="sf-value-cell">{{ number_format($data['average_quantity'], 1) }}</td>
                                    <td class="sf-value-cell">
                                        <span class="sf-badge {{ $data['turnover_ratio'] > 2 ? 'sf-badge-success' : 'sf-badge-warning' }}">
                                            {{ number_format($data['turnover_ratio'], 2) }}
                                        </span>
                                    </td>
                                    <td class="sf-value-cell">
                                        @php
                                            $stockOutDays = $data['stock_out_days_estimate'];
                                            $isCritical = $stockOutDays <= 7 && $stockOutDays > 0;
                                            $isOut = $stockOutDays <= 0;
                                        @endphp
                                        @if($isOut)
                                            <span class="sf-badge sf-badge-danger">Out of Stock</span>
                                        @elseif($isCritical)
                                            <span class="sf-badge sf-badge-warning">{{ number_format($stockOutDays, 1) }} days</span>
                                        @else
                                            <span>{{ number_format($stockOutDays, 1) }} days</span>
                                        @endif
                                    </td>
                                    <td class="sf-value-cell sf-positive">+{{ number_format($data['total_stock_in']) }}</td>
                                    <td class="sf-value-cell sf-negative">-{{ number_format($data['total_stock_out']) }}</td>
                                    <td class="sf-value-cell">{{ number_format($data['avg_daily_stock_in'], 1) }}</td>
                                    <td class="sf-value-cell">{{ number_format($data['avg_daily_stock_out'], 1) }}</td>
                                </tr>
                            @endforeach
                            <!-- Total Row -->
                            <tr class="sf-table-row sf-total-row">
                                <td class="sf-total-cell">{{ number_format($this->calculate('current_quantity')) }}</td>
                                @can('view financial metrics')
                                <td class="sf-total-cell sf-currency">${{ number_format($this->calculate('inventory_assets'), 2) }}</td>
                                @endcan
                                <td class="sf-total-cell">{{ number_format($this->calculate('average_quantity'), 1) }}</td>
                                <td class="sf-total-cell">{{ number_format($this->calculate('turnover_ratio'), 2) }}</td>
                                <td class="sf-total-cell">{{ number_format($this->calculate('stock_out_days_estimate'), 1) }} days</td>
                                <td class="sf-total-cell sf-positive">+{{ number_format($this->calculate('total_stock_in')) }}</td>
                                <td class="sf-total-cell sf-negative">-{{ number_format($this->calculate('total_stock_out')) }}</td>
                                <td class="sf-total-cell">{{ number_format($this->calculate('avg_daily_stock_in'), 1) }}</td>
                                <td class="sf-total-cell">{{ number_format($this->calculate('avg_daily_stock_out'), 1) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="sf-summary-card">
                <div class="sf-summary-icon sf-summary-icon-total">
                    <i class='bx bx-package'></i>
                </div>
                <div>
                    <p class="sf-summary-label">Total Items</p>
                    <p class="sf-summary-value">{{ count(json_decode($filteredAnalyticsDataJson, true)) }}</p>
                </div>
            </div>
            
            @can('view financial metrics')
            <div class="sf-summary-card">
                <div class="sf-summary-icon sf-summary-icon-assets">
                    <i class='bx bx-dollar'></i>
                </div>
                <div>
                    <p class="sf-summary-label">Total Inventory Value</p>
                    <p class="sf-summary-value">${{ number_format($this->calculate('inventory_assets'), 2) }}</p>
                </div>
            </div>
            @endcan
            
            <div class="sf-summary-card">
                <div class="sf-summary-icon sf-summary-icon-in">
                    <i class='bx bx-arrow-to-bottom'></i>
                </div>
                <div>
                    <p class="sf-summary-label">Total Stock In</p>
                    <p class="sf-summary-value sf-positive">+{{ number_format($this->calculate('total_stock_in')) }}</p>
                </div>
            </div>
            
            <div class="sf-summary-card">
                <div class="sf-summary-icon sf-summary-icon-out">
                    <i class='bx bx-arrow-to-top'></i>
                </div>
                <div>
                    <p class="sf-summary-label">Total Stock Out</p>
                    <p class="sf-summary-value sf-negative">-{{ number_format($this->calculate('total_stock_out')) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>