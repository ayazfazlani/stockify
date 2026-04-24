<?php

namespace App\Livewire;

use App\Models\Analytics;
use App\Models\InventoryAudit;
use App\Models\Item;
use App\Models\Supplier;
use App\Mail\LowStockAlertMail;
use App\Services\Notifications\WhatsAppNotifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Dashboard extends Component
{
    public $summary = [];
    public $totalInventoryData = [];
    public $lowStockItems = [];
    public array $marginLeaders = [];
    public $recentAudits = [];
    public $topBrandsData = [];
    public string $summaryJson = '{}';
    public string $topBrandsJson = '[]';
    public string $stockFlowJson = '{}';

    public function mount()
    {
        $this->fetchSummary();
        $this->fetchTotalInventoryData();
        $this->fetchLowStockItems();
        $this->fetchMarginLeaders();
        $this->fetchRecentAudits();
        $this->syncChartPayloads();
    }

    public function fetchSummary()
    {
        if (auth()->check()) {
            $query = Analytics::query();
            
            if (!auth()->user()->hasRole('super admin')) {
                $teamId = Auth::user()->getCurrentStoreId();
                $query->where('analytics.store_id', $teamId);
            }

            $totalInventory = clone $query;
            $stockIn = clone $query;
            $stockOut = clone $query;
            $inventoryEquity = clone $query;

            $totalInventory = $totalInventory->sum('current_quantity');
            $stockIn = $stockIn->sum('total_stock_in');
            $stockOut = $stockOut->sum('total_stock_out');
            $inventoryEquity = $inventoryEquity->sum('inventory_assets');
            
            // Potential Revenue = Current Quantity * Item Price
            $potentialRevenueQuery = clone $query;
            $potentialRevenue = (float) $potentialRevenueQuery
                ->join('items', 'analytics.item_id', '=', 'items.id')
                ->sum(DB::raw('analytics.current_quantity * items.price'));

            // Potential Profit = Potential Revenue - Inventory Equity
            $potentialProfit = $potentialRevenue - $inventoryEquity;

            $this->summary = [
                'totalInventory' => $totalInventory,
                'stockIn' => $stockIn,
                'stockOut' => $stockOut,
                'inventoryEquity' => $inventoryEquity,
                'potentialRevenue' => $potentialRevenue,
                'potentialProfit' => $potentialProfit,
            ];
        } else {
            $this->summary = [
                'totalInventory' => 0,
                'stockIn' => 0,
                'stockOut' => 0,
                'inventoryEquity' => 0,
                'potentialRevenue' => 0,
                'potentialProfit' => 0,
            ];
        }

        $this->syncChartPayloads();
    }

    public function fetchTotalInventoryData()
    {
        if (auth()->check()) {
            $query = Analytics::query();
            
            if (!auth()->user()->hasRole('super admin')) {
                $teamId = Auth::user()->getCurrentStoreId();
                $query->where('store_id', $teamId);
            }

            $data = $query->with('item')
                ->orderBy('current_quantity', 'desc')
                ->take(10)
                ->get();

            $this->totalInventoryData = $data->map(function ($stat) {
                return [
                    'name' => $stat->item->name ?? 'Unknown',
                    'quantity' => $stat->current_quantity,
                ];
            })->values()->all();

            // Brand-wise distribution
            $this->topBrandsData = $data->groupBy(function($stat) {
                return $stat->item->brand ?? 'No Brand';
            })->map(function($group, $brand) {
                return [
                    'brand' => $brand,
                    'count' => $group->sum('current_quantity')
                ];
            })->values()->all();
        }

        $this->syncChartPayloads();
    }

    public function fetchLowStockItems()
    {
        if (auth()->check()) {
            $query = Item::query();
            
            if (!auth()->user()->hasRole('super admin')) {
                $teamId = Auth::user()->getCurrentStoreId();
                $query->where('store_id', $teamId);
            }

            $this->lowStockItems = $query
                ->with('supplier')
                ->whereColumn('quantity', '<=', 'reorder_level')
                ->orderBy('quantity', 'asc')
                ->take(5)
                ->get();
        }
    }

    public function fetchMarginLeaders(): void
    {
        $query = Item::query();

        if (auth()->check() && !auth()->user()->hasRole('super admin')) {
            $query->where('store_id', Auth::user()->getCurrentStoreId());
        }

        $this->marginLeaders = $query
            ->where('quantity', '>', 0)
            ->orderByRaw('(price - cost) * quantity DESC')
            ->take(8)
            ->get()
            ->map(function ($item) {
                $marginValue = max(0, (float) $item->price - (float) $item->cost);
                $marginPct = $item->price > 0 ? ($marginValue / (float) $item->price) * 100 : 0;
                return [
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'qty' => (int) $item->quantity,
                    'margin_value' => $marginValue,
                    'margin_pct' => round($marginPct, 2),
                    'profit_pool' => round($marginValue * (int) $item->quantity, 2),
                ];
            })
            ->all();
    }

    public function fetchRecentAudits(): void
    {
        $query = InventoryAudit::with(['item', 'user'])->latest();

        if (auth()->check() && !auth()->user()->hasRole('super admin')) {
            $query->where('store_id', Auth::user()->getCurrentStoreId());
        }

        $this->recentAudits = $query->take(10)->get();
    }

    public function sendLowStockAlerts(): void
    {
        $alerts = collect($this->lowStockItems)->map(function ($item) {
            return [
                'name' => $item->name,
                'sku' => $item->sku,
                'current' => (int) $item->quantity,
                'reorder_level' => (int) $item->reorder_level,
                'suggested_order' => max(1, (int) $item->reorder_quantity),
                'supplier_email' => $item->supplier?->email,
                'supplier_whatsapp' => $item->supplier?->whatsapp,
            ];
        })->all();

        if (empty($alerts)) {
            session()->flash('error', 'No low stock items to alert.');
            return;
        }

        $emails = collect($alerts)->pluck('supplier_email')->filter()->unique()->values();
        if ($emails->isNotEmpty()) {
            foreach ($emails as $email) {
                Mail::to($email)->send(new LowStockAlertMail($alerts));
            }
        }

        $whatsAppNotifier = app(WhatsAppNotifier::class);
        collect($alerts)->pluck('supplier_whatsapp')->filter()->unique()->each(function ($phone) use ($whatsAppNotifier, $alerts) {
            $message = 'Low stock alert: '.collect($alerts)->take(3)->map(fn($a) => "{$a['name']} ({$a['current']} left)").implode(', ');
            $whatsAppNotifier->send($phone, $message);
        });

        session()->flash('message', 'Low stock alerts dispatched via email/WhatsApp integrations.');
    }

    protected function syncChartPayloads(): void
    {
        $this->summaryJson = json_encode($this->summary, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '{}';
        $this->topBrandsJson = json_encode($this->topBrandsData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '[]';
        $this->stockFlowJson = json_encode([
            'labels' => ['Total Inventory', 'Stock In', 'Stock Out'],
            'values' => [
                (int) ($this->summary['totalInventory'] ?? 0),
                (int) ($this->summary['stockIn'] ?? 0),
                (int) ($this->summary['stockOut'] ?? 0),
            ],
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '{}';
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
