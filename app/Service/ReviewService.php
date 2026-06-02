<?php

namespace App\Service;

use App\Models\Review;
use App\Models\User;

class ReviewService
{
    public function submitReview(User $user, array $data): Review
    {
        return Review::updateOrCreate(
            ['user_id' => $user->id, 'unit_id' => $data['unit_id']],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null]
        );
    }

    // Get all reviews by the current user
    public function getUserReviews(User $user)
    {
        return Review::with(['unit.media', 'unit.city'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }

    // Get all reviews for a specific unit
    public function getUnitReviews(int $unitId)
    {
        return Review::with('user:id,name,avatar')
            ->where('unit_id', $unitId)
            ->latest()
            ->paginate(3);
    }

    // Update a review (only if it belongs to the user)
    public function updateReview(User $user, int $reviewId, array $data): Review
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Filter out empty or null values to keep old values
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        $review->update($data);

        return $review->fresh();
    }

    // Delete a review (only if it belongs to the user)
    public function deleteReview(User $user, int $reviewId): bool
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return $review->delete();
    }
}
