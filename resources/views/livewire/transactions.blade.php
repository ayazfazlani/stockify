<div data-stockify>
    <div class="p-4 md:p-6 flex-1 min-h-screen" style="background: #F4F5F8;">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 style="font-size: 24px; font-weight: 700; color: #0F1117;">Transactions</h1>
            <button wire:click="exportToExcel" class="sf-btn sf-btn-green">
                <i class='bx bx-export'></i> Export Excel
            </button>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-4 mb-6 max-sm:flex-wrap">
            <div class="relative flex-1">
                <i class='bx bx-search'
                    style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3B8; font-size: 18px;"></i>
                <input type="text" wire:model.live.debounce.300ms="filter" class="sf-input" style="padding-left: 40px;"
                    placeholder="Search transactions by item name or type..." />
            </div>

            <div class="w-full flex justify-between md:justify-end md:flex-1 items-center gap-2">
                <div class="sf-daterange">
                    <i class='bx bx-calendar'></i>
                    <input type="date" wire:model.live="dateRange.start">
                    <span>—</span>
                    <input type="date" wire:model.live="dateRange.end">
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex gap-5 max-sm:flex-wrap">
            <!-- Left Column: Transaction List -->
            <div class="w-full md:w-1/2">
                <div class="sf-card" style="height: calc(100vh - 220px); display: flex; flex-direction: column;">
                    <div class="sf-card-head">
                        <h2 class="text-lg font-semibold" style="color: #0F1117;">Transaction List</h2>
                        <span style="font-size: 11px; color: #9CA3B8; font-family: 'JetBrains Mono', monospace;">
                            {{ $transactions->count() }} records
                        </span>
                    </div>
                    <div style="flex: 1; overflow-y: auto; padding: 12px;">
                        @forelse($transactions as $transaction)
                            <div wire:key="transaction-{{ $transaction->id }}"
                                wire:click="handleTransactionClick({{ $transaction->id }})"
                                class="sf-transaction-item {{ $selectedTransaction && $selectedTransaction->id === $transaction->id ? 'selected' : '' }}">
                                <div class="sf-transaction-icon {{ $this->getTransactionColor($transaction->type) }}">
                                    @if($transaction->type === 'stock in')
                                        <i class='bx bx-arrow-to-bottom'></i>
                                    @elseif($transaction->type === 'stock out')
                                        <i class='bx bx-arrow-to-top'></i>
                                    @else
                                        <i class='bx bx-transfer'></i>
                                    @endif
                                </div>
                                <div class="sf-transaction-info">
                                    <div class="sf-transaction-name">{{ $transaction->item_name }}</div>
                                    <div class="sf-transaction-type">{{ ucfirst($transaction->type) }}</div>
                                </div>
                                <div class="sf-transaction-date">
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}
                                </div>
                                <div
                                    class="sf-transaction-qty {{ $transaction->type === 'stock in' ? 'positive' : 'negative' }}">
                                    {{ $transaction->type === 'stock in' ? '+' : '-' }}{{ $transaction->quantity }}
                                </div>
                            </div>
                        @empty
                            <div class="sf-empty">
                                <i class='bx bx-receipt' style="font-size: 48px;"></i>
                                <p>No transactions found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Transaction Details -->
            <div class="w-full md:w-1/2">
                <div class="sf-card" style="height: calc(100vh - 220px); display: flex; flex-direction: column;">
                    <div class="sf-card-head">
                        <h2 class="text-lg font-semibold" style="color: #0F1117;">Transaction Details</h2>
                        <i class='bx bx-detail' style="color: #9CA3B8;"></i>
                    </div>
                    <div style="flex: 1; overflow-y: auto; padding: 16px;">
                        @if($selectedTransaction)
                            <div class="space-y-5">
                                <!-- Status Badge -->
                                <div class="sf-status-badge {{ $selectedTransaction->type === 'stock in' ? 'in' : 'out' }}">
                                    <i
                                        class='bx {{ $selectedTransaction->type === 'stock in' ? 'bx-arrow-to-bottom' : 'bx-arrow-to-top' }}'></i>
                                    {{ ucfirst($selectedTransaction->type) }}
                                </div>

                                <!-- Details Grid -->
                                <div class="sf-details-grid">
                                    <div class="sf-detail-item">
                                        <label class="sf-detail-label">Item Name</label>
                                        <p class="sf-detail-value">{{ $selectedTransaction->item_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="sf-detail-item">
                                        <label class="sf-detail-label">Quantity</label>
                                        <p class="sf-detail-value {{ $selectedTransaction->type === 'stock in' ? 'text-green-600' : 'text-red-600' }}"
                                            style="font-weight: 700;">
                                            {{ $selectedTransaction->type === 'stock in' ? '+' : '-' }}{{ $selectedTransaction->quantity }}
                                        </p>
                                    </div>
                                    @can('view financial metrics')
                                    <div class="sf-detail-item">
                                        <label class="sf-detail-label">Unit Price</label>
                                        <p class="sf-detail-value" style="font-family: 'JetBrains Mono', monospace;">
                                            ${{ number_format($selectedTransaction->unit_price, 2) }}
                                        </p>
                                    </div>
                                    <div class="sf-detail-item">
                                        <label class="sf-detail-label">Total Price</label>
                                        <p class="sf-detail-value"
                                            style="font-family: 'JetBrains Mono', monospace; font-weight: 700;">
                                            ${{ number_format($selectedTransaction->total_price, 2) }}
                                        </p>
                                    </div>
                                    @endcan
                                    <div class="sf-detail-item">
                                        <label class="sf-detail-label">Date & Time</label>
                                        <p class="sf-detail-value" style="font-family: 'JetBrains Mono', monospace;">
                                            {{ \Carbon\Carbon::parse($selectedTransaction->created_at)->format('M d, Y g:i A') }}
                                        </p>
                                    </div>
                                    <div class="sf-detail-item">
                                        <label class="sf-detail-label">Transaction ID</label>
                                        <p class="sf-detail-value"
                                            style="font-family: 'JetBrains Mono', monospace; font-size: 12px;">
                                            #{{ $selectedTransaction->id }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="sf-empty"
                                style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                                <i class='bx bx-folder-open' style="font-size: 48px;"></i>
                                <p>Select a transaction to view details</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>