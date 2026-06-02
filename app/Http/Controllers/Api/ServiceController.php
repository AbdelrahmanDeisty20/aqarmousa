<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Service\GeneralServiceService;
use App\Traits\ApiResponse;

class ServiceController extends Controller
{
    use ApiResponse;

    protected $serviceService;

    public function __construct(GeneralServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index()
    {
        $services = $this->serviceService->getAll();
        if ($services->isEmpty()) {
            return $this->error(__('api.no_services_yet'), 200);
        }
        return $this->success(ServiceResource::collection($services));
    }

    public function show($id)
    {
        $service = $this->serviceService->getById($id);
        return $this->success(new ServiceResource($service));
    }
}
