<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Illuminate\Support\Facades\Auth;

class Transactions extends Component
{
    public $transactions;
    public $selectedTransaction = null;
    public $filter = '';
    public $dateRange = [
        'start' => '',
        'end' => ''
    ];

    public function mount()
    {
        $this->transactions = collect(); // Initialize as collection to prevent foreach errors
        $this->fetchTransactions();
    }

    public function fetchTransactions()
    {
        try {
            $query = Transaction::query();

            $storeId = Auth::user()->getCurrentStoreId();
            $query->where('store_id', $storeId);

            // Apply search filter
            if (!empty($this->filter)) {
                $query->where(function ($query) {
                    $query->where('item_name', 'like', '%' . $this->filter . '%')
                        ->orWhere('type', 'like', '%' . $this->filter . '%');
                });
            }

            // Apply date range filter
            if (!empty($this->dateRange['start']) && !empty($this->dateRange['end'])) {
                $query->whereBetween('created_at', [
                    $this->dateRange['start'] . ' 00:00:00',
                    $this->dateRange['end'] . ' 23:59:59'
                ]);
            }

            $this->transactions = $query->orderBy('created_at', 'desc')->get();
            $this->selectedTransaction = null; 

        } catch (\Exception $e) {
            session()->flash('error', 'Error fetching transactions: ' . $e->getMessage());
            $this->transactions = collect();
        }
    }

    public function updatedFilter()
    {
        $this->fetchTransactions();
    }

    public function updatedDateRange()
    {
        if ($this->dateRange['start'] && $this->dateRange['end']) {
            $this->fetchTransactions();
        }
    }

    public function switchStore($storeId)
    {
        try {
            // Verify user belongs to this store
            if (!Auth::user()->teams()->where('store_id', $storeId)->exists()) {
                abort(403, 'Unauthorized store selection');
            }

            session(['current_store_id' => $storeId]);
            Auth::user()->update(['current_team_id' => $storeId]);
            $this->fetchTransactions();
        } catch (\Exception $e) {
            session()->flash('error', 'Error switching store: ' . $e->getMessage());
        }
    }

    public function handleTransactionClick($transactionId)
    {
        $this->selectedTransaction = Transaction::find($transactionId);
    }

    public function getTransactionColor($type)
    {
        return match ($type) {
            'stock in' => 'bg-green-100',
            'stock out' => 'bg-red-100',
            default => 'bg-gray-100',
        };
    }

    public function exportToExcel()
    {
        if (!$this->transactions || $this->transactions->isEmpty()) {
            session()->flash('error', 'No transactions available to export.');
            return;
        }

        return Excel::download(
            new TransactionsExport($this->transactions),
            'transactions-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.transactions');
    }
}
