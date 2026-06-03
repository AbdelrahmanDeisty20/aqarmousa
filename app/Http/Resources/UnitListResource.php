<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitListResource extends JsonResource
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
            "id" => $this->id,
            "title" => ($lang === "ar" ? $this->title_ar : $this->title_en) ?? "",
            "is_visible" => (bool) $this->is_visible,
            'is_favourite' => $this->when(auth('sanctum')->check(), function () {
                return \App\Models\Favorite::where('user_id', auth('sanctum')->id())
                    ->where('unit_id', $this->id)
                    ->exists();
            }),
            "address" => ($lang === "ar" ? $this->address_ar : $this->address_en) ?? "",
            "price" => $this->price ?? 0,
            "discount" => $this->discount ?? 0,
            "status" => $this->status ?? "",
            "offer_type" => $this->offer_type ?? "",
            "area" => $this->area ?? 0,
            "length" => $this->length ?? 0,
            "width" => $this->width ?? 0,
            "category" => $this->category ?? 'land',
            "rooms" => $this->rooms ?? 0,
            "bathrooms" => $this->bathrooms ?? 0,
            "garages" => $this->garages ?? 0,
            "governorate" => new GovernorateResource($this->governorate),
            "compound" => $this->whenLoaded("compound", function () use ($lang) {
                return [
                    "id" => $this->compound_id,
                    "name" => ($lang === "ar" ? ($this->compound->name_ar ?? "") : ($this->compound->name_en ?? "")),
                ];
            }),
            "developer" => $this->whenLoaded("developer", function () use ($lang) {
                return [
                    "id" => $this->developer_id,
                    "name" => ($lang === "ar" ? ($this->developer->name_ar ?? "") : ($this->developer->name_en ?? "")),
                ];
            }),
            "unit_type" => [
                "id" => $this->unit_type_id,
                "name" => ($lang === "ar" ? ($this->type->name_ar ?? "") : ($this->type->name_en ?? "")),
            ],
            "main_image" => $this->whenLoaded("media", function () {
                $image = $this->media->where("type", "image")->first();
                return $image ? env('APP_URL') . Storage::disk("public")->url($image->url) : "";
            }),
            "average_rating" => (float) ($this->reviews_avg_rating ?? 0),
            "reviews_count" => (int) ($this->reviews_count ?? 0),
            "created_at" => $this->created_at?->toISOString() ?? "",
        ];
    }
}
