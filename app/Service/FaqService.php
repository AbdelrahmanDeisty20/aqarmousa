<?php

namespace App\Service;

use App\Models\Faq;

class FaqService
{
    public function getAll()
    {
        return Faq::latest()->get();
    }
}
