<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class DeveloperResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();
        return [
            'id' => $this->id,
            'name' => ($lang === 'ar' ? $this->name_ar : $this->name_en) ?? '',
            'logo' => $this->logo ? Storage::disk('public')->url($this->logo) : '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'address' => $this->address ?? '',
            'units' => UnitListResource::collection($this->whenLoaded('units')),
        ];
    }
}
