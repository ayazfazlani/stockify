<?php

namespace App\Livewire;

use App\Models\Analytics;
use App\Models\InventoryAudit;
use App\Models\Item;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use App\Mail\LowStockAlertMail;
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
    public $dateRange = 'current_month'; // current_month, last_month, current_quarter, current_year
    public $startDate = null;
    public $endDate = null;

    // Available options for filters
    public $availableStores = [];
    public $availableUsers = [];

    // UI State
    public $isLoading = false;

    protected $currentTenantId = null;

    // Listeners for Livewire events
    protected $listeners = ['refreshDashboard' => 'refreshData', 'storeChanged' => 'handleStoreChange'];

    public function mount()
    {
        $this->initializeTenant();
        $this->loadAvailableFilters();
        $this->setDefaultDateRange();
        $this->refreshData();
    }

    /**
     * Initialize tenant context
     */
    protected function initializeTenant()
    {
        if (auth()->check()) {
            $this->currentTenantId = Auth::user()->tenant_id;

            // If user has a default store, use it
            if (!$this->selectedStoreId) {
                $this->selectedStoreId = Auth::user()->getCurrentStoreId();
            }

            // If still no store, get first available store for tenant
            if (!$this->selectedStoreId && $this->currentTenantId) {
                $store = Store::where('tenant_id', $this->currentTenantId)->first();
                if ($store) {
                    $this->selectedStoreId = $store->id;
                }
            }
        }
    }

    /**
     * Load available stores and users for filtering
     */
    // protected function loadAvailableFilters()
    // {
    //     if (!auth()->check()) {
    //         return;
    //     }

    //     $user = Auth::user();

    //     // Load stores based on user role
    //     if ($user->hasRole('super admin') || $user->hasRole('tenant_admin')) {
    //         // Super admin or tenant admin can see all stores in tenant
    //         $this->availableStores = Store::where('tenant_id', $this->currentTenantId)
    //             ->orderBy('name')
    //             ->get(['id', 'name'])
    //             ->toArray();

    //         // Can see all users in tenant
    //         $this->availableUsers = User::where('tenant_id', $this->currentTenantId)
    //             ->orderBy('name')
    //             ->get(['id', 'name', 'email'])
    //             ->toArray();
    //     } else {
    //         // Regular user can only see their assigned stores
    //         $this->availableStores = $user->stores()
    //             ->orderBy('name')
    //             ->get(['stores.id', 'stores.name'])
    //             ->toArray();

    //         // Can only see users who share at least one store
    //         $storeIds = $user->stores()->pluck('stores.id');
    //         $this->availableUsers = User::whereHas('stores', function ($query) use ($storeIds) {
    //             $query->whereIn('stores.id', $storeIds);
    //         })
    //             ->where('tenant_id', $this->currentTenantId)
    //             ->orderBy('name')
    //             ->get(['id', 'name', 'email'])
    //             ->toArray();
    //     }
    // }

    /**
     * Load available stores and users for filtering - WORKAROUND VERSION
     */
    protected function loadAvailableFilters()
    {
        if (!auth()->check()) {
            return;
        }

        $user = Auth::user();

        // Load stores based on user role
        if ($user->hasRole('super admin') || $user->hasRole('tenant_admin')) {
            // Super admin or tenant admin can see all stores in tenant
            $this->availableStores = Store::where('tenant_id', $this->currentTenantId)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray();

            // Can see all users in tenant
            $this->availableUsers = User::where('tenant_id', $this->currentTenantId)
                ->orderBy('name')
                ->get(['id', 'name', 'email'])
                ->toArray();
        } else {
            // WORKAROUND: Direct query instead of using relationship
            // Regular user can only see their assigned stores via store_user pivot
            $this->availableStores = DB::table('stores')
                ->join('store_user', 'stores.id', '=', 'store_user.store_id')
                ->where('store_user.user_id', $user->id)
                ->orderBy('stores.name')
                ->select('stores.id', 'stores.name')
                ->get()
                ->toArray();

            // Can only see users who share at least one store
            $storeIds = DB::table('store_user')
                ->where('user_id', $user->id)
                ->pluck('store_id');

            if ($storeIds->isNotEmpty()) {
                $this->availableUsers = DB::table('users')
                    ->join('store_user', 'users.id', '=', 'store_user.user_id')
                    ->whereIn('store_user.store_id', $storeIds)
                    ->where('users.tenant_id', $this->currentTenantId)
                    ->where('users.id', '!=', $user->id)
                    ->orderBy('users.name')
                    ->select('users.id', 'users.name', 'users.email')
                    ->distinct()
                    ->get()
                    ->toArray();
            } else {
                $this->availableUsers = [];
            }
        }

        // Convert stdClass objects to arrays if needed
        $this->availableStores = array_map(function ($item) {
            return (array) $item;
        }, $this->availableStores);

        $this->availableUsers = array_map(function ($item) {
            return (array) $item;
        }, $this->availableUsers);
    }

    /**
     * Set default date range based on selected period
     */
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

    /**
     * Handle store change event
     */
    public function handleStoreChange($storeId)
    {
        $this->selectedStoreId = $storeId;
        $this->refreshData();
    }

    /**
     * Refresh all dashboard data
     */
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
     * Apply tenant and team filters to a query
     */
    protected function applyFilters($query, $tablePrefix = null)
    {
        if (!auth()->check()) {
            return $query;
        }

        $user = Auth::user();
        $prefix = $tablePrefix ? $tablePrefix . '.' : '';

        // Apply store filter
        if ($this->selectedStoreId) {
            $query->where($prefix . 'store_id', $this->selectedStoreId);
        } elseif (!$user->hasRole('super admin') && !$user->hasRole('tenant_admin')) {
            // Regular user: only show their accessible stores
            $storeIds = $user->stores()->pluck('stores.id');
            $query->whereIn($prefix . 'store_id', $storeIds);
        }

        // Apply user filter (for team filtering)
        if ($this->selectedUserId && $this->selectedUserId !== 'all') {
            $query->where($prefix . 'user_id', $this->selectedUserId);
        }

        // Apply date range filter
        if ($this->startDate && $this->endDate) {
            if ($this->isDateColumnAvailable($query->getModel()->getTable(), 'created_at')) {
                $query->whereBetween($prefix . 'created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
            }
        }

        return $query;
    }

    /**
     * Check if date column exists in table
     */
    protected function isDateColumnAvailable($table, $column)
    {
        try {
            return \Schema::hasColumn($table, $column);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Apply tenant filter for Store-related queries
     */
    protected function applyTenantFilter($query)
    {
        if ($this->currentTenantId) {
            $query->where('tenant_id', $this->currentTenantId);
        }
        return $query;
    }

    /**
     * Fetch summary statistics
     */
    public function fetchSummary()
    {
        if (!auth()->check()) {
            $this->summary = $this->getEmptySummary();
            return;
        }

        try {
            $query = Analytics::query();
            $query = $this->applyFilters($query, 'analytics');

            // Get totals using separate queries to avoid ambiguity
            $totalInventory = (clone $query)->sum('analytics.current_quantity');
            $stockIn = (clone $query)->sum('analytics.total_stock_in');
            $stockOut = (clone $query)->sum('analytics.total_stock_out');
            $inventoryEquity = (clone $query)->sum('analytics.inventory_assets');

            // Potential Revenue - requires join
            $potentialRevenue = (float) Analytics::query()
                ->join('items', 'analytics.item_id', '=', 'items.id')
                ->when($this->selectedStoreId, function ($q) {
                    return $q->where('analytics.store_id', $this->selectedStoreId);
                })
                ->when($this->selectedUserId && $this->selectedUserId !== 'all', function ($q) {
                    return $q->where('analytics.user_id', $this->selectedUserId);
                })
                ->when($this->startDate && $this->endDate, function ($q) {
                    return $q->whereBetween('analytics.created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
                })
                ->sum(DB::raw('analytics.current_quantity * items.price'));

            $potentialProfit = $potentialRevenue - $inventoryEquity;

            $this->summary = [
                'totalInventory' => (int) $totalInventory,
                'stockIn' => (int) $stockIn,
                'stockOut' => (int) $stockOut,
                'inventoryEquity' => (float) $inventoryEquity,
                'potentialRevenue' => (float) $potentialRevenue,
                'potentialProfit' => (float) $potentialProfit,
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching summary: ' . $e->getMessage());
            $this->summary = $this->getEmptySummary();
        }
    }

    /**
     * Get empty summary array
     */
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
     * Fetch total inventory data for charts
     */
    public function fetchTotalInventoryData()
    {
        if (!auth()->check()) {
            $this->totalInventoryData = [];
            $this->topBrandsData = [];
            return;
        }

        try {
            $query = Analytics::query()
                ->with('item')
                ->orderBy('current_quantity', 'desc')
                ->take(10);

            $query = $this->applyFilters($query, 'analytics');

            $data = $query->get();

            $this->totalInventoryData = $data->map(function ($stat) {
                return [
                    'name' => $stat->item->name ?? 'Unknown',
                    'quantity' => (int) $stat->current_quantity,
                    'sku' => $stat->item->sku ?? 'N/A',
                    'value' => (float) ($stat->current_quantity * ($stat->item->price ?? 0)),
                ];
            })->values()->all();

            // Brand-wise distribution
            $this->topBrandsData = $data->groupBy(function ($stat) {
                return $stat->item->brand ?? 'No Brand';
            })->map(function ($group, $brand) {
                return [
                    'brand' => $brand,
                    'count' => $group->sum('current_quantity'),
                    'total_value' => $group->sum(function ($stat) {
                        return $stat->current_quantity * ($stat->item->price ?? 0);
                    }),
                ];
            })->values()->all();
        } catch (\Exception $e) {
            \Log::error('Error fetching inventory data: ' . $e->getMessage());
            $this->totalInventoryData = [];
            $this->topBrandsData = [];
        }
    }

    /**
     * Fetch low stock items
     */
    public function fetchLowStockItems()
    {
        if (!auth()->check()) {
            $this->lowStockItems = [];
            return;
        }

        try {
            $query = Item::query()
                ->with('supplier')
                ->whereColumn('quantity', '<=', 'reorder_level')
                ->orderBy('quantity', 'asc')
                ->take(5);

            // Apply tenant filter
            $query = $this->applyTenantFilter($query);

            // Apply store filter
            if ($this->selectedStoreId) {
                $query->where('store_id', $this->selectedStoreId);
            } elseif (!auth()->user()->hasRole('super admin') && !auth()->user()->hasRole('tenant_admin')) {
                $storeIds = auth()->user()->stores()->pluck('stores.id');
                $query->whereIn('store_id', $storeIds);
            }

            $this->lowStockItems = $query->get();
        } catch (\Exception $e) {
            \Log::error('Error fetching low stock items: ' . $e->getMessage());
            $this->lowStockItems = [];
        }
    }

    /**
     * Fetch margin leaders (most profitable items)
     */
    public function fetchMarginLeaders(): void
    {
        if (!auth()->check()) {
            $this->marginLeaders = [];
            return;
        }

        try {
            $query = Item::query()
                ->where('quantity', '>', 0)
                ->orderByRaw('(price - cost) * quantity DESC')
                ->take(8);

            // Apply tenant filter
            $query = $this->applyTenantFilter($query);

            // Apply store filter
            if ($this->selectedStoreId) {
                $query->where('store_id', $this->selectedStoreId);
            } elseif (!auth()->user()->hasRole('super admin') && !auth()->user()->hasRole('tenant_admin')) {
                $storeIds = auth()->user()->stores()->pluck('stores.id');
                $query->whereIn('store_id', $storeIds);
            }

            $this->marginLeaders = $query->get()
                ->map(function ($item) {
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
                })
                ->all();
        } catch (\Exception $e) {
            \Log::error('Error fetching margin leaders: ' . $e->getMessage());
            $this->marginLeaders = [];
        }
    }

    /**
     * Fetch recent inventory audits
     */
    public function fetchRecentAudits(): void
    {
        if (!auth()->check()) {
            $this->recentAudits = [];
            return;
        }

        try {
            $query = InventoryAudit::with(['item', 'user'])
                ->latest()
                ->take(10);

            // Apply store filter
            if ($this->selectedStoreId) {
                $query->where('store_id', $this->selectedStoreId);
            } elseif (!auth()->user()->hasRole('super admin') && !auth()->user()->hasRole('tenant_admin')) {
                $storeIds = auth()->user()->stores()->pluck('stores.id');
                $query->whereIn('store_id', $storeIds);
            }

            // Apply user filter
            if ($this->selectedUserId && $this->selectedUserId !== 'all') {
                $query->where('user_id', $this->selectedUserId);
            }

            // Apply date range
            if ($this->startDate && $this->endDate) {
                $query->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
            }

            $this->recentAudits = $query->get();
        } catch (\Exception $e) {
            \Log::error('Error fetching recent audits: ' . $e->getMessage());
            $this->recentAudits = [];
        }
    }

    /**
     * Send low stock alerts to suppliers
     */
    public function sendLowStockAlerts(): void
    {
        // Refresh low stock items with current filters
        $this->fetchLowStockItems();

        if ($this->lowStockItems->isEmpty()) {
            session()->flash('error', 'No low stock items to alert.');
            return;
        }

        $alerts = $this->lowStockItems->map(function ($item) {
            return [
                'name' => $item->name,
                'sku' => $item->sku,
                'current' => (int) $item->quantity,
                'reorder_level' => (int) $item->reorder_level,
                'suggested_order' => max(1, (int) $item->reorder_quantity),
                'supplier_name' => $item->supplier?->name,
                'supplier_email' => $item->supplier?->email,
                'supplier_whatsapp' => $item->supplier?->whatsapp,
                'store_name' => $item->store?->name,
            ];
        })->all();

        // Send emails
        $emails = collect($alerts)->pluck('supplier_email')->filter()->unique();
        if ($emails->isNotEmpty()) {
            foreach ($emails as $email) {
                try {
                    Mail::to($email)->send(new LowStockAlertMail($alerts));
                } catch (\Exception $e) {
                    \Log::error('Failed to send low stock email: ' . $e->getMessage());
                }
            }
        }

        // Send WhatsApp notifications
        $whatsAppNotifier = app(WhatsAppNotifier::class);
        $whatsappNumbers = collect($alerts)->pluck('supplier_whatsapp')->filter()->unique();

        foreach ($whatsappNumbers as $phone) {
            try {
                $message = '🚨 Low Stock Alert for ' . ($this->selectedStoreId ? 'Store' : 'Your Stores') . ":\n\n";
                $message .= collect($alerts)->take(5)->map(function ($a) {
                    return "📦 {$a['name']}\n   Stock: {$a['current']} (Min: {$a['reorder_level']})\n   Suggested: {$a['suggested_order']} units";
                })->implode("\n\n");

                if (count($alerts) > 5) {
                    $message .= "\n\n... and " . (count($alerts) - 5) . " more items.";
                }

                $whatsAppNotifier->send($phone, $message);
            } catch (\Exception $e) {
                \Log::error('Failed to send WhatsApp alert: ' . $e->getMessage());
            }
        }

        session()->flash('message', sprintf(
            'Low stock alerts dispatched: %d emails, %d WhatsApp messages',
            $emails->count(),
            $whatsappNumbers->count()
        ));
    }

    /**
     * Update date range based on selection
     */
    public function updatedDateRange()
    {
        $this->setDefaultDateRange();
        $this->refreshData();
    }

    /**
     * Handle manual date range change
     */
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

    /**
     * Handle store selection change
     */
    public function updatedSelectedStoreId()
    {
        $this->refreshData();
    }

    /**
     * Handle user selection change
     */
    public function updatedSelectedUserId()
    {
        $this->refreshData();
    }

    /**
     * Reset all filters
     */
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

    /**
     * Export current data as CSV
     */
    public function exportData()
    {
        $filename = 'dashboard_export_' . now()->format('Y-m-d_His') . '.csv';
        $handle = fopen('php://temp', 'w+');

        // Headers
        fputcsv($handle, ['Metric', 'Value', 'Date Range', 'Store', 'User']);

        // Summary data
        fputcsv($handle, [
            'Total Inventory',
            $this->summary['totalInventory'],
            $this->dateRange,
            $this->selectedStoreId ?? 'All',
            $this->selectedUserId ?? 'All'
        ]);
        fputcsv($handle, [
            'Stock In',
            $this->summary['stockIn'],
            $this->dateRange,
            $this->selectedStoreId ?? 'All',
            $this->selectedUserId ?? 'All'
        ]);
        fputcsv($handle, [
            'Stock Out',
            $this->summary['stockOut'],
            $this->dateRange,
            $this->selectedStoreId ?? 'All',
            $this->selectedUserId ?? 'All'
        ]);
        fputcsv($handle, [
            'Inventory Equity',
            $this->summary['inventoryEquity'],
            $this->dateRange,
            $this->selectedStoreId ?? 'All',
            $this->selectedUserId ?? 'All'
        ]);
        fputcsv($handle, [
            'Potential Revenue',
            $this->summary['potentialRevenue'],
            $this->dateRange,
            $this->selectedStoreId ?? 'All',
            $this->selectedUserId ?? 'All'
        ]);
        fputcsv($handle, [
            'Potential Profit',
            $this->summary['potentialProfit'],
            $this->dateRange,
            $this->selectedStoreId ?? 'All',
            $this->selectedUserId ?? 'All'
        ]);

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * Sync chart payloads for JavaScript
     */
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

    /**
     * Get role-based access info for UI
     */
    public function getUserRoleInfo()
    {
        if (!auth()->check()) {
            return ['role' => 'guest', 'can_view_all' => false];
        }

        $user = Auth::user();
        return [
            'role' => $user->getRoleNames()->first() ?? 'user',
            'can_view_all' => $user->hasRole('super admin') || $user->hasRole('tenant_admin'),
            'store_count' => $user->stores()->count(),
        ];
    }

    /**
     * Render the dashboard view
     */
    public function render()
    {
        return view('livewire.dashboard', [
            'userRoleInfo' => $this->getUserRoleInfo(),
            'filtersApplied' => !is_null($this->selectedStoreId) ||
                (!is_null($this->selectedUserId) && $this->selectedUserId !== 'all') ||
                $this->dateRange !== 'current_month',
        ])->layout('layouts.app', [
                    'title' => 'Dashboard - Stock Management System'
                ]);
    }
}