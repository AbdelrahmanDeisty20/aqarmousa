<?php

namespace App\Http\Requests\Api\Seller;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'seller' || $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'address_ar' => 'required|string|max:500',
            'address_en' => 'nullable|string|max:500',
            'price' => 'required|numeric',
            'offer_type' => 'required|in:sale,rent',
            'area' => 'required|numeric',
            'rooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'city_id' => 'required|exists:cities,id',
            'unit_type_id' => 'required|exists:unit_types,id',
            'city_id' => 'required|exists:cities,id',
            'unit_type_id' => 'required|exists:unit_types,id',
            'compound_id' => 'nullable|exists:compounds,id',
            'developer_id' => 'nullable|exists:developers,id',
            'land_area' => 'nullable|numeric',
            'internal_area' => 'nullable|numeric',
            'price_per_m2' => 'nullable|numeric',
            'garages' => 'nullable|integer',
            'build_year' => 'nullable|integer|between:1900,' . (date('Y') + 5),
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'media' => 'nullable|array',
            'media.*.file' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,obj,glb|max:20480',
            'media.*.type' => 'required|in:image,video,3d,floorplan',
            'development_status' => 'nullable|required_if:offer_type,sale|in:primary,resale',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ];
    }
}
