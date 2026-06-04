<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();
        return [
            'id' => $this->id,
            'name' => $lang === 'ar' ? $this->title_ar : $this->title_en,
            'description' => $lang === 'ar' ? $this->content_ar : $this->content_en,
            'icon' => $this->icon ? Storage::disk('public')->url($this->icon) : '',
        ];
    }
}