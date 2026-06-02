<?php

namespace App\Service;

use App\Models\Service;

class GeneralServiceService
{
    public function getAll()
    {
        return Service::latest()->get();
    }

    public function getById($id)
    {
        return Service::findOrFail($id);
    }
}
