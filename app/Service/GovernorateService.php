<?php

namespace App\Service;

use App\Models\Governorate;

class GovernorateService
{
    public function getAllGovernorates()
    {
        return Governorate::all();
    }
}
