<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = app()->getLocale();
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : '',
            'title' => $lang === 'ar' ? $this->title_ar : $this->title_en,
            'content' => $lang === 'ar' ? $this->content_ar : $this->content_en,
            'team_members' => collect($this->team_members)->map(fn($member) => [
                'name' => $member['name'] ?? '',
                'position' => $member['position'] ?? '',
                'photo' => isset($member['photo']) ? env('APP_URL') . Storage::disk('public')->url($member['photo']) : '',
            ]),
            'features' => collect($this->features)->map(fn($feature) => [
                'title' => $lang === 'ar' ? ($feature['title_ar'] ?? '') : ($feature['title_en'] ?? ($feature['title_ar'] ?? '')),
                'description' => $lang === 'ar' ? ($feature['description_ar'] ?? '') : ($feature['description_en'] ?? ($feature['description_ar'] ?? '')),
                'icon' => isset($feature['icon']) ? env('APP_URL') . Storage::disk('public')->url($feature['icon']) : '',
            ]),
            'sections' => collect($this->sections)->map(fn($section) => [
                'title' => $lang === 'ar' ? ($section['title_ar'] ?? '') : ($section['title_en'] ?? ''),
                'content' => array_values(array_filter(explode("\n", str_replace("\r", "", $lang === 'ar' ? ($section['content_ar'] ?? '') : ($section['content_en'] ?? ''))))),
            ]),
        ];
    }
}
