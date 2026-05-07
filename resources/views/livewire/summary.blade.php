<div data-stockify>
    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <div>
                <h1 class="sf-page-title">
                    <i class='bx bx-bar-chart-alt-2 mr-2' style="color: #4361EE;"></i>
                    Summary Report
                </h1>
                <p class="sf-page-subtitle mt-1">Inventory movement summary with stock in, out, adjustments, and balance
                </p>
            </div>
            @feature('bulk-export')
            <button wire:click="exportExcel" class="sf-btn sf-btn-green">
                <i class='bx bx-export'></i> Export Excel
            </button>
            @endfeature
        </div>

        <!-- Filters -->
        <div class="sf-card mb-6">
            <div class="p-5">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <i class='bx bx-search'
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3B8; font-size: 18px;"></i>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Search by item name or SKU..." class="sf-input" style="padding-left: 40px;">
                    </div>
                    <div class="md:w-auto">
                        <button wire:click="filterReports" class="sf-btn sf-btn-blue w-full md:w-auto">
                            <i class='bx bx-filter-alt'></i> Apply Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="sf-summary-card sf-summary-card-blue">
                <div class="sf-summary-icon">
                    <i class='bx bx-package'></i>
                </div>
                <div>
                    <p class="sf-summary-label">Total Items</p>
                    <p class="sf-summary-value">{{ $reports->count() }}</p>
                </div>
            </div>

            <div class="sf-summary-card sf-summary-card-green">
                <div class="sf-summary-icon">
                    <i class='bx bx-arrow-to-bottom'></i>
                </div>
                <div>
                    <p class="sf-summary-label">Total Stock In</p>
                    <p class="sf-summary-value sf-positive">{{ number_format($reports->sum('total_stock_in')) }}</p>
                </div>
            </div>

            <div class="sf-summary-card sf-summary-card-red">
                <div class="sf-summary-icon">
                    <i class='bx bx-arrow-to-top'></i>
                </div>
                <div>
                    <p class="sf-summary-label">Total Stock Out</p>
                    <p class="sf-summary-value sf-negative">{{ number_format($reports->sum('total_stock_out')) }}</p>
                </div>
            </div>

            @can('view financial metrics')
            <div class="sf-summary-card sf-summary-card-purple">
                <div class="sf-summary-icon">
                    <i class='bx bx-dollar'></i>
                </div>
                <div>
                    <p class="sf-summary-label">Inventory Value</p>
                    <p class="sf-summary-value">${{ number_format($reports->sum('inventory_assets'), 2) }}</p>
                </div>
            </div>
            @endcan
        </div>

        <!-- Reports Table -->
        <div class="sf-card">
            <div class="sf-card-head">
                <h3 class="sf-card-title">
                    <i class='bx bx-list-ul mr-2' style="color: #4361EE;"></i>
                    Inventory Movement Report
                </h3>
                <span class="sf-badge sf-badge-gray">{{ $reports->count() }} records</span>
            </div>

            <div class="overflow-x-auto" style="max-height: 500px; overflow-y: auto;">
                <table class="sf-table">
                    <thead class="sf-table-header">
                        <tr>
                            <th>Item Name</th>
                            <th class="text-right">Stock In</th>
                            <th class="text-right">Stock Out</th>
                            <th class="text-right">Balance</th>
                            @can('view financial metrics')
                            <th class="text-right">Value</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr class="sf-table-row">
                                <td>
                                    <div class="font-medium text-gray-900">{{ $report->item_name }}</div>
                                    <div class="sf-meta-text">SKU: {{ $report->sku ?? 'N/A' }}</div>
                                </td>
                                <td class="text-right">
                                    <span class="sf-value-positive">
                                        <i class='bx bx-up-arrow-alt'></i> {{ number_format($report->total_stock_in) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="sf-value-negative">
                                        <i class='bx bx-down-arrow-alt'></i> {{ number_format($report->total_stock_out) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="sf-value-neutral">
                                        {{ number_format($report->current_quantity) }}
                                    </span>
                                </td>
                                @can('view financial metrics')
                                <td class="text-right">
                                    <span class="sf-currency-value">
                                        ${{ number_format($report->inventory_assets, 2) }}
                                    </span>
                                </td>
                                @endcan
                            </tr>
                        @empty
                            <tr class="sf-empty-row">
                                <td colspan="5">
                                    <div class="sf-empty">
                                        <i class='bx bx-folder-open' style="font-size: 48px;"></i>
                                        <p>No results found</p>
                                        <p class="text-sm mt-1">Try adjusting your search criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="sf-table-footer">
                        <tr>
                            <td class="font-bold">Total</td>
                            <td class="text-right font-bold sf-positive">
                                {{ number_format($reports->sum('total_stock_in')) }}</td>
                            <td class="text-right font-bold sf-negative">
                                {{ number_format($reports->sum('total_stock_out')) }}</td>
                            <td class="text-right font-bold">{{ number_format($reports->sum('current_quantity')) }}</td>
                            @can('view financial metrics')
                            <td class="text-right font-bold sf-currency-value">
                                ${{ number_format($reports->sum('inventory_assets'), 2) }}</td>
                            @endcan
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($reports instanceof \Illuminate\Pagination\LengthAwarePaginator && $reports->hasPages())
            <div class="mt-6">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>