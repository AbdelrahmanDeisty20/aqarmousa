<?php

namespace App\Service;

use App\Models\Page;

class PageService
{
    public function getBySlug(string $identifier)
    {
        return Page::where('slug', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();
    }

    public function getAll()
    {
        return Page::all();
    }
}
