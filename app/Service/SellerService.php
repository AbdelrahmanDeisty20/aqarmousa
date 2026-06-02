<?php

namespace App\Service;

use App\Models\User;

class SellerService
{
    public function getAllSellers()
    {
        // Assuming 'seller' role identifies sellers. Adjust if 'agent' is used or both.
        // Also checking for active status if applicable.
        return User::where('role', 'seller')
            ->where('status', 'approved')
            ->withCount(['units' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();
    }

    public function getSellerById($id)
    {
        return User::where('role', 'seller')
            ->where('status', 'approved')
            ->withCount(['units' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->with(['units' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->find($id);
    }
}
