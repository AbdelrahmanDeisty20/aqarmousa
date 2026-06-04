<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MaintenanceServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();
        return [
            'id' => $this->id,
            'title' => $lang === 'ar' ? $this->title_ar : ($this->title_en ?? $this->title_ar),
            'category' => $this->category,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : '',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}