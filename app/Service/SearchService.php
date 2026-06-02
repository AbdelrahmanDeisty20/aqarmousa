<?php

namespace App\Service;

use App\Models\Unit;
use App\Models\City;
use App\Models\Compound;
use App\Models\UnitType;
use App\Models\Developer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SearchService
{
    public function globalSearch(string $query)
    {
        $words = explode(' ', $query);
        $q = '%' . $query . '%';

        $units = Unit::with(['city', 'compound', 'developer', 'type', 'media'])
            ->where(function ($sub) use ($query, $words) {
                // Search as whole phrase
                $sub->where('title_ar', 'like', "%{$query}%")
                    ->orWhere('title_en', 'like', "%{$query}%")
                    ->orWhere('address_ar', 'like', "%{$query}%")
                    ->orWhere('address_en', 'like', "%{$query}%")
                    ->orWhere('description_ar', 'like', "%{$query}%")
                    ->orWhere('description_en', 'like', "%{$query}%");

                // Then search by individual words - REMOVED length check to match ANY character/part
                foreach ($words as $word) {
                    $sub->orWhere('title_ar', 'like', "%{$word}%")
                        ->orWhere('title_en', 'like', "%{$word}%")
                        ->orWhere('address_ar', 'like', "%{$word}%")
                        ->orWhere('address_en', 'like', "%{$word}%");
                }

                $sub->orWhereHas('city', function ($q) use ($query) {
                    $q->where('name_ar', 'like', "%{$query}%")
                        ->orWhere('name_en', 'like', "%{$query}%");
                });

                $sub->orWhereHas('compound', function ($q) use ($query) {
                    $q->where('name_ar', 'like', "%{$query}%")
                        ->orWhere('name_en', 'like', "%{$query}%");
                });
            })
            // Simple ordering: Matches in address or title come first
            ->orderByRaw("
                CASE
                    WHEN address_ar LIKE ? OR address_en LIKE ? THEN 1
                    WHEN title_ar LIKE ? OR title_en LIKE ? THEN 2
                    ELSE 3
                END
            ", ["%{$query}%", "%{$query}%", "%{$query}%", "%{$query}%"])
            ->take(20) // Increased limit to find more potential matches
            ->get();

        $cities = City::where(function ($sub) use ($query, $words) {
            $sub->where('name_ar', 'like', "%{$query}%")
                ->orWhere('name_en', 'like', "%{$query}%");
            foreach ($words as $word) {
                if (mb_strlen($word) > 2) {
                    $sub->orWhere('name_ar', 'like', "%{$word}%")
                        ->orWhere('name_en', 'like', "%{$word}%");
                }
            }
        })
            ->take(5)
            ->get();

        $compounds = Compound::with('city')
            ->where(function ($sub) use ($query, $words) {
                $sub->where('name_ar', 'like', "%{$query}%")
                    ->orWhere('name_en', 'like', "%{$query}%")
                    ->orWhere('description_ar', 'like', "%{$query}%")
                    ->orWhere('description_en', 'like', "%{$query}%");
                foreach ($words as $word) {
                    if (mb_strlen($word) > 2) {
                        $sub->orWhere('name_ar', 'like', "%{$word}%")
                            ->orWhere('name_en', 'like', "%{$word}%");
                    }
                }
            })
            ->take(5)
            ->get();

        $unitTypes = UnitType::where(function ($sub) use ($query, $words) {
            $sub->where('name_ar', 'like', "%{$query}%")
                ->orWhere('name_en', 'like', "%{$query}%");
            foreach ($words as $word) {
                if (mb_strlen($word) > 2) {
                    $sub->orWhere('name_ar', 'like', "%{$word}%")
                        ->orWhere('name_en', 'like', "%{$word}%");
                }
            }
        })
            ->take(5)
            ->get();

        $developers = Developer::where(function ($sub) use ($query, $words) {
            $sub->where('name_ar', 'like', "%{$query}%")
                ->orWhere('name_en', 'like', "%{$query}%");
            foreach ($words as $word) {
                if (mb_strlen($word) > 2) {
                    $sub->orWhere('name_ar', 'like', "%{$word}%")
                        ->orWhere('name_en', 'like', "%{$word}%");
                }
            }
        })
            ->take(5)
            ->take(5)
            ->get();

        $sellers = User::where('role', 'seller')
            ->where('status', 'approved')
            ->where(function ($sub) use ($query, $words) {
                $sub->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");

                foreach ($words as $word) {
                    if (mb_strlen($word) > 2) {
                        $sub->orWhere('name', 'like', "%{$word}%");
                    }
                }
            })
            ->take(5)
            ->get();

        return [
            'units' => $units,
            'cities' => $cities,
            'compounds' => $compounds,
            'unit_types' => $unitTypes,
            'developers' => $developers,
            'sellers' => $sellers,
        ];
    }
}
