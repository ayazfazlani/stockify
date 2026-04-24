<?php

namespace App\Livewire;

use App\Models\InventoryAudit;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Services\InventoryAuditService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class PurchaseOrders extends Component
{
    public $purchaseOrders = [];
    public $items = [];
    public $suppliers = [];

    public bool $showCreateModal = false;
    public string $newSupplierName = '';
    public ?int $selectedPoId = null;
    public array $receiveQuantities = [];
    public array $poForm = [
        'supplier_id' => null,
        'expected_date' => null,
        'notes' => '',
        'lines' => [],
    ];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $storeId = Auth::user()->getCurrentStoreId();
        $tenantId = Auth::user()->tenant_id;

        $this->purchaseOrders = PurchaseOrder::with(['supplier', 'items.item'])
            ->where('store_id', $storeId)
            ->latest()
            ->get()
            ->all();
        $this->items = Item::where('store_id', $storeId)->orderBy('name')->get()->all();
        $this->suppliers = Supplier::where('tenant_id', $tenantId)->where('store_id', $storeId)->orderBy('name')->get()->all();
        $this->receiveQuantities = [];
    }

    public function openCreateModal(): void
    {
        $this->poForm = [
            'supplier_id' => null,
            'expected_date' => now()->addDays(3)->toDateString(),
            'notes' => '',
            'lines' => [['item_id' => null, 'ordered_qty' => 1, 'unit_cost' => 0]],
        ];
        $this->showCreateModal = true;
    }

    public function createSupplier(): void
    {
        $this->validate([
            'newSupplierName' => 'required|string|max:255',
        ]);

        Supplier::create([
            'tenant_id' => Auth::user()->tenant_id,
            'store_id' => Auth::user()->getCurrentStoreId(),
            'name' => $this->newSupplierName,
        ]);

        $this->newSupplierName = '';
        $this->loadData();
        session()->flash('message', 'Supplier created.');
    }

    public function addLine(): void
    {
        $this->poForm['lines'][] = ['item_id' => null, 'ordered_qty' => 1, 'unit_cost' => 0];
    }

    public function removeLine(int $index): void
    {
        unset($this->poForm['lines'][$index]);
        $this->poForm['lines'] = array_values($this->poForm['lines']);
    }

    public function createPurchaseOrder(): void
    {
        $this->validate([
            'poForm.supplier_id' => 'required|exists:suppliers,id',
            'poForm.expected_date' => 'required|date',
            'poForm.lines' => 'required|array|min:1',
            'poForm.lines.*.item_id' => 'required|exists:items,id',
            'poForm.lines.*.ordered_qty' => 'required|integer|min:1',
            'poForm.lines.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $storeId = Auth::user()->getCurrentStoreId();
        $tenantId = Auth::user()->tenant_id;

        DB::transaction(function () use ($storeId, $tenantId) {
            $po = PurchaseOrder::create([
                'tenant_id' => $tenantId,
                'store_id' => $storeId,
                'supplier_id' => $this->poForm['supplier_id'],
                'created_by' => Auth::id(),
                'status' => 'draft',
                'expected_date' => $this->poForm['expected_date'],
                'notes' => $this->poForm['notes'],
            ]);

            $total = 0;
            foreach ($this->poForm['lines'] as $line) {
                $lineTotal = (float) $line['ordered_qty'] * (float) $line['unit_cost'];
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'item_id' => $line['item_id'],
                    'ordered_qty' => $line['ordered_qty'],
                    'received_qty' => 0,
                    'unit_cost' => $line['unit_cost'],
                    'line_total' => $lineTotal,
                ]);
                $total += $lineTotal;
            }
            $po->update(['total_amount' => $total]);
        });

        $this->showCreateModal = false;
        session()->flash('message', 'Purchase order drafted successfully.');
        $this->loadData();
    }

    public function markOrdered(int $poId): void
    {
        $po = PurchaseOrder::findOrFail($poId);
        $po->update(['status' => 'ordered', 'ordered_at' => now()]);
        session()->flash('message', 'Purchase order marked as ordered.');
        $this->loadData();
    }

    public function receiveLine(int $lineId): void
    {
        $line = PurchaseOrderItem::with(['item', 'purchaseOrder'])->findOrFail($lineId);
        $remainingQty = max(0, (int) $line->ordered_qty - (int) $line->received_qty);

        if ($remainingQty <= 0) {
            return;
        }

        $requestedQty = (int) ($this->receiveQuantities[$lineId] ?? 1);
        if ($requestedQty < 1) {
            $requestedQty = 1;
        }
        $receiveQty = min($requestedQty, $remainingQty);

        DB::transaction(function () use ($line, $receiveQty) {
            $item = Item::lockForUpdate()->findOrFail($line->item_id);
            $before = (int) $item->quantity;
            $item->quantity += $receiveQty;
            $item->save();

            $line->update(['received_qty' => $line->received_qty + $receiveQty]);

            app(InventoryAuditService::class)->log(
                $item,
                'po_receive',
                $before,
                $receiveQty,
                'PO receiving',
                ['purchase_order_id' => $line->purchase_order_id]
            );

            $po = $line->purchaseOrder()->with('items')->first();
            $isFullyReceived = $po->items->every(fn($poLine) => $poLine->received_qty >= $poLine->ordered_qty);
            $po->update([
                'status' => $isFullyReceived ? 'received' : 'partial',
                'received_at' => $isFullyReceived ? now() : null,
            ]);
        });

        unset($this->receiveQuantities[$lineId]);
        session()->flash('message', "Received {$receiveQty} unit(s) for the selected line.");
        $this->loadData();
    }

    public function printPurchaseOrder(int $poId)
    {
        $po = PurchaseOrder::with(['supplier', 'items.item', 'creator'])->findOrFail($poId);

        $pdf = Pdf::loadView('pdf.purchase-order', ['po' => $po]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "purchase-order-{$po->id}.pdf"
        );
    }

    public function sharePurchaseOrder(int $poId): void
    {
        $po = PurchaseOrder::with(['supplier', 'items.item'])->findOrFail($poId);

        if (! $po->supplier?->email) {
            session()->flash('error', 'Supplier email is missing for this purchase order.');
            return;
        }

        $pdf = Pdf::loadView('pdf.purchase-order', ['po' => $po])->output();

        Mail::raw("Please find attached Purchase Order #{$po->id}.", function ($message) use ($po, $pdf) {
            $message
                ->to($po->supplier->email)
                ->subject("Purchase Order #{$po->id}")
                ->attachData($pdf, "purchase-order-{$po->id}.pdf", [
                    'mime' => 'application/pdf',
                ]);
        });

        session()->flash('message', 'PO shared with supplier via email.');
    }

    public function render()
    {
        return view('livewire.purchase-orders');
    }
}
