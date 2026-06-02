<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Seller\FetchSellersRequest;
use App\Http\Resources\SellerResource;
use App\Service\SellerService;
use App\Traits\ApiResponse;

class SellerController extends Controller
{
    use ApiResponse;

    protected $sellerService;

    public function __construct(SellerService $sellerService)
    {
        $this->sellerService = $sellerService;
    }

    public function index(FetchSellersRequest $request)
    {
        $sellers = $this->sellerService->getAllSellers();
        return $this->success(SellerResource::collection($sellers));
    }

    public function show($id)
    {
        $seller = $this->sellerService->getSellerById($id);

        if (!$seller) {
            return $this->error(__('api.seller_not_found'), 404);
        }

        return $this->success(new SellerResource($seller));
    }
}
