<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        $settings = Cache::remember('site_settings_formatted_api', 86400, function () use ($keys) {
            $allSettings = Setting::all();
            $result = [];
            foreach ($keys as $key) {
                $setting = $allSettings->firstWhere('key', $key);
                $result[$key] = $setting ? Setting::formatSettingValue($setting) : null;
            }
            return $result;
        });

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