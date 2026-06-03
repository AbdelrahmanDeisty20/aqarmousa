<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();

        return [
            'id' => $this->id,
            'title' => ($lang === 'ar' ? $this->title_ar : $this->title_en) ?? '',
            'description' => ($lang === 'ar' ? $this->description_ar : $this->description_en) ?? '',
            'address' => ($lang === 'ar' ? $this->address_ar : $this->address_en) ?? '',
            'price' => $this->price ?? 0,
            'price_per_m2' => $this->price_per_m2 ?? 0,
            'offer_type' => $this->offer_type ?? '',
            'area' => $this->area ?? 0,
            'unit_area' => $this->unit_area ?? 0,
            'internal_area' => $this->internal_area ?? 0,
            'build_year' => $this->build_year ?? '',
            'latitude' => $this->latitude ?? '',
            'longitude' => $this->longitude ?? '',
            'category' => $this->category ?? 'land',
            'discount' => $this->discount ?? 0,
            'length' => $this->length ?? 0,
            'width' => $this->width ?? 0,
            'ownership' => $this->whenLoaded('ownership', fn() => [
                'contract_type' => $this->ownership->contract_type ?? '',
                'is_registered' => (bool) ($this->ownership->is_registered ?? false),
                'plot_number' => $this->ownership->plot_number ?? '',
            ]),
            'status' => $this->status ?? '',
            'is_favourite' => $this->when(auth('sanctum')->check(), function () {
                return \App\Models\Favorite::where('user_id', auth('sanctum')->id())
                    ->where('unit_id', $this->id)
                    ->exists();
            }),
            'is_visible' => (bool) $this->is_visible,
            'development_status' => $this->offer_type === 'rent' ? '' : ($this->development_status ?? ''),
            'owner' => new UserResource($this->whenLoaded('owner')), // Relations usually handled by 'data' wrapper or null if not loaded, but if loaded and null, Resource handles it? No, if relation is null, new Resource(null) might return null resource.
            // However, relation logic with `new Resource` on null often returns null.
            // If flutter expects object, returning null is okay ONLY if they made it nullable.
            // The user said "if defined as required... cannot return null".
            // So for objects, we might need to return empty object or null?
            // Usually Objects are Nullable in Flutter models, but primitive types (String, int) are often strict.
            // I will focus on primitives first as requested.
            'media' => UnitMediaResource::collection($this->whenLoaded('media')),
            'governorate' => new GovernorateResource($this->whenLoaded('governorate')),
            'unit_type' => [
                'id' => $this->whenLoaded('type', fn() => $this->type->id ?? 0),
                'name' => $this->whenLoaded('type', fn() => (app()->getLocale() === 'ar' ? $this->type->name_ar : $this->type->name_en) ?? ''),
            ],
            'compound' => new CompoundResource($this->whenLoaded('compound')),
            'developer' => new DeveloperResource($this->whenLoaded('developer')),
            'amenities' => AmenityResource::collection($this->whenLoaded('amenities')),
            'average_rating' => (float) ($this->reviews_avg_rating ?? 0),
            'reviews_count' => (int) ($this->reviews_count ?? 0),
            'created_at' => $this->created_at?->toISOString() ?? '',
        ];
    }
}
