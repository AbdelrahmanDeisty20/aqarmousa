<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompoundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();
        return [
            'id' => $this->id,
            'name' => ($lang === 'ar' ? $this->name_ar : $this->name_en) ?? '',
            'description' => ($lang === 'ar' ? $this->description_ar : $this->description_en) ?? '',
            'units' => UnitListResource::collection($this->whenLoaded('units')),
        ];
    }
}
