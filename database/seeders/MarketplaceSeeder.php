<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketplaceSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'icon' => 'bolt'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'icon' => 'shopping-bag'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'icon' => 'home'],
            ['name' => 'Groceries', 'slug' => 'groceries', 'icon' => 'shopping-cart'],
            ['name' => 'Beauty', 'slug' => 'beauty', 'icon' => 'sparkles'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 2. Make some stores public and give them locations
        $stores = Store::take(5)->get();
        $locations = [
            ['city' => 'New York', 'lat' => 40.7128, 'lng' => -74.0060, 'address' => '5th Ave, NY'],
            ['city' => 'London', 'lat' => 51.5074, 'lng' => -0.1278, 'address' => 'Oxford St, London'],
            ['city' => 'Dubai', 'lat' => 25.2048, 'lng' => 55.2708, 'address' => 'Sheikh Zayed Rd, Dubai'],
            ['city' => 'Singapore', 'lat' => 1.3521, 'lng' => 103.8198, 'address' => 'Orchard Rd, Singapore'],
            ['city' => 'Tokyo', 'lat' => 35.6762, 'lng' => 139.6503, 'address' => 'Shibuya, Tokyo'],
        ];

        foreach ($stores as $index => $store) {
            $loc = $locations[$index] ?? $locations[0];
            $store->update([
                'is_public' => true,
                'city' => $loc['city'],
                'country' => 'Global',
                'latitude' => $loc['lat'],
                'longitude' => $loc['lng'],
                'address' => $loc['address'],
            ]);

            // 3. Make some items in these stores public
            $items = Item::where('store_id', $store->id)->take(10)->get();
            foreach ($items as $item) {
                $item->update([
                    'is_public' => true,
                    'slug' => Str::slug($item->name).'-'.Str::random(4),
                    'category_id' => Category::inRandomOrder()->first()->id,
                    'description' => "This is a premium product from {$store->name}. Available now at our {$loc['city']} location.",
                ]);
            }
        }
    }
}
