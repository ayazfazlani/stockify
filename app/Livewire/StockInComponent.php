<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\ItemBarcode;
use App\Models\StockIn;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithFileUploads;
use App\Services\AnalyticsService;
use App\Services\InventoryAuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;

class StockInComponent extends Component
{
    use WithFileUploads;

    public $items = [];
    public $transactions = [];
    public $selectedItems = [];
    public $isModalOpen = false;
    public $search = '';
    public $dateRange = [
        'start' => '',
        'end' => ''
    ];
    public $newItem = [
        'sku' => '',
        'name' => '',
        'cost' => '',
        'price' => '',
        'type' => '',
        'brand' => '',
        'quantity' => 0,
        'image' => null,
        'tracking_type' => 'standard',
    ];

    public $scannedSerials = [];
    public $currentSerial = '';

    public $isScanningForSku = false;
    public $additionalCodes = '';

    public function mount()
    {
        $this->loadItems();
        $this->loadTransactions();
    }

    public function loadItems()
    {
        $teamId = Auth::user()->getCurrentStoreId();
        $this->items = Item::where('store_id', $teamId)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function loadTransactions()
    {
        $teamId = Auth::user()->getCurrentStoreId();

        $this->transactions = Transaction::where('type', 'stock in')
            ->when($teamId, fn($q) => $q->where('store_id', $teamId))
            ->when(
                $this->dateRange['start'] && $this->dateRange['end'],
                fn($q) => $q->whereBetween('date', [
                    $this->dateRange['start'],
                    $this->dateRange['end']
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
        // If scanned from the modal's scanner
        if ($scannerId === 'modal-scanner') {
            $this->newItem['sku'] = $code;
            $this->isScanningForSku = false;
            session()->flash('success', "SKU captured: {$code}");
            return;
        }

        // Default: Stock selection scanner
        $teamId = Auth::user()->getCurrentStoreId();
        $item = Item::resolveByCode($code, $teamId);

        if ($item) {
            $this->toggleItemSelection($item->id);
            session()->flash('success', "Item auto-selected: {$item->name}");
        } else {
            session()->flash('error', "Item with SKU '{$code}' not found.");
        }
    }

    public function toggleItemSelection($itemId)
    {
        $key = array_search($itemId, array_column($this->selectedItems, 'id'));

        if ($key === false) {
            $item = Item::find($itemId)->toArray();
            $item['quantity'] = 1;
            $this->selectedItems[] = $item;
        } else {
            unset($this->selectedItems[$key]);
            $this->selectedItems = array_values($this->selectedItems);
        }
    }

    public function addItem()
    {
        $teamId = Auth::user()->getCurrentStoreId();
        
        $this->validate([
            'newItem.sku' => [
                'required',
                'string',
                Rule::unique('items', 'sku')->where('store_id', $teamId)
            ],
            'newItem.name' => 'required|string',
            'newItem.cost' => 'required|numeric|min:0',
            'newItem.price' => 'required|numeric|min:0',
            'newItem.type' => 'required|string',
            'newItem.brand' => 'required|string',
            'newItem.quantity' => $this->newItem['tracking_type'] === 'standard' ? 'required|integer|min:0' : 'nullable|integer',
            'newItem.tracking_type' => 'required|in:standard,serialized',
            'newItem.image' => 'nullable|image|max:2048',
            'additionalCodes' => 'nullable|string',
        ]);

        if ($this->newItem['tracking_type'] === 'serialized' && count($this->scannedSerials) === 0) {
            $this->addError('scannedSerials', 'Please scan at least one serial number for serialized items.');
            return;
        }

        $finalQuantity = $this->newItem['tracking_type'] === 'serialized' ? count($this->scannedSerials) : $this->newItem['quantity'];

        $imagePath = $this->newItem['image'] ? $this->newItem['image']->store('item_images', 'public') : null;

        DB::beginTransaction();
        try {
            $item = Item::create([
                'sku' => $this->newItem['sku'],
                'name' => $this->newItem['name'],
                'cost' => $this->newItem['cost'],
                'price' => $this->newItem['price'],
                'type' => $this->newItem['type'],
                'brand' => $this->newItem['brand'],
                'quantity' => $finalQuantity,
                'tracking_type' => $this->newItem['tracking_type'],
                'image' => $imagePath,
                'store_id' => $teamId,
                'tenant_id' => Auth::user()->tenant_id,
            ]);

            if ($this->newItem['tracking_type'] === 'serialized') {
                foreach ($this->scannedSerials as $serialNumber) {
                    \App\Models\ItemSerial::create([
                        'item_id' => $item->id,
                        'serial_number' => $serialNumber,
                        'status' => 'available',
                        'store_id' => $teamId,
                        'tenant_id' => Auth::user()->tenant_id,
                    ]);
                }
            }

            $codes = collect(explode(',', (string) $this->additionalCodes))
                ->map(fn($value) => trim($value))
                ->filter()
                ->unique()
                ->values();

            if ($codes->isNotEmpty()) {
                $existingCodes = ItemBarcode::query()
                    ->where('store_id', $teamId)
                    ->whereIn('code', $codes->all())
                    ->pluck('code')
                    ->all();

                if (! empty($existingCodes)) {
                    throw new \RuntimeException('These barcodes are already in use: '.implode(', ', $existingCodes));
                }

                foreach ($codes as $code) {
                    ItemBarcode::create([
                        'item_id' => $item->id,
                        'tenant_id' => Auth::user()->tenant_id,
                        'store_id' => $teamId,
                        'code' => $code,
                    ]);
                }
            }

            Transaction::create([
                'item_id' => $item->id,
                'store_id' => $teamId,
                'user_id' => Auth::id(),
                'item_name' => $item->name,
                'type' => 'created',
                'quantity' => $item->quantity,
                'unit_price' => $item->cost,
                'total_price' => $item->cost * $item->quantity,
                'date' => now(),
            ]);

            (new AnalyticsService())->updateAllAnalytics($item, $item->quantity, 'stock_in');

            DB::commit();
            session()->flash('message', 'Item added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error adding item: ' . $e->getMessage());
        }

        $this->reset(['newItem', 'scannedSerials', 'currentSerial', 'additionalCodes']);
        $this->loadItems();
        $this->isModalOpen = false;
    }

    public function handleStockIn()
    {
        DB::beginTransaction();
        $teamId = Auth::user()->getCurrentStoreId();
        try {
            foreach ($this->selectedItems as $item) {
                $itemModel = Item::find($item['id']);
                if ($itemModel) {
                    $beforeQty = (int) $itemModel->quantity;
                    $itemModel->quantity += $item['quantity'];
                    $itemModel->save();

                    Transaction::create([
                        'item_id' => $itemModel->id,
                        'store_id' => $teamId,
                        'user_id' => Auth::id(),
                        'item_name' => $itemModel->name,
                        'type' => 'stock in',
                        'quantity' => $item['quantity'],
                        'unit_price' => $itemModel->cost,
                        'total_price' => $itemModel->cost * $item['quantity'],
                        'date' => now(),
                    ]);

                    (new AnalyticsService())->updateAllAnalytics($itemModel, $item['quantity'], 'stock_in');
                    app(InventoryAuditService::class)->log(
                        $itemModel,
                        'stock_in',
                        $beforeQty,
                        (int) $item['quantity'],
                        'Manual stock in'
                    );
                }
            }

            DB::commit();
            session()->flash('message', 'Stock-in completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error during stock-in: ' . $e->getMessage());
        }

        $this->reset('selectedItems');
        $this->loadItems();
        $this->loadTransactions();
    }

    public function addSerial()
    {
        $this->currentSerial = trim($this->currentSerial);
        if (!$this->currentSerial) return;

        if (in_array($this->currentSerial, $this->scannedSerials)) {
            $this->addError('currentSerial', 'This serial number is already in the list.');
            return;
        }

        $this->scannedSerials[] = $this->currentSerial;
        $this->currentSerial = '';
    }

    public function removeSerial($index)
    {
        unset($this->scannedSerials[$index]);
        $this->scannedSerials = array_values($this->scannedSerials);
    }

    public function render()
    {
        return view('livewire.stock-in');
    }
}
