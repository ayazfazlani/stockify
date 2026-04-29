<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        \DB::table('seo_settings')->insert([
            [
                'key' => 'robots_txt',
                'value' => "User-agent: *\nAllow: /\n\nDisallow: /super-admin/\nDisallow: /login\nDisallow: /register\n\nSitemap: " . config('app.url') . "/sitemap.xml",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_meta_title',
                'value' => '{{page_title}} | StockFlow',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_description',
                'value' => 'AI-Powered Inventory Management Solution - Streamline your operations, reduce costs, and boost efficiency.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'global_schema',
                'value' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => 'StockFlow',
                    'description' => 'AI-Powered Inventory Management Solution',
                    'url' => config('app.url'),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
