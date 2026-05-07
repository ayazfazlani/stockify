<?php

namespace App\Livewire;

use App\Mail\LowStockAlertMail;
use App\Models\InventoryAudit;
use App\Models\Item;
use App\Models\Store;
use App\Models\User;
use App\Services\Notifications\WhatsAppNotifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    // Public properties
    public $summary = [];

    public $totalInventoryData = [];

    public $lowStockItems = [];

    public array $marginLeaders = [];

    public $recentAudits = [];

    public $topBrandsData = [];

    public string $summaryJson = '{}';

    public string $topBrandsJson = '[]';

    public string $stockFlowJson = '{}';

    // Store and team filtering
    public $selectedStoreId = null;

    public $selectedUserId = null;

    public $dateRange = 'current_month';

    public $startDate = null;

    public $endDate = null;

    // Available options for filters
    public $availableStores = [];

    public $availableUsers = [];

    // UI State
    public $isLoading = false;

    // Debug info
    public $debugInfo = [];

    protected $currentTenantId = null;

    protected $currentStoreId = null;

    protected $listeners = ['refreshDashboard' => 'refreshData', 'storeChanged' => 'handleStoreChange'];

    public function mount()
    {
        if (! Auth::user()->can('view analytics')) {
            abort(403);
        }

        $this->initializeTenant();
        $this->loadAvailableFilters();
        $this->setDefaultDateRange();
        $this->refreshData();
    }

    protected function initializeTenant()
    {
        if (auth()->check()) {
            $this->currentTenantId = Auth::user()->tenant_id;
            $this->currentStoreId = Auth::user()->getCurrentStoreId();

            if (! $this->selectedStoreId) {
                $this->selectedStoreId = $this->currentStoreId;
            }

            // Debug: Log the store IDs
            \Log::info('Dashboard initializeTenant', [
                'current_store_id' => $this->currentStoreId,
                'selected_store_id' => $this->selectedStoreId,
                'user_id' => Auth::id(),
                'user_stores' => Auth::user()->stores()->pluck('stores.id')->toArray(),
            ]);
        }
    }

    protected function loadAvailableFilters()
    {
        if (! auth()->check()) {
            return;
        }

        $user = Auth::user();

        // Get stores - using the same pattern as ItemList
        $this->availableStores = Store::query()
            ->where('tenant_id', $this->currentTenantId)
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->toArray();

        // Get users from the same tenant
        $this->availableUsers = User::where('tenant_id', $this->currentTenantId)
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get(['id', 'name', 'email'])
            ->toArray();
    }

    protected function setDefaultDateRange()
    {
        $now = now();

        switch ($this->dateRange) {
            case 'current_month':
                $this->startDate = $now->copy()->startOfMonth()->toDateString();
                $this->endDate = $now->copy()->endOfMonth()->toDateString();
                break;
            case 'last_month':
                $this->startDate = $now->copy()->subMonth()->startOfMonth()->toDateString();
                $this->endDate = $now->copy()->subMonth()->endOfMonth()->toDateString();
                break;
            case 'current_quarter':
                $this->startDate = $now->copy()->startOfQuarter()->toDateString();
                $this->endDate = $now->copy()->endOfQuarter()->toDateString();
                break;
            case 'current_year':
                $this->startDate = $now->copy()->startOfYear()->toDateString();
                $this->endDate = $now->copy()->endOfYear()->toDateString();
                break;
        }
    }

    public function handleStoreChange($storeId)
    {
        $this->selectedStoreId = $storeId;
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->isLoading = true;

        $this->fetchSummary();
        $this->fetchTotalInventoryData();
        $this->fetchLowStockItems();
        $this->fetchMarginLeaders();
        $this->fetchRecentAudits();
        $this->syncChartPayloads();

        $this->isLoading = false;
    }

    /**
     * Fetch summary statistics - COMPLETELY REWRITTEN
     */
    public function fetchSummary()
    {
        if (! auth()->check()) {
            $this->summary = $this->getEmptySummary();

            return;
        }

        try {
            // Determine which store ID to use
            $storeId = $this->selectedStoreId;

            // If no store selected, get from user's current store
            if (! $storeId) {
                $storeId = Auth::user()->getCurrentStoreId();
            }

            // If still no store, get first store from user's stores
            if (! $storeId) {
                $firstStore = Auth::user()->stores()->first();
                $storeId = $firstStore ? $firstStore->id : null;
            }

            // If still no store, get any store from tenant
            if (! $storeId && $this->currentTenantId) {
                $anyStore = Store::where('tenant_id', $this->currentTenantId)->first();
                $storeId = $anyStore ? $anyStore->id : null;
            }

            // Debug info
            $this->debugInfo['store_id_used'] = $storeId;
            $this->debugInfo['selected_store_id'] = $this->selectedStoreId;
            $this->debugInfo['current_store_id_method'] = Auth::user()->getCurrentStoreId();

            if (! $storeId) {
                \Log::warning('No store ID found for dashboard summary');
                $this->summary = $this->getEmptySummary();

                return;
            }

            // Get total inventory from items table
            $totalInventory = Item::where('store_id', $storeId)->sum('quantity');

            // Get inventory equity
            $inventoryEquity = Item::where('store_id', $storeId)
                ->sum(DB::raw('quantity * cost'));

            // Get potential revenue
            $potentialRevenue = Item::where('store_id', $storeId)
                ->sum(DB::raw('quantity * price'));

            $potentialProfit = $potentialRevenue - $inventoryEquity;

            // Get stock in/out from inventory_audits
            $stockInQuery = InventoryAudit::where('store_id', $storeId)
                ->where(function ($q) {
                    $q->where('action', 'stock_in')->orWhere('action', 'in');
                });

            $stockOutQuery = InventoryAudit::where('store_id', $storeId)
                ->where(function ($q) {
                    $q->where('action', 'stock_out')->orWhere('action', 'out');
                });

            if ($this->startDate && $this->endDate) {
                $stockInQuery->whereBetween('created_at', [$this->startDate.' 00:00:00', $this->endDate.' 23:59:59']);
                $stockOutQuery->whereBetween('created_at', [$this->startDate.' 00:00:00', $this->endDate.' 23:59:59']);
            }

            if ($this->selectedUserId && $this->selectedUserId !== 'all') {
                $stockInQuery->where('user_id', $this->selectedUserId);
                $stockOutQuery->where('user_id', $this->selectedUserId);
            }

            $stockIn = (int) $stockInQuery->sum('change_qty');
            $stockOut = abs((int) $stockOutQuery->sum('change_qty'));

            // Debug: Log the actual values
            \Log::info('Dashboard Summary Values', [
                'store_id' => $storeId,
                'total_inventory' => $totalInventory,
                'stock_in' => $stockIn,
                'stock_out' => $stockOut,
                'inventory_equity' => $inventoryEquity,
                'potential_revenue' => $potentialRevenue,
            ]);

            $this->summary = [
                'totalInventory' => (int) $totalInventory,
                'stockIn' => $stockIn,
                'stockOut' => $stockOut,
                'inventoryEquity' => round((float) $inventoryEquity, 2),
                'potentialRevenue' => round((float) $potentialRevenue, 2),
                'potentialProfit' => round((float) $potentialProfit, 2),
            ];

            // Update the selected store ID in the UI for display
            if ($storeId && ! $this->selectedStoreId) {
                $this->selectedStoreId = $storeId;
            }

        } catch (\Exception $e) {
            \Log::error('Error fetching summary: '.$e->getMessage());
            $this->summary = $this->getEmptySummary();
        }
    }

    protected function getEmptySummary()
    {
        return [
            'totalInventory' => 0,
            'stockIn' => 0,
            'stockOut' => 0,
            'inventoryEquity' => 0,
            'potentialRevenue' => 0,
            'potentialProfit' => 0,
        ];
    }

    /**
     * Fetch total inventory data
     */
    public function fetchTotalInventoryData()
    {
        if (! auth()->check()) {
            $this->totalInventoryData = [];
            $this->topBrandsData = [];

            return;
        }

        try {
            $storeId = $this->selectedStoreId ?? Auth::user()->getCurrentStoreId();

            if (! $storeId) {
                $this->totalInventoryData = [];
                $this->topBrandsData = [];

                return;
            }

            $items = Item::where('store_id', $storeId)
                ->where('quantity', '>', 0)
                ->orderBy('quantity', 'desc')
                ->take(10)
                ->get();

            $this->totalInventoryData = $items->map(function ($item) {
                return [
                    'name' => $item->name ?? 'Unknown',
                    'quantity' => (int) $item->quantity,
                    'sku' => $item->sku ?? 'N/A',
                    'value' => (float) ($item->quantity * $item->price),
                ];
            })->values()->all();

            // Brand-wise distribution
            $this->topBrandsData = $items->groupBy(function ($item) {
                return $item->brand ?? 'No Brand';
            })->map(function ($group, $brand) {
                return [
                    'brand' => $brand,
                    'count' => $group->sum('quantity'),
                    'total_value' => $group->sum(function ($item) {
                        return $item->quantity * $item->price;
                    }),
                ];
            })->values()->all();

        } catch (\Exception $e) {
            \Log::error('Error fetching inventory data: '.$e->getMessage());
            $this->totalInventoryData = [];
            $this->topBrandsData = [];
        }
    }

    /**
     * Fetch low stock items
     */
    public function fetchLowStockItems()
    {
        if (! auth()->check()) {
            $this->lowStockItems = collect();

            return;
        }

        try {
            $storeId = $this->selectedStoreId ?? Auth::user()->getCurrentStoreId();

            if (! $storeId) {
                $this->lowStockItems = collect();

                return;
            }

            $this->lowStockItems = Item::with('supplier')
                ->where('store_id', $storeId)
                ->whereColumn('quantity', '<=', 'reorder_level')
                ->orderBy('quantity', 'asc')
                ->take(5)
                ->get();

        } catch (\Exception $e) {
            \Log::error('Error fetching low stock items: '.$e->getMessage());
            $this->lowStockItems = collect();
        }
    }

    /**
     * Fetch margin leaders
     */
    public function fetchMarginLeaders(): void
    {
        if (! auth()->check()) {
            $this->marginLeaders = [];

            return;
        }

        try {
            $storeId = $this->selectedStoreId ?? Auth::user()->getCurrentStoreId();

            if (! $storeId) {
                $this->marginLeaders = [];

                return;
            }

            $items = Item::where('store_id', $storeId)
                ->where('quantity', '>', 0)
                ->orderByRaw('(price - cost) * quantity DESC')
                ->take(8)
                ->get();

            $this->marginLeaders = $items->map(function ($item) {
                $marginValue = max(0, (float) $item->price - (float) $item->cost);
                $marginPct = $item->price > 0 ? ($marginValue / (float) $item->price) * 100 : 0;
                $profitPool = $marginValue * (int) $item->quantity;

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'qty' => (int) $item->quantity,
                    'price' => (float) $item->price,
                    'cost' => (float) $item->cost,
                    'margin_value' => round($marginValue, 2),
                    'margin_pct' => round($marginPct, 2),
                    'profit_pool' => round($profitPool, 2),
                ];
            })->all();

        } catch (\Exception $e) {
            \Log::error('Error fetching margin leaders: '.$e->getMessage());
            $this->marginLeaders = [];
        }
    }

    /**
     * Fetch recent inventory audits
     */
    public function fetchRecentAudits(): void
    {
        if (! auth()->check()) {
            $this->recentAudits = collect();

            return;
        }

        try {
            $storeId = $this->selectedStoreId ?? Auth::user()->getCurrentStoreId();

            if (! $storeId) {
                $this->recentAudits = collect();

                return;
            }

            $query = InventoryAudit::with(['item', 'user'])
                ->where('store_id', $storeId)
                ->latest()
                ->take(10);

            if ($this->selectedUserId && $this->selectedUserId !== 'all') {
                $query->where('user_id', $this->selectedUserId);
            }

            if ($this->startDate && $this->endDate) {
                $query->whereBetween('created_at', [$this->startDate.' 00:00:00', $this->endDate.' 23:59:59']);
            }

            $this->recentAudits = $query->get();

        } catch (\Exception $e) {
            \Log::error('Error fetching recent audits: '.$e->getMessage());
            $this->recentAudits = collect();
        }
    }

    public function sendLowStockAlerts(): void
    {
        $this->fetchLowStockItems();

        if ($this->lowStockItems->isEmpty()) {
            session()->flash('error', 'No low stock items to alert.');

            return;
        }

        $alerts = collect($this->lowStockItems)->map(function ($item) {
            return [
                'name' => $item->name,
                'sku' => $item->sku,
                'current' => (int) $item->quantity,
                'reorder_level' => (int) $item->reorder_level,
                'suggested_order' => max(1, (int) $item->reorder_quantity),
                'supplier_name' => $item->supplier?->name,
                'supplier_email' => $item->supplier?->email,
                'supplier_whatsapp' => $item->supplier?->whatsapp,
            ];
        })->all();

        $emails = collect($alerts)->pluck('supplier_email')->filter()->unique();
        if ($emails->isNotEmpty()) {
            foreach ($emails as $email) {
                try {
                    Mail::to($email)->send(new LowStockAlertMail($alerts));
                } catch (\Exception $e) {
                    \Log::error('Failed to send low stock email: '.$e->getMessage());
                }
            }
        }

        $whatsAppNotifier = app(WhatsAppNotifier::class);
        $whatsappNumbers = collect($alerts)->pluck('supplier_whatsapp')->filter()->unique();

        foreach ($whatsappNumbers as $phone) {
            try {
                $message = '🚨 Low Stock Alert:\n\n';
                $message .= collect($alerts)->take(5)->map(function ($a) {
                    return "📦 {$a['name']}\n   Stock: {$a['current']} (Min: {$a['reorder_level']})\n   Suggested: {$a['suggested_order']} units";
                })->implode("\n\n");
                $whatsAppNotifier->send($phone, $message);
            } catch (\Exception $e) {
                \Log::error('Failed to send WhatsApp alert: '.$e->getMessage());
            }
        }

        session()->flash('message', 'Low stock alerts dispatched.');
    }

    public function updatedDateRange()
    {
        $this->setDefaultDateRange();
        $this->refreshData();
    }

    public function updatedStartDate()
    {
        if ($this->startDate && $this->endDate) {
            $this->dateRange = 'custom';
            $this->refreshData();
        }
    }

    public function updatedEndDate()
    {
        if ($this->startDate && $this->endDate) {
            $this->dateRange = 'custom';
            $this->refreshData();
        }
    }

    public function updatedSelectedStoreId()
    {
        $this->refreshData();
    }

    public function updatedSelectedUserId()
    {
        $this->refreshData();
    }

    public function resetFilters()
    {
        $this->selectedStoreId = null;
        $this->selectedUserId = null;
        $this->dateRange = 'current_month';
        $this->setDefaultDateRange();
        $this->loadAvailableFilters();
        $this->refreshData();
        session()->flash('message', 'All filters have been reset.');
    }

    public function exportData()
    {
        $filename = 'dashboard_export_'.now()->format('Y-m-d_His').'.csv';
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['Metric', 'Value', 'Date Range', 'Store ID', 'User ID']);
        fputcsv($handle, ['Total Inventory', $this->summary['totalInventory'] ?? 0, $this->dateRange, $this->selectedStoreId ?? 'All', $this->selectedUserId ?? 'All']);
        fputcsv($handle, ['Stock In', $this->summary['stockIn'] ?? 0, $this->dateRange, $this->selectedStoreId ?? 'All', $this->selectedUserId ?? 'All']);
        fputcsv($handle, ['Stock Out', $this->summary['stockOut'] ?? 0, $this->dateRange, $this->selectedStoreId ?? 'All', $this->selectedUserId ?? 'All']);
        fputcsv($handle, ['Inventory Equity', $this->summary['inventoryEquity'] ?? 0, $this->dateRange, $this->selectedStoreId ?? 'All', $this->selectedUserId ?? 'All']);
        fputcsv($handle, ['Potential Revenue', $this->summary['potentialRevenue'] ?? 0, $this->dateRange, $this->selectedStoreId ?? 'All', $this->selectedUserId ?? 'All']);
        fputcsv($handle, ['Potential Profit', $this->summary['potentialProfit'] ?? 0, $this->dateRange, $this->selectedStoreId ?? 'All', $this->selectedUserId ?? 'All']);

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, ['Content-Type' => 'text/csv']);
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

    public function getUserRoleInfo()
    {
        if (! auth()->check()) {
            return ['role' => 'guest', 'can_view_all' => false];
        }

        $user = Auth::user();

        return [
            'role' => $user->getRoleNames()->first() ?? 'user',
            'can_view_all' => $user->hasRole('super admin') || $user->hasRole('tenant_admin'),
            'store_count' => $user->stores()->count(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'userRoleInfo' => $this->getUserRoleInfo(),
            'filtersApplied' => ($this->selectedStoreId && $this->selectedStoreId !== 'all') ||
                ($this->selectedUserId && $this->selectedUserId !== 'all') ||
                $this->dateRange !== 'current_month',
            'debugInfo' => $this->debugInfo, // Add this to your blade if needed
        ]);
    }
}
