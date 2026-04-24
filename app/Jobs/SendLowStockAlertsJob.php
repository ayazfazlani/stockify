<?php

namespace App\Jobs;

use App\Mail\LowStockAlertMail;
use App\Models\Item;
use App\Services\Notifications\WhatsAppNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLowStockAlertsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $lowStockItems = Item::query()
            ->with('supplier')
            ->whereNotNull('supplier_id')
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->get()
            ->groupBy('store_id');

        $notifier = app(WhatsAppNotifier::class);

        foreach ($lowStockItems as $storeItems) {
            $alerts = $storeItems->map(function ($item) {
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

            $emails = collect($alerts)->pluck('supplier_email')->filter()->unique();
            foreach ($emails as $email) {
                Mail::to($email)->send(new LowStockAlertMail($alerts));
            }

            collect($alerts)->pluck('supplier_whatsapp')->filter()->unique()->each(function ($phone) use ($notifier, $alerts) {
                $message = 'Low stock alert: ' . collect($alerts)->take(3)->map(fn ($a) => "{$a['name']} ({$a['current']} left)")->implode(', ');
                $notifier->send($phone, $message);
            });
        }
    }
}
