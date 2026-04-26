<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDailySummary extends Command
{
    protected $signature = 'app:send-daily-summary';
    protected $description = 'Send daily summary email to store owners';

    public function handle()
    {
        $stores = \App\Models\Store::all();

        foreach ($stores as $store) {
            if (!$store->owner) continue;

            $today = now()->startOfDay();
            $tomorrow = now()->endOfDay();

            // Calculate Today's Sales
            $salesToday = \App\Models\Sale::where('store_id', $store->id)
                ->whereBetween('created_at', [$today, $tomorrow]);
            
            $totalSales = $salesToday->sum('total_amount');
            $transactionCount = $salesToday->count();

            // Calculate Total Stock Value
            $totalStockValue = \App\Models\Item::where('store_id', $store->id)
                ->sum(\Illuminate\Support\Facades\DB::raw('quantity * price'));

            // Count Low Stock Items
            $lowStockCount = \App\Models\Item::where('store_id', $store->id)
                ->whereRaw('quantity <= reorder_level')
                ->count();

            // Top Item (by quantity sold today)
            $topItemTransaction = \App\Models\Transaction::where('store_id', $store->id)
                ->where('type', 'stock_out')
                ->whereBetween('created_at', [$today, $tomorrow])
                ->select('item_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('item_id')
                ->orderByDesc('total_sold')
                ->first();
            
            $topItemName = $topItemTransaction ? ($topItemTransaction->item->name ?? 'N/A') : 'N/A';

            $data = [
                'store_name' => $store->name,
                'total_sales' => $totalSales,
                'transaction_count' => $transactionCount,
                'net_total' => $totalSales, // Simplification
                'total_stock_value' => $totalStockValue,
                'low_stock_count' => $lowStockCount,
                'top_item' => $topItemName,
                'active_subs' => \App\Models\Subscription::where('tenant_id', $store->tenant_id)->where('status', 'active')->count(),
                'dashboard_url' => route('index'), // Fallback
                'low_stock_url' => route('index'), // Fallback
            ];

            \Illuminate\Support\Facades\Mail::to($store->owner->email)->send(new \App\Mail\DailySummaryMail($data));
            
            $this->info("Summary sent to {$store->owner->email} for {$store->name}");
        }
    }
}
