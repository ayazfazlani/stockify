<div class="mx-auto p-6 z-0 flex-1 min-h-screen bg-gradient-to-br from-sky-50 via-white to-indigo-50">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Page header -->
        <div class=" border border-white/60 bg-white/70 backdrop-blur-xl p-6 flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daily Expenses ✨</h1>
                <p class="text-sm text-gray-500">Track petty cash, snacks, bills, and miscellaneous expenses.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    <i class="bx bx-wallet-alt mr-1"></i> Petty Cash Book
                </span>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-white/60 bg-white/70 backdrop-blur-xl p-5 border-l-4 border-l-indigo-500">
                <p class="text-sm font-medium text-gray-500 mb-1">Today's Expenses</p>
                <div class="flex items-center">
                    <h2 class="text-3xl font-bold text-gray-800">${{ number_format($todayTotal, 2) }}</h2>
                </div>
            </div>

            <div class="border border-white/60 bg-white/70 backdrop-blur-xl p-5 border-l-4 border-l-rose-500">
                <p class="text-sm font-medium text-gray-500 mb-1">This Month's Expenses</p>
                <div class="flex items-center">
                    <h2 class="text-3xl font-bold text-gray-800">${{ number_format($monthTotal, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Add Expense Form -->
            <div class="lg:col-span-1">
                <div class="border border-white/60 bg-white/75 backdrop-blur-xl p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center">
                        <i class="bx bx-plus-circle text-indigo-500 mr-2 text-xl"></i> 
                        Log Expense
                    </h2>
                    
                    @if (session()->has('message'))
                        <div class="mb-5 p-3 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200 text-sm flex items-start">
                            <i class="bx bx-check-circle text-lg mr-2"></i> {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="saveExpense" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Amount ($) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" wire:model.defer="amount" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border" placeholder="0.00" required />
                            @error('amount') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Category <span class="text-rose-500">*</span></label>
                            <input list="categoryOptions" wire:model.defer="category" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border" placeholder="E.g. Snacks, Bills" required />
                            <datalist id="categoryOptions">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            <p class="text-xs text-gray-500 mt-1">Select or type a new category.</p>
                            @error('category') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                            <textarea wire:model.defer="description" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border" rows="3" placeholder="Optional details (e.g. coffee for meeting)"></textarea>
                            @error('description') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Date <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model.defer="expense_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border" required />
                            @error('expense_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Save Expense
                        </button>
                    </form>
                </div>
            </div>

            <!-- Expense List -->
            <div class="lg:col-span-2">
                <div class="border border-white/60 bg-white/75 backdrop-blur-xl h-full flex flex-col">
                    <header class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white/50">
                        <h2 class="text-lg font-bold text-gray-800"><i class="bx bx-list-ul text-gray-400 mr-2"></i>Recent Expenses</h2>
                    </header>
                    <div class="flex-1">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold">Date / Category</th>
                                        <th class="px-6 py-3 font-semibold">Description</th>
                                        <th class="px-6 py-3 font-semibold text-right">Amount</th>
                                        <th class="px-6 py-3 font-semibold text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($expenses as $expense)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium text-gray-900">{{ $expense->expense_date->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500 flex items-center mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $expense->category }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-gray-700 text-sm max-w-xs break-words">
                                                    {{ $expense->description ?: '-' }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1">
                                                    Added by {{ $expense->user->name ?? 'System' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="font-bold text-rose-600">
                                                    -${{ number_format($expense->amount, 2) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button wire:click="deleteExpense({{ $expense->id }})" 
                                                        wire:confirm="Are you sure you want to delete this expense?" 
                                                        class="text-gray-400 hover:text-rose-500 transition-colors p-1 rounded-full hover:bg-rose-50" 
                                                        title="Delete expense">
                                                    <i class="bx bx-trash text-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center text-gray-400">
                                                    <i class="bx bx-wallet text-4xl mb-3 opacity-50"></i>
                                                    <p class="text-base text-gray-500">No expenses recorded yet.</p>
                                                    <p class="text-sm mt-1">Use the form to start tracking your daily spending.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($expenses->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                            {{ $expenses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
