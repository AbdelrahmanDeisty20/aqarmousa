<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'payment_status' => $this->payment_status,
            'transaction_ref' => $this->transaction_ref ?? '',
            'unit' => $this->whenLoaded('unit', function () {
                return [
                    'id' => $this->unit->id,
                    'title' => $this->unit->{'title_' . app()->getLocale()} ?? $this->unit->title_ar,
                ];
            }),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}