<?php

namespace App\Livewire;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseTracker extends Component
{
    use WithPagination;

    public $amount = '';
    public $category = '';
    public $description = '';
    public $expense_date = '';
    
    // predefined categories for the UI
    public $categories = [
        'Snacks/Food',
        'Utilities',
        'Rent',
        'Salaries',
        'Office Supplies',
        'Maintenance',
        'Transport/Fuel',
        'Other'
    ];

    public function mount()
    {
        $this->expense_date = Carbon::today()->format('Y-m-d');
    }

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'category' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'expense_date' => 'required|date',
    ];

    public function saveExpense()
    {
        $this->validate();

        $storeId = Auth::user()->getCurrentStoreId();
        $tenantId = tenant('id');

        Expense::create([
            'tenant_id' => $tenantId,
            'store_id' => $storeId,
            'user_id' => Auth::id(),
            'amount' => $this->amount,
            'category' => $this->category,
            'description' => $this->description,
            'expense_date' => $this->expense_date,
        ]);

        $this->reset(['amount', 'description']);
        // Keep category and date as they might enter multiple for the same day/category
        
        session()->flash('message', 'Expense recorded successfully.');
    }

    public function deleteExpense($id)
    {
        $expense = Expense::find($id);
        if ($expense && $expense->store_id === Auth::user()->getCurrentStoreId()) {
            $expense->delete();
            session()->flash('message', 'Expense deleted.');
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $storeId = Auth::user()->getCurrentStoreId();

        $expenses = Expense::where('store_id', $storeId)
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $todayTotal = Expense::where('store_id', $storeId)
            ->whereDate('expense_date', Carbon::today())
            ->sum('amount');

        $monthTotal = Expense::where('store_id', $storeId)
            ->whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('amount');

        return view('livewire.expense-tracker', compact('expenses', 'todayTotal', 'monthTotal'));
    }
}
