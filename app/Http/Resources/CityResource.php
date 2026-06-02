<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();
        return [
            'id' => $this->id,
            'name' => ($lang === 'ar' ? $this->name_ar : $this->name_en) ?? '',
        ];
    }
}
