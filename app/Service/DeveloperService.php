<?php

namespace App\Service;

use App\Models\Developer;

class DeveloperService
{
    public function getAllDevelopers()
    {
        return Developer::where('status', 'active')->get();
    }

    public function getDeveloperById($id)
    {
        return Developer::where('status', 'active')->with(['units', 'units.media', 'units.type', 'units.governorate', 'units.compound'])->find($id);
    }

    public function getDeveloperUnits($id, $perPage = 10)
    {
        $developer = Developer::where('status', 'active')->find($id);
        if (!$developer) return null;

        return $developer->units()
            ->with(['media', 'type', 'governorate', 'compound'])
            ->latest()
            ->paginate($perPage);
    }
}
