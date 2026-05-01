<?php

namespace App\Livewire;

use App\Enums\PlanFeature;
use App\Imports\ItemsImport;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ItemList extends Component
{
    use WithFileUploads;

    public $items = [];

    public $newItem = [
        'sku' => '',
        'name' => '',
        'cost' => 0,
        'price' => 0,
        'type' => '',
        'brand' => '',
        'quantity' => 0,
    ];

    public $images = [];

    public $search = '';

    public $inStockOnly = false;

    public $isModalOpen = false;

    public $selectedItem = null;

    public $isImportModalOpen = false;

    public $importFile;

    public $suppliers = [];

    public $editForm = [];

    public $isEditModalOpen = false;

    public $tenantSlug;

    public function mount()
    {
        $tenant = tenant();
        $this->tenantSlug = $tenant ? $tenant->slug : Auth::user()->tenant_id;
        $this->fetchItems();
        $this->loadSuppliers();
    }

    public function fetchItems()
    {
        $teamId = Auth::user()->getCurrentStoreId();

        $query = Item::query()
            ->when($teamId, function ($query) use ($teamId) {
                $query->where('store_id', $teamId);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('sku', 'like', '%'.$this->search.'%')
                        ->orWhere('brand', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->inStockOnly, function ($query) {
                $query->where('quantity', '>', 0);
            });

        $this->items = $query->get();
    }

    public function loadSuppliers(): void
    {
        $storeId = Auth::user()->getCurrentStoreId();
        $this->suppliers = Supplier::query()
            ->where('tenant_id', Auth::user()->tenant_id)
            ->where('store_id', $storeId)
            ->orderBy('name')
            ->get()
            ->all();
    }

    public function updatedSearch()
    {
        $this->fetchItems();
    }

    public function updatedInStockOnly()
    {
        $this->fetchItems();
    }

    public function toggleImportModal()
    {
        if (! $this->tenantCanBulkImport()) {
            session()->flash('error', 'Bulk import is not included in your current plan. Upgrade to import items from a file.');

            return;
        }

        $this->isImportModalOpen = ! $this->isImportModalOpen;
    }

    public function importItems()
    {
        if (! $this->tenantCanBulkImport()) {
            session()->flash('error', 'Bulk import is not included in your current plan.');

            return;
        }

        $this->validate(['importFile' => 'required|mimes:xlsx,csv']);
        Excel::import(new ItemsImport, $this->importFile->getRealPath());
        $this->fetchItems();
        $this->toggleImportModal();
    }

    public function selectItem($itemId)
    {
        $this->selectedItem = Item::with('supplier')->find($itemId);
    }

    public function openEditModal(): void
    {
        if (! $this->selectedItem) {
            return;
        }

        $this->editForm = [
            'id' => $this->selectedItem->id,
            'price' => (float) $this->selectedItem->price,
            'reorder_level' => (int) ($this->selectedItem->reorder_level ?? 0),
            'reorder_quantity' => (int) ($this->selectedItem->reorder_quantity ?? 0),
            'supplier_id' => $this->selectedItem->supplier_id,
        ];
        $this->isEditModalOpen = true;
    }

    public function saveItemEdit(): void
    {
        $this->validate([
            'editForm.id' => 'required|exists:items,id',
            'editForm.price' => 'required|numeric|min:0',
            'editForm.reorder_level' => 'required|integer|min:0',
            'editForm.reorder_quantity' => 'required|integer|min:1',
            'editForm.supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $item = Item::findOrFail($this->editForm['id']);
        $item->update([
            'price' => $this->editForm['price'],
            'reorder_level' => $this->editForm['reorder_level'],
            'reorder_quantity' => $this->editForm['reorder_quantity'],
            'supplier_id' => $this->editForm['supplier_id'] ?: null,
        ]);

        $this->isEditModalOpen = false;
        $this->selectItem($item->id);
        $this->fetchItems();
        session()->flash('success', 'Item details updated.');
    }

    public function toggleModal()
    {
        $this->isModalOpen = ! $this->isModalOpen;
        if (! $this->isModalOpen) {
            $this->resetNewItem();
        }
    }

    public function addItem()
    {
        $this->validate([
            'newItem.sku' => 'required|string|max:255',
            'newItem.name' => 'required|string|max:255',
            'newItem.cost' => 'required|numeric|min:0',
            'newItem.price' => 'required|numeric|min:0',
            'newItem.type' => 'required|string|max:255',
            'newItem.brand' => 'required|string|max:255',
            'newItem.quantity' => 'required|numeric|min:0',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $teamId = Auth::user()->getCurrentStoreId();
        $tenant = $this->resolveTenant();

        if (! $tenant || ! $tenant->canAdd(PlanFeature::MAX_ITEMS, Item::where('store_id', $teamId)->count())) {
            session()->flash('error', 'You have reached the maximum number of items allowed for your plan. Please upgrade to add more.');
            $this->toggleModal();

            return;
        }

        $item = Item::create([
            'tenant_id' => Auth::user()->tenant_id,
            'store_id' => $teamId,
            'user_id' => Auth::id(),
            ...$this->newItem,
            'image' => ! empty($this->images) ? $this->images[0]->store('item_images', 'public') : null,
            'images' => array_map(fn ($img) => $img->store('item_images', 'public'), $this->images),
        ]);

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

        (new AnalyticsService)->updateAllAnalytics($item, $item->quantity, 'created');

        $this->fetchItems();
        $this->toggleModal();
        session()->flash('success', 'Item added successfully!');
    }

    private function resetNewItem()
    {
        $this->reset('newItem', 'images');
    }

    protected function tenantCanBulkImport(): bool
    {
        if (Auth::user()?->isSuperAdmin()) {
            return true;
        }

        $tenant = tenant();

        return $tenant && $tenant->hasFeature(PlanFeature::BULK_IMPORT);
    }

    protected function resolveTenant(): ?Tenant
    {
        $resolved = tenant();
        if ($resolved) {
            return $resolved;
        }

        $tenantId = Auth::user()?->tenant_id;
        if (! $tenantId) {
            return null;
        }

        return Tenant::query()->find($tenantId);
    }

    public function render()
    {
        return view('livewire.item-list');
    }
}
