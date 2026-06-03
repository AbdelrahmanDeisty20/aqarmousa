<?php

namespace App\Service;

use App\Models\Unit;
use Illuminate\Pagination\LengthAwarePaginator;

class SellerUnitService
{
    public function getSellerUnits($user, array $filters = [], $perPage = 10)
    {
        $query = Unit::with(['owner', 'governorate', 'type', 'media', 'amenities', 'ownership'])
            ->where('owner_id', $user->id);

        if (isset($filters['governorate_id'])) {
            $query->where('governorate_id', $filters['governorate_id']);
        }

        if (isset($filters['compound_id'])) {
            $query->where('compound_id', $filters['compound_id']);
        }

        if (isset($filters['offer_type'])) {
            $query->where('offer_type', $filters['offer_type']);
        }

        if (isset($filters['unit_type_id'])) {
            $query->where('unit_type_id', $filters['unit_type_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Price Range
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Area Range
        if (isset($filters['min_area'])) {
            $query->where('area', '>=', $filters['min_area']);
        }

        if (isset($filters['max_area'])) {
            $query->where('area', '<=', $filters['max_area']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function createUnit($user, array $data): Unit
    {
        $media = $data['media'] ?? [];
        unset($data['media']);

        // Handle ownership data if provided
        $ownershipData = $data['ownership'] ?? null;
        unset($data['ownership']);

        $data['owner_id'] = $user->id;
        $data['status'] = 'pending';

        $unit = Unit::create($data);

        if (isset($data['amenities'])) {
            $unit->amenities()->sync($data['amenities']);
        }

        if ($ownershipData) {
            $unit->ownership()->create($ownershipData);
        }

        foreach ($media as $item) {
            $this->uploadMedia($unit, $item['file'], $item['type']);
        }

        return $unit->load(['owner', 'governorate', 'compound', 'developer', 'type', 'media', 'amenities', 'ownership']);
    }

    public function updateUnit($user, Unit $unit, array $data): Unit
    {
        if ($unit->owner_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Handle ownership data
        $ownershipData = $data['ownership'] ?? null;
        unset($data['ownership']);

        // Filter out empty or null values to keep old values
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        $unit->update($data);
        $unit->update(['status' => 'pending']);

        if (isset($data['amenities'])) {
            $unit->amenities()->sync($data['amenities']);
        }

        if ($ownershipData) {
            $unit->ownership()->updateOrCreate([], $ownershipData);
        }

        return $unit->load(['owner', 'governorate', 'compound', 'developer', 'type', 'media', 'amenities', 'ownership']);
    }

    public function deleteUnit($user, Unit $unit): void
    {
        if ($unit->owner_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $unit->delete();
    }

    public function uploadMedia(Unit $unit, $file, $type)
    {
        $path = $file->store('units/media', 'public');

        $media = $unit->media()->create([
            'url' => $path,
            'type' => $type,
            'processing_status' => $type === 'video' ? 'pending' : 'completed',
        ]);

        if ($type === 'video') {
            \App\Jobs\ProcessVideoHLS::dispatch($media);
        }

        return $media;
    }

    public function getSellerStats($user): array
    {
        $userId = $user->id;

        return [
            'total_units' => Unit::where('owner_id', $userId)->count(),
            'available_units' => Unit::where('owner_id', $userId)->where('status', 'available')->count(),
            'pending_units' => Unit::where('owner_id', $userId)->where('status', 'pending')->count(),
            'rejected_units' => Unit::where('owner_id', $userId)->where('status', 'rejected')->count(),
        ];
    }

    public function toggleVisibility($user, Unit $unit): Unit
    {
        if ($unit->owner_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $unit->update([
            'is_visible' => !$unit->is_visible
        ]);

        return $unit;
    }
}
