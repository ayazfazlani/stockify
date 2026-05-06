<div data-stockify>
    <div class="p-6 flex-1 min-h-screen" style="background: #F4F5F8;">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Page header -->
            <div class="sf-card">
                <div class="flex flex-col md:flex-row md:items-center justify-between p-6">
                    <div>
                        <h1 class="sf-page-title">
                            <i class='bx bx-wallet-alt mr-2' style="color: #4361EE;"></i>
                            Daily Expenses
                        </h1>
                        <p class="sf-page-subtitle">Track petty cash, snacks, bills, and miscellaneous expenses.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="sf-badge sf-badge-purple">
                            <i class='bx bx-book-open mr-1'></i> Petty Cash Book
                        </span>
                    </div>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="sf-metric-card" style="border-left-color: #4361EE;">
                    <p class="sf-metric-label">Today's Expenses</p>
                    <div class="sf-metric-value">${{ number_format($todayTotal, 2) }}</div>
                </div>

                <div class="sf-metric-card" style="border-left-color: #F04438;">
                    <p class="sf-metric-label">This Month's Expenses</p>
                    <div class="sf-metric-value">${{ number_format($monthTotal, 2) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Add Expense Form -->
                <div class="lg:col-span-1">
                    <div class="sf-card">
                        <div class="p-5 border-b border-gray-100">
                            <h2 class="sf-card-title">
                                <i class='bx bx-plus-circle mr-2' style="color: #4361EE;"></i>
                                Log Expense
                            </h2>
                        </div>
                        <div class="p-5">
                            @if (session()->has('message'))
                                <div class="sf-alert sf-alert-success mb-5">
                                    <i class='bx bx-check-circle'></i>
                                    {{ session('message') }}
                                </div>
                            @endif

                            <form wire:submit.prevent="saveExpense" class="space-y-4">
                                <div class="sf-field">
                                    <label class="sf-label">Amount ($) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" wire:model.defer="amount" class="sf-input" placeholder="0.00">
                                    @error('amount') <div class="sf-ferr"><i class='bx bx-error-circle'></i> {{ $message }}</div> @enderror
                                </div>

                                <div class="sf-field">
                                    <label class="sf-label">Category <span class="text-red-500">*</span></label>
                                    <input list="categoryOptions" wire:model.defer="category" class="sf-input" placeholder="E.g. Snacks, Bills">
                                    <datalist id="categoryOptions">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">
                                        @endforeach
                                    </datalist>
                                    <div class="sf-hint">Select or type a new category.</div>
                                    @error('category') <div class="sf-ferr">{{ $message }}</div> @enderror
                                </div>

                                <div class="sf-field">
                                    <label class="sf-label">Description</label>
                                    <textarea wire:model.defer="description" class="sf-input" rows="3" placeholder="Optional details (e.g. coffee for meeting)"></textarea>
                                    @error('description') <div class="sf-ferr">{{ $message }}</div> @enderror
                                </div>

                                <div class="sf-field">
                                    <label class="sf-label">Date <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model.defer="expense_date" class="sf-input">
                                    @error('expense_date') <div class="sf-ferr">{{ $message }}</div> @enderror
                                </div>

                                <button type="submit" class="sf-btn sf-btn-blue" style="width: 100%; justify-content: center;">
                                    <i class='bx bx-save'></i> Save Expense
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Expense List -->
                <div class="lg:col-span-2">
                    <div class="sf-card" style="height: 100%; display: flex; flex-direction: column;">
                        <div class="sf-card-head">
                            <h2 class="text-lg font-semibold" style="color: #0F1117;">
                                <i class='bx bx-list-ul mr-2' style="color: #9CA3B8;"></i>
                                Recent Expenses
                            </h2>
                            <span class="sf-badge sf-badge-gray">{{ $expenses->total() }} records</span>
                        </div>
                        
                        <div style="flex: 1; overflow-x: auto;">
                            <table class="sf-table">
                                <thead>
                                    <tr>
                                        <th>Date / Category</th>
                                        <th>Description</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expenses as $expense)
                                        <tr class="sf-table-row">
                                            <td>
                                                <div class="font-medium text-gray-900">{{ $expense->expense_date->format('M d, Y') }}</div>
                                                <div class="mt-1">
                                                    <span class="sf-badge sf-badge-light">{{ $expense->category }}</span>
                                                </div>
                                            </table>
                                            <td>
                                                <div class="text-gray-700 text-sm">
                                                    {{ $expense->description ?: '-' }}
                                                </div>
                                                <div class="sf-meta-text mt-1">
                                                    Added by {{ $expense->user->name ?? 'System' }}
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="sf-amount-negative">
                                                    -${{ number_format($expense->amount, 2) }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button wire:click="deleteExpense({{ $expense->id }})" 
                                                        wire:confirm="Are you sure you want to delete this expense?" 
                                                        class="sf-icon-btn sf-icon-btn-danger" 
                                                        title="Delete expense">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="sf-table-empty">
                                                <div class="sf-empty">
                                                    <i class='bx bx-wallet-alt' style="font-size: 48px;"></i>
                                                    <p>No expenses recorded yet.</p>
                                                    <p class="text-sm mt-1">Use the form to start tracking your daily spending.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($expenses->hasPages())
                            <div class="sf-pagination">
                                {{ $expenses->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>