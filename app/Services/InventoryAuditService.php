<?php

namespace App\Services;

use App\Models\InventoryAudit;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class InventoryAuditService
{
    public function log(Item $item, string $action, int $beforeQty, int $changeQty, ?string $reason = null, array $meta = []): void
    {
        InventoryAudit::create([
            'tenant_id' => Auth::user()?->tenant_id,
            'store_id' => $item->store_id,
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'before_qty' => $beforeQty,
            'change_qty' => $changeQty,
            'after_qty' => $beforeQty + $changeQty,
            'reason' => $reason,
            'meta' => $meta,
        ]);
    }
}
