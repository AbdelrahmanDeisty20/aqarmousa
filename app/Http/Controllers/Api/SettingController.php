<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    use ApiResponse;

    /**
     * Get all site settings.
     */
    public function index()
    {
        $keys = [
            'home_hero_image',
            'site_name',
            'site_email',
            'site_logo',
            'site_phone',
            'site_address',
            'social_facebook',
            'social_instagram',
            'social_twitter',
        ];

        $settings = [];
        foreach ($keys as $key) {
            $value = Setting::getValue($key);

            // Add full URL for image fields
            if ($key === 'site_logo' && $value) {
                $settings[$key] = env('APP_URL') . Storage::disk('public')->url($value);
            } elseif ($key === 'home_hero_image' && $value) {
                // home_hero_image is an array of paths
                $settings[$key] = array_map(function($path) {
                    return env('APP_URL') . Storage::disk('public')->url($path);
                }, $value);
            } else {
                $settings[$key] = $value;
            }
        }

        return $this->success($settings);
    }

    /**
     * Get a specific setting.
     */
    public function show($key)
    {
        $value = Setting::getValue($key);

        if ($value === null) {
            return $this->error(__('api.setting_not_found'), 404);
        }

        return $this->success([
            $key => $value
        ]);
    }
}