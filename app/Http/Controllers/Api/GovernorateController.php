<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GovernorateResource;
use App\Service\GovernorateService;

class GovernorateController extends Controller
{
    protected $governorateService;

    public function __construct(GovernorateService $governorateService)
    {
        $this->governorateService = $governorateService;
    }

    public function index()
    {
        $governorates = $this->governorateService->getAllGovernorates();
        if ($governorates->isEmpty()) {
            return $this->error(__('api.no_governorates_yet'), 200);
        }
        return $this->success(GovernorateResource::collection($governorates));
    }
}
