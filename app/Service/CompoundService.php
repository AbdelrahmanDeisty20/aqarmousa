<?php

namespace App\Service;

use App\Models\Compound;

class CompoundService
{
    public function getAllCompounds()
    {
        return Compound::with('governorate')->latest()->get();
    }

    public function getCompoundById(int $id)
    {
        return Compound::with(['governorate', 'units', 'units.media', 'units.type', 'units.governorate', 'units.developer'])->findOrFail($id);
    }

    public function getCompoundUnits($id, $perPage = 10)
    {
        $compound = Compound::find($id);
        if (!$compound) return null;

        return $compound->units()
            ->with(['media', 'type', 'governorate', 'developer'])
            ->latest()
            ->paginate($perPage);
    }
}
