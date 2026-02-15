<?php

namespace App\Observers;

use App\Models\Item;
use App\Services\TeamMetricsService;
use Illuminate\Support\Facades\Storage;

class ItemMetricsObserver
{
    protected $metricsService;

    public function __construct(TeamMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function created(Item $item)
    {
        $team = $item->team;
        
        // Record item creation metric
        $this->metricsService->recordMetric($team, 'items', 1, [
            'action' => 'created',
        ]);

        // Track storage usage
        $this->updateStorageQuota($team);
    }

    public function updated(Item $item)
    {
        $team = $item->team;
        
        // Track storage changes if relevant fields were updated
        if ($item->isDirty(['image', 'attachments'])) {
            $this->updateStorageQuota($team);
        }
    }

    public function deleted(Item $item)
    {
        $team = $item->team;
        
        // Record item deletion metric
        $this->metricsService->recordMetric($team, 'items', -1, [
            'action' => 'deleted',
        ]);

        // Update storage usage
        $this->updateStorageQuota($team);
    }

    protected function updateStorageQuota($team)
    {
        // Calculate total storage used by the team's items
        $storageUsed = Item::where('team_id', $team->id)
            ->sum(function ($item) {
                $storage = 0;
                if ($item->image) {
                    $storage += Storage::size($item->image);
                }
                if ($item->attachments) {
                    foreach ($item->attachments as $attachment) {
                        $storage += Storage::size($attachment);
                    }
                }
                return $storage;
            });

        // Convert to MB
        $storageUsedMB = ceil($storageUsed / 1024 / 1024);
        
        // Update storage quota
        $this->metricsService->updateQuota($team, 'storage', $storageUsedMB);
    }
}