<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'avatar' => $this->avatar ? Storage::disk('public')->url($this->avatar) : '',
            'units_count' => $this->units_count ?? 0,
            'created_at' => $this->created_at->format('Y-m-d') ?? '',
            'units' => UnitListResource::collection($this->whenLoaded('units')),
        ];
    }
}
