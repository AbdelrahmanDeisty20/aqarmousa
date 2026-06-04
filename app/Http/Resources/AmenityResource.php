<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class AmenityResource extends JsonResource
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
            'name' => (app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en) ?? '',
            'icon' => $this->icon ? Storage::disk('public')->url($this->icon) : '',
        ];
    }
}
