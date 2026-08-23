<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Cache key for all site settings.
     */
    public const CACHE_KEY = 'site_settings_all';

    /**
     * Boot model and attach event listeners to flush cache on change.
     */
    protected static function booted()
    {
        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget(self::CACHE_KEY);
        \Illuminate\Support\Facades\Cache::forget('site_settings_formatted_api');
    }

    /**
     * Format a setting record value based on type.
     */
    public static function formatSettingValue($setting)
    {
        if (!$setting) {
            return null;
        }

        if ($setting->type === 'image' && $setting->value) {
            // Check if it's multiple images (JSON array)
            $decoded = json_decode($setting->value, true);
            if (is_array($decoded)) {
                return array_map(function ($path) {
                    if (filter_var($path, FILTER_VALIDATE_URL) || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                        return $path;
                    }
                    return Storage::disk('public')->url($path);
                }, $decoded);
            }

            if (filter_var($setting->value, FILTER_VALIDATE_URL) || str_starts_with($setting->value, 'http://') || str_starts_with($setting->value, 'https://')) {
                return $setting->value;
            }

            return Storage::disk('public')->url($setting->value);
        }

        if ($setting->type === 'json' || is_array(json_decode($setting->value, true))) {
            return json_decode($setting->value, true);
        }

        return $setting->value;
    }

    /**
     * Get a setting value by key (cached).
     */
    public static function getValue(string $key, $default = null)
    {
        $allSettings = \Illuminate\Support\Facades\Cache::remember(self::CACHE_KEY, 86400, function () {
            return self::all();
        });

        $setting = $allSettings->firstWhere('key', $key);

        if (!$setting) {
            return $default;
        }

        return self::formatSettingValue($setting);
    }

    /**
     * Set a setting value.
     */
    public static function setValue(string $key, $value, string $type = 'text')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        self::clearCache();

        return $setting;
    }

}
