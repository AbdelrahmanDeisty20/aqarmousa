<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    use \App\Traits\ApiResponse;

    public function index()
    {
        $amenities = \App\Models\Amenity::all();
        if ($amenities->isEmpty()) {
            return $this->error(__('api.no_unit_types_yet'), 200);
        }
        return $this->success(\App\Http\Resources\AmenityResource::collection($amenities));
    }
}
