<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Analytics;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $summary = [];
    public $totalInventoryData = [];
    public $lowStockItems = [];
    public $topBrandsData = [];

    public function mount()
    {
        $this->fetchSummary();
        $this->fetchTotalInventoryData();
        $this->fetchLowStockItems();
    }

    public function fetchSummary()
    {
        if (auth()->check()) {
            $query = Analytics::query();
            
            if (!auth()->user()->hasRole('super admin')) {
                $teamId = Auth::user()->getCurrentStoreId();
                $query->where('team_id', $teamId);
            }

            $analyticsData = $query->with('item')->get();

            $totalInventory = $analyticsData->sum('current_quantity');
            $stockIn = $analyticsData->sum('total_stock_in');
            $stockOut = $analyticsData->sum('total_stock_out');
            $inventoryEquity = $analyticsData->sum('inventory_assets');
            
            // Potential Revenue = Current Quantity * Item Price
            $potentialRevenue = $analyticsData->sum(function($stat) {
                return $stat->current_quantity * ($stat->item->price ?? 0);
            });

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
    }

    public function fetchTotalInventoryData()
    {
        if (auth()->check()) {
            $query = Analytics::query();
            
            if (!auth()->user()->hasRole('super admin')) {
                $teamId = Auth::user()->getCurrentStoreId();
                $query->where('team_id', $teamId);
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
            });

            // Brand-wise distribution
            $this->topBrandsData = $data->groupBy(function($stat) {
                return $stat->item->brand ?? 'No Brand';
            })->map(function($group, $brand) {
                return [
                    'brand' => $brand,
                    'count' => $group->sum('current_quantity')
                ];
            })->values();
        }
    }

    public function fetchLowStockItems()
    {
        if (auth()->check()) {
            $query = Analytics::query();
            
            if (!auth()->user()->hasRole('super admin')) {
                $teamId = Auth::user()->getCurrentStoreId();
                $query->where('team_id', $teamId);
            }

            $this->lowStockItems = $query->with('item')
                ->where('current_quantity', '<', 10) // Threshold can be dynamic later
                ->where('current_quantity', '>', 0)
                ->orderBy('current_quantity', 'asc')
                ->take(5)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
