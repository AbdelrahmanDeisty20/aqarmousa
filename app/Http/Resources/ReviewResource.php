<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at->toISOString(),
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar ? env('APP_URL') . Storage::disk('public')->url($this->user->avatar) : '',
            ],
            // Only include unit if it's loaded (useful for getUserReviews)
            'unit' => $this->whenLoaded('unit', function () {
                $lang = app()->getLocale();
                return [
                    'id' => $this->unit_id,
                    'title' => $lang === 'ar' ? $this->unit->title_ar : $this->unit->title_en, // title accessor handles localization
                ];
            }),
        ];
    }
}