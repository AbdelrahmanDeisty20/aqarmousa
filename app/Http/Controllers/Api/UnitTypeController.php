<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitTypeResource;
use App\Service\UnitTypeService;

class UnitTypeController extends Controller
{
    protected $unitTypeService;

    public function __construct(UnitTypeService $unitTypeService)
    {
        $this->unitTypeService = $unitTypeService;
    }

    public function index()
    {
        $types = $this->unitTypeService->getAll();
        if ($types->isEmpty()) {
            return $this->error(__('api.no_unit_types_yet'), 200);
        }
        return $this->success(UnitTypeResource::collection($types));
    }
}
