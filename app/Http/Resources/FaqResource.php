<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();
        return [
            'id' => $this->id,
            'question' => $lang === 'ar' ? $this->question_ar : $this->question_en,
            'answer' => $lang === 'ar' ? $this->answer_ar : $this->answer_en,
        ];
    }
}
