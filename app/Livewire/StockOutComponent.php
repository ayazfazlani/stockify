<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Models\Transaction;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class StockOutComponent extends Component
{
    public $items = [];
    public $transactions = [];
    public $selectedItems = [];
    public $search = '';
    public $dateRange = [
        'start' => '',
        'end' => ''
    ];

    public $showReceipt = false;
    public $currentSale = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->loadItems();
        $this->loadTransactions();
    }

    public function loadItems()
    {
        $teamId = Auth::user()->getCurrentStoreId();

        $this->items = Item::when(
            !Auth::user()->hasRole('super admin'),
            fn($q) => $q->where('store_id', $teamId)
        )
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function loadTransactions()
    {
        $teamId = Auth::user()->getCurrentStoreId();

        $this->transactions = Transaction::where('type', 'stock out')
            ->when($teamId, fn($q) => $q->where('store_id', $teamId))
            ->when(
                $this->dateRange['start'] && $this->dateRange['end'],
                fn($q) => $q->whereBetween('created_at', [
                    $this->dateRange['start'] . ' 00:00:00',
                    $this->dateRange['end'] . ' 23:59:59'
                ])
            )
            ->latest()
            ->get();
    }

    public function updatedSearch()
    {
        $this->loadItems();
    }

    public function updatedDateRange()
    {
        $this->loadTransactions();
    }

    #[On('scannedData')]
    public function handleScannedData($code, $scannerId = null)
    {
        $teamId = Auth::user()->getCurrentStoreId();
        
        // 1. Check if it's a SKU
        $item = Item::where('sku', $code)
            ->where('store_id', $teamId)
            ->first();

        if ($item) {
            $this->toggleItemSelection($item->id);
            session()->flash('success', "Item auto-selected: {$item->name}");
            return;
        }

        // 2. Check if it's a Serial Number
        $serial = \App\Models\ItemSerial::where('serial_number', $code)
            ->where('store_id', $teamId)
            ->where('status', 'available')
            ->first();

        if ($serial) {
            $this->addSerializedItem($serial);
            session()->flash('success', "Serial detected: {$serial->serial_number} ({$serial->item->name})");
            return;
        }

        session()->flash('error', "Code '{$code}' not recognized as SKU or Available Serial.");
    }

    public function addSerializedItem($serial)
    {
        $itemModel = $serial->item;
        $key = array_search($itemModel->id, array_column($this->selectedItems, 'id'));

        if ($key === false) {
            $item = $itemModel->toArray();
            $item['quantity'] = 1;
            $item['selected_serials'] = [$serial->serial_number];
            $this->selectedItems[] = $item;
        } else {
            if (!in_array($serial->serial_number, $this->selectedItems[$key]['selected_serials'])) {
                $this->selectedItems[$key]['selected_serials'][] = $serial->serial_number;
                $this->selectedItems[$key]['quantity'] = count($this->selectedItems[$key]['selected_serials']);
            }
        }
    }

    public function toggleItemSelection($itemId)
    {
        $key = array_search($itemId, array_column($this->selectedItems, 'id'));

        if ($key === false) {
            $item = Item::findOrFail($itemId)->toArray();
            $item['quantity'] = 1;
            $this->selectedItems[] = $item;
        } else {
            unset($this->selectedItems[$key]);
            $this->selectedItems = array_values($this->selectedItems);
        }
    }

    public function viewReceipt($saleId)
    {
        $this->currentSale = \App\Models\Sale::with(['transactions', 'store', 'user'])->find($saleId);
        if ($this->currentSale) {
            $this->showReceipt = true;
        } else {
            session()->flash('error', 'Sale record not found.');
        }
    }

    public function handleStockOut()
    {
        $this->validate([
            'selectedItems.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $itemId = $this->selectedItems[$index]['id'];
                    $item = Item::find($itemId);

                    if (!$item) {
                        $fail("Item not found");
                        return;
                    }

                    if ($value > $item->quantity) {
                        $fail("Quantity for {$item->name} exceeds available stock ({$item->quantity})");
                    }
                }
            ]
        ]);

        DB::beginTransaction();
        $teamId = Auth::user()->getCurrentStoreId();

        try {
            // Create the Sale record
            $totalAmount = 0;
            foreach ($this->selectedItems as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            $sale = \App\Models\Sale::create([
                'store_id' => $teamId,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'tenant_id' => Auth::user()->tenant_id,
            ]);

            foreach ($this->selectedItems as $item) {
                $itemModel = Item::lockForUpdate()->find($item['id']);

                if ($itemModel) {
                    if ($itemModel->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$itemModel->name}");
                    }

                    $itemModel->quantity -= $item['quantity'];
                    $itemModel->save();

                    // If serialized, mark serials as sold
                    if (isset($item['selected_serials'])) {
                        \App\Models\ItemSerial::whereIn('serial_number', $item['selected_serials'])
                            ->where('item_id', $itemModel->id)
                            ->update(['status' => 'sold']);
                    }

                    Transaction::create([
                        'sale_id' => $sale->id,
                        'item_id' => $itemModel->id,
                        'store_id' => $teamId,
                        'user_id' => Auth::id(),
                        'item_name' => $itemModel->name,
                        'type' => 'stock out',
                        'quantity' => $item['quantity'],
                        'unit_price' => $itemModel->price,
                        'total_price' => $itemModel->price * $item['quantity'],
                        'created_at' => now(),
                    ]);

                    (new AnalyticsService())->updateAllAnalytics($itemModel, $item['quantity'], 'stock_out');
                }
            }

            DB::commit();
            
            // Set data for receipt and show it
            $this->currentSale = \App\Models\Sale::with(['transactions', 'store', 'user'])->find($sale->id);
            $this->showReceipt = true;
            
            session()->flash('message', 'Stock-out completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }

        $this->reset('selectedItems');
        $this->loadItems();
        $this->loadTransactions();
    }

    public function render()
    {
        return view('livewire.stock-out');
    }
}
