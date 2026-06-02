<?php

namespace App\Service;

use App\Models\Favorite;
use App\Models\User;

class FavoriteService
{
    public function getUserFavorites(User $user)
    {
        return Favorite::with('unit.media')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }

    public function toggleFavorite(User $user, int $unitId): array
    {
        $favorite = Favorite::where('user_id', $user->id)->where('unit_id', $unitId)->first();

        if ($favorite) {
            $favorite->delete();
            return ['message' => __('api.favorite_removed'), 'status' => false];
        }

        Favorite::create([
            'user_id' => $user->id,
            'unit_id' => $unitId,
        ]);

        return ['message' => __('api.favorite_added'), 'status' => true];
    }
}
