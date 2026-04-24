<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Sale;
use Livewire\Component;
use App\Models\Transaction;
use App\Services\AnalyticsService;
use App\Services\InventoryAuditService;
use App\Services\Notifications\WhatsAppNotifier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class StockOutComponent extends Component
{
    public $items = [];
    public $salesHistory = [];
    public $selectedItems = [];
    public $search = '';
    public $dateRange = [
        'start' => '',
        'end' => ''
    ];

    public $showReceipt = false;
    public $currentSale = null;
    public string $shareEmail = '';
    public string $sharePhone = '';
    public string $customerName = '';
    public string $customerPhone = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->loadItems();
        $this->loadSalesHistory();
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

    public function loadSalesHistory(): void
    {
        $teamId = Auth::user()->getCurrentStoreId();

        $this->salesHistory = Sale::query()
            ->withCount('transactions')
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
        $this->loadSalesHistory();
    }

    #[On('scannedData')]
    public function handleScannedData($code, $scannerId = null)
    {
        $teamId = Auth::user()->getCurrentStoreId();
        
        // 1. Check if it's a SKU
        $item = Item::resolveByCode($code, $teamId);

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

        session()->flash('error', "Code '{$code}' not recognized as product barcode/SKU or available serial.");
    }

    public function addSerializedItem($serial)
    {
        $itemModel = $serial->item;
        $key = array_search($itemModel->id, array_column($this->selectedItems, 'id'));

        if ($key === false) {
            $item = $itemModel->toArray();
            $item['quantity'] = 1;
            $item['sale_price'] = (float) $item['price'];
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
            $item['sale_price'] = (float) $item['price'];
            $this->selectedItems[] = $item;
        } else {
            unset($this->selectedItems[$key]);
            $this->selectedItems = array_values($this->selectedItems);
        }
    }

    public function viewReceipt($saleId)
    {
        $this->currentSale = $this->getSaleWithRelations($saleId);
        if ($this->currentSale) {
            $this->prefillRememberedContact();
            $this->showReceipt = true;
        } else {
            session()->flash('error', 'Sale record not found.');
        }
    }

    public function downloadReceiptPdf(int $saleId)
    {
        $sale = $this->getSaleWithRelations($saleId);

        if (! $sale) {
            session()->flash('error', 'Sale record not found.');
            return null;
        }

        $pdf = Pdf::loadView('pdf.sale-receipt', ['sale' => $sale]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "receipt-{$sale->id}.pdf"
        );
    }

    public function shareReceiptByEmail(int $saleId): void
    {
        $this->validate([
            'shareEmail' => 'required|email',
        ]);

        $sale = $this->getSaleWithRelations($saleId);
        if (! $sale) {
            session()->flash('error', 'Sale record not found.');
            return;
        }

        $pdf = Pdf::loadView('pdf.sale-receipt', ['sale' => $sale])->output();

        Mail::raw("Please find your sales receipt #".str_pad($sale->id, 6, '0', STR_PAD_LEFT)." attached.", function ($message) use ($sale, $pdf) {
            $message
                ->to($this->shareEmail)
                ->subject("Receipt #".str_pad($sale->id, 6, '0', STR_PAD_LEFT))
                ->attachData($pdf, 'receipt-'.$sale->id.'.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });

        $this->rememberContact(email: $this->shareEmail);
        session()->flash('message', 'Receipt sent by email successfully.');
    }

    public function shareReceiptByWhatsApp(int $saleId): void
    {
        $this->validate([
            'sharePhone' => 'required|string|min:7|max:30',
        ]);

        $sale = $this->getSaleWithRelations($saleId);
        if (! $sale) {
            session()->flash('error', 'Sale record not found.');
            return;
        }

        $message = sprintf(
            "Receipt #%s from %s\nDate: %s\nTotal: %s\nThank you for your purchase.",
            str_pad((string) $sale->id, 6, '0', STR_PAD_LEFT),
            $sale->store->name ?? 'Stockify',
            optional($sale->created_at)->format('M d, Y h:i A'),
            number_format((float) $sale->total_amount, 2)
        );

        app(WhatsAppNotifier::class)->send($this->sharePhone, $message);

        $this->rememberContact(phone: $this->sharePhone);
        session()->flash('message', 'Receipt summary shared by WhatsApp successfully.');
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
            ],
            'selectedItems.*.sale_price' => 'required|numeric|min:0',
            'customerName' => 'nullable|string|max:100',
            'customerPhone' => 'nullable|string|max:30',
        ]);

        DB::beginTransaction();
        $teamId = Auth::user()->getCurrentStoreId();

        try {
            // Create the Sale record
            $totalAmount = 0;
            foreach ($this->selectedItems as $item) {
                $totalAmount += $item['sale_price'] * $item['quantity'];
            }

            $sale = \App\Models\Sale::create([
                'store_id' => $teamId,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'tenant_id' => Auth::user()->tenant_id,
                'customer_name' => trim($this->customerName) ?: null,
                'customer_phone' => trim($this->customerPhone) ?: null,
            ]);

            foreach ($this->selectedItems as $item) {
                $itemModel = Item::lockForUpdate()->find($item['id']);

                if ($itemModel) {
                    if ($itemModel->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$itemModel->name}");
                    }

                    $beforeQty = (int) $itemModel->quantity;
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
                        'unit_price' => $item['sale_price'],
                        'total_price' => $item['sale_price'] * $item['quantity'],
                        'created_at' => now(),
                    ]);

                    (new AnalyticsService())->updateAllAnalytics($itemModel, $item['quantity'], 'stock_out');
                    app(InventoryAuditService::class)->log(
                        $itemModel,
                        'stock_out',
                        $beforeQty,
                        -1 * (int) $item['quantity'],
                        'Checkout / stock out'
                    );
                }
            }

            DB::commit();
            
            // Set data for receipt and show it
            $this->currentSale = $this->getSaleWithRelations($sale->id);
            $this->prefillRememberedContact();
            $this->showReceipt = true;
            
            session()->flash('message', 'Stock-out completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }

        $this->reset('selectedItems');
        $this->reset('customerName', 'customerPhone');
        $this->loadItems();
        $this->loadSalesHistory();
    }

    public function render()
    {
        return view('livewire.stock-out');
    }

    private function getSaleWithRelations(int $saleId): ?Sale
    {
        return Sale::with(['transactions', 'store', 'user'])->find($saleId);
    }

    private function contactSessionKey(): string
    {
        $storeId = Auth::user()?->getCurrentStoreId() ?? 'default';
        $userId = Auth::id() ?? 'guest';

        return "receipt_last_contact.user_{$userId}.store_{$storeId}";
    }

    private function prefillRememberedContact(): void
    {
        $contact = Session::get($this->contactSessionKey(), []);
        $this->shareEmail = (string) ($contact['email'] ?? '');
        $this->sharePhone = (string) ($contact['phone'] ?? '');
    }

    private function rememberContact(?string $email = null, ?string $phone = null): void
    {
        $current = Session::get($this->contactSessionKey(), []);

        Session::put($this->contactSessionKey(), [
            'email' => $email ?? ($current['email'] ?? ''),
            'phone' => $phone ?? ($current['phone'] ?? ''),
        ]);
    }
}
