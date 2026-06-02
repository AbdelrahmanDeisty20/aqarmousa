<?php

namespace App\Service;

use App\Models\City;

class CityService
{
    public function getAllCities()
    {
        return City::all();
    }
}
