<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    use ApiResponse;

    /**
     * Get all active banners ordered by sort_order.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return $this->success(BannerResource::collection($banners));
    }
}
