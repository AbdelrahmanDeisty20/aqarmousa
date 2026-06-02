<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use App\Http\Resources\UnitListResource;
use App\Service\ReviewService;
use App\Service\UnitService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    use ApiResponse;
    protected $unitService;
    protected $reviewService;

    public function __construct(UnitService $unitService, ReviewService $reviewService)
    {
        $this->unitService = $unitService;
        $this->reviewService = $reviewService;
    }

    public function index(Request $request)
    {
        $units = $this->unitService->getFilteredUnits($request->all(), 10);
        if ($units->isEmpty()) {
            return $this->error(__('api.no_units_yet'), 200);
        }
        return $this->paginated(UnitListResource::class, $units);
    }

    public function show($id)
    {
        $unit = $this->unitService->getUnitById($id);
        return new UnitResource($unit);
    }

    public function latest()
    {
        $units = $this->unitService->getLatestUnits(10);
        if ($units->isEmpty()) {
            return $this->error(__('api.no_units_yet'), 200);
        }
        return $this->success(UnitListResource::collection($units));
    }
    public function related($id)
    {
        $units = $this->unitService->getRelatedUnits($id);
        if ($units->isEmpty()) {
            return $this->error(__('api.not_found'), 200);
        }
        return $this->success(UnitListResource::collection($units));
    }

    // Get all reviews for a specific unit
    public function reviews($id)
    {
        $reviews = $this->reviewService->getUnitReviews($id);
        if ($reviews->isEmpty()) {
            return $this->error(__('api.no_reviews_found'), 200);
        }
        return $this->paginated(\App\Http\Resources\ReviewResource::class, $reviews);
    }

    public function nearby(Request $request)
    {
        $units = $this->unitService->getNearbyUnits($request->user(), 10);

        if ($units->isEmpty()) {
            return $this->error(__('api.no_units_yet'), 200);
        }

        return $this->paginated(UnitListResource::class, $units);
    }
}
