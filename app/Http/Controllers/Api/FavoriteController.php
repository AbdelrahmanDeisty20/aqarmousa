<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Favorite\ToggleFavoriteRequest;
use App\Service\FavoriteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    use ApiResponse;
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function index(Request $request)
    {
        $favorites = $this->favoriteService->getUserFavorites($request->user());

        if ($favorites->isEmpty()) {
            return $this->error(__('api.no_favorites_found'), 200);
        }

        return $this->paginated(\App\Http\Resources\FavoriteResource::class, $favorites);
    }

    public function toggle(ToggleFavoriteRequest $request)
    {
        $response = $this->favoriteService->toggleFavorite($request->user(), $request->unit_id);
        return response()->json($response);
    }
}
