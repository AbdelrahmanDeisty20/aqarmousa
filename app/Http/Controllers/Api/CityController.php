<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Service\CityService;

class CityController extends Controller
{
    protected $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function index()
    {
        $cities = $this->cityService->getAllCities();
        if ($cities->isEmpty()) {
            return $this->error(__('api.no_cities_yet'), 200);
        }
        return $this->success(CityResource::collection($cities));
    }
}
