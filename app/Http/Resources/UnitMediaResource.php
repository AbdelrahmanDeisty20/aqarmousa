<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_path' => $this->url ? env('APP_URL') . Storage::disk('public')->url($this->url) : '',
            'hls_path' => $this->processed_url ? env('APP_URL') . Storage::disk('public')->url($this->processed_url) : '',
            'processing_status' => $this->processing_status ?? 'pending',
            'type' => $this->type ?? 'image',
        ];
    }
}
