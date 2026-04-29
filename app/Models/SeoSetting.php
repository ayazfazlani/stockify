<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key with an optional default.
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key (create or update).
     */
    public static function setValue(string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get the robots.txt content.
     */
    public static function getRobotsTxt(): string
    {
        return static::getValue('robots_txt', "User-agent: *\nAllow: /\n\nSitemap: " . config('app.url') . "/sitemap.xml");
    }

    /**
     * Get the global schema markup.
     */
    public static function getGlobalSchema(): ?array
    {
        $value = static::getValue('global_schema');
        return $value ? json_decode($value, true) : null;
    }
}
