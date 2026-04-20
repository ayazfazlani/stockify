<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Models\Analytics;
use App\Exports\AnalyticsExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class Analytic extends Component
{
    public $itemsDataJsn;
    public $filteredAnalyticsDataJsn;
    public $filterName = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $store_id;

    public function mount()
    {
        // Initialize store_id during mount
        $this->store_id =  Auth::user()->getCurrentStoreId();
        $this->fetchData();
    }

    public function updatedFilterName()
    {
        $this->fetchData();
    }

    public function updatedDateFrom()
    {
        $this->fetchData();
    }

    public function updatedDateTo()
    {
        $this->fetchData();
    }

    public function fetchData()
    {
        // Fetch items based on user role
        $items = Auth::user()->hasRole('super admin')
            ? Item::all()
            : Item::where('store_id', $this->store_id)->get();

        // Convert items to JSON and assign
        $this->itemsDataJsn = $items->toJson();

        // Build analytics query based on user role
        $query = Analytics::query();

        if (!Auth::user()->hasRole('super admin')) {
            $query->where('store_id', $this->store_id);
        }

        // Apply additional filters for analytics
        if (!empty($this->filterName)) {
            $query->where('item_name', 'like', '%' . $this->filterName . '%');
        }

        if (!empty($this->dateFrom)) {
            $query->where('created_at', '>=', $this->dateFrom . ' 00:00:00');
        }

        if (!empty($this->dateTo)) {
            $query->where('created_at', '<=', $this->dateTo . ' 23:59:59');
        }

        // Fetch analytics data and convert to JSON
        $this->filteredAnalyticsDataJsn = $query->orderBy('created_at', 'desc')->get()->toJson();
    }

    public function exportExcel()
    {
        // Convert JSON data to a collection for export
        $analyticsData = collect(json_decode($this->filteredAnalyticsDataJsn, true));
        return Excel::download(new AnalyticsExport($analyticsData), 'analytics.xlsx');
    }

    public function calculate($column)
    {
        // Decode analytics data and calculate total
        $data = json_decode($this->filteredAnalyticsDataJsn, true);
        if (empty($data)) return 0;
        
        $total = array_sum(array_column($data, $column));
        return $total;
    }

    public function render()
    {
        return view('livewire.analytic', [
            'itemsDataJson' => $this->itemsDataJsn,
            'filteredAnalyticsDataJson' => $this->filteredAnalyticsDataJsn,
        ]);
    }
}
