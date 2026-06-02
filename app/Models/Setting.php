<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        if ($setting->type === 'image' && $setting->value) {
            // Check if it's multiple images (JSON array)
            $decoded = json_decode($setting->value, true);
            if (is_array($decoded)) {
                return array_map(function ($path) {
                    return env('APP_URL') . Storage::disk('public')->url($path);
                }, $decoded);
            }

            return env('APP_URL') . Storage::disk('public')->url($setting->value);
        }

        if ($setting->type === 'json' || is_array(json_decode($setting->value, true))) {
            return json_decode($setting->value, true);
        }

        return $setting->value;
    }

    /**
     * Set a setting value.
     */
    public static function setValue(string $key, $value, string $type = 'text')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }
}
