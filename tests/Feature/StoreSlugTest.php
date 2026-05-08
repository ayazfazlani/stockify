<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class StoreSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_slug_updates_when_name_is_updated()
    {
        $tenant = Tenant::factory()->create();
        $store = Store::create([
            'tenant_id' => $tenant->id,
            'name' => 'Original Store Name',
            'slug' => 'original-store-name',
            'address' => 'Test Address',
            'city' => 'Test City',
            'country' => 'Pakistan',
        ]);

        $this->assertEquals('original-store-name', $store->slug);

        $store->update(['name' => 'New Store Name']);

        // This is expected to fail if the logic isn't there
        $this->assertEquals('new-store-name', $store->fresh()->slug);
    }
}
