<?php

namespace App\Service;

use App\Models\UnitType;

class UnitTypeService
{
    public function getAll()
    {
        return UnitType::all();
    }
}
