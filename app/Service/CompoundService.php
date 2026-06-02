<?php

namespace App\Service;

use App\Models\Compound;

class CompoundService
{
    public function getAllCompounds()
    {
        return Compound::with('city')->latest()->get();
    }

    public function getCompoundById(int $id)
    {
        return Compound::with(['city', 'units', 'units.media', 'units.type', 'units.city', 'units.developer'])->findOrFail($id);
    }

    public function getCompoundUnits($id, $perPage = 10)
    {
        $compound = Compound::find($id);
        if (!$compound) return null;

        return $compound->units()
            ->with(['media', 'type', 'city', 'developer'])
            ->latest()
            ->paginate($perPage);
    }
}
