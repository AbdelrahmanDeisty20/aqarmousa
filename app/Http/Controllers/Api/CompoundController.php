<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompoundResource;
use App\Service\CompoundService;

class CompoundController extends Controller
{
    protected $compoundService;

    public function __construct(CompoundService $compoundService)
    {
        $this->compoundService = $compoundService;
    }

    public function index()
    {
        $compounds = $this->compoundService->getAllCompounds();
        if ($compounds->isEmpty()) {
            return $this->error(__('api.no_compounds_yet'), 200);
        }
        return $this->success(CompoundResource::collection($compounds));
    }

    public function show($id)
    {
        $compound = $this->compoundService->getCompoundById($id);

        if (!$compound) {
            return $this->error(__('api.compound_not_found'), 404);
        }

        $units = $this->compoundService->getCompoundUnits($id, 10);

        $compoundData = new \App\Http\Resources\CompoundResource($compound);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => array_merge($compoundData->toArray(request()), [
                'units' => \App\Http\Resources\UnitListResource::collection($units->items()),
            ]),
            'pagination' => [
                'current_page' => $units->currentPage(),
                'per_page' => $units->perPage(),
                'total' => $units->total(),
                'last_page' => $units->lastPage(),
            ],
        ]);
    }
}
