<?php

namespace App\Observers;

use App\Models\Item;
use App\Services\StoreMetricsService;
use Illuminate\Support\Facades\Storage;

class ItemMetricsObserver
{
    protected $metricsService;

    public function __construct(StoreMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function created(Item $item)
    {
        $store = $item->store;
        if (!$store) return;
        
        // Record item creation metric
        $this->metricsService->recordMetric($store, 'items', 1, [
            'action' => 'created',
        ]);

        // Track storage usage
        $this->updateStorageQuota($store);
    }

    public function updated(Item $item)
    {
        $store = $item->store;
        if (!$store) return;
        
        // Track storage changes if relevant fields were updated
        if ($item->isDirty(['image', 'attachments'])) {
            $this->updateStorageQuota($store);
        }
    }

    public function deleted(Item $item)
    {
        $store = $item->store;
        if (!$store) return;
        
        // Record item deletion metric
        $this->metricsService->recordMetric($store, 'items', -1, [
            'action' => 'deleted',
        ]);

        // Update storage usage
        $this->updateStorageQuota($store);
    }

    protected function updateStorageQuota($store)
    {
        // Calculate total storage used by the store's items
        $items = Item::where('store_id', $store->id)->get();
        
        $storageUsed = 0;
        foreach ($items as $item) {
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                $storageUsed += Storage::disk('public')->size($item->image);
            }
            if (!empty($item->attachments) && is_array($item->attachments)) {
                foreach ($item->attachments as $attachment) {
                    if (Storage::disk('public')->exists($attachment)) {
                        $storageUsed += Storage::disk('public')->size($attachment);
                    }
                }
            }
        }

        // Convert to MB
        $storageUsedMB = ceil($storageUsed / 1024 / 1024);
        
        // Update storage quota
        $this->metricsService->updateQuota($store, 'storage', $storageUsedMB);
    }
}