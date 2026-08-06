<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $filePath = '';
        if ($this->url) {
            $filePath = (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://'))
                ? $this->url
                : Storage::disk('public')->url($this->url);
        }

        $hlsPath = '';
        if ($this->processed_url) {
            $hlsPath = (str_starts_with($this->processed_url, 'http://') || str_starts_with($this->processed_url, 'https://'))
                ? $this->processed_url
                : Storage::disk('public')->url($this->processed_url);
        }

        return [
            'id' => $this->id,
            'file_path' => $filePath,
            'hls_path' => $hlsPath,
            'processing_status' => $this->processing_status ?? 'completed',
            'type' => $this->type ?? 'image',
        ];
    }
}
