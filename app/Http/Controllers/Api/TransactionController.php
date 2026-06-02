<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Service\TransactionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use ApiResponse;
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $transactions = $this->transactionService->getUserTransactions($request->user());

        if ($transactions->isEmpty()) {
            return $this->success([], __('api.no_transactions_yet'));
        }

        return $this->paginated(TransactionResource::class, $transactions);
    }
}
