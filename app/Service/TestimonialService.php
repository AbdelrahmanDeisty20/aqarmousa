<?php

namespace App\Service;

use App\Models\Testimonial;
use App\Models\User;

class TestimonialService
{
    /**
     * Submit a new testimonial
     */
    public function submitTestimonial(User $user, array $data): Testimonial
    {
        return Testimonial::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'position' => $data['position'] ?? 'Client',
            'content' => $data['content'],
            'image' => $user->avatar ?? '',
            'status' => false, // Pending approval
        ]);
    }

    /**
     * Get all testimonials for the current user
     */
    public function getUserTestimonials(User $user)
    {
        return Testimonial::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }

    /**
     * Update a testimonial (only if it belongs to the user)
     */
    public function updateTestimonial(User $user, int $id, array $data): Testimonial
    {
        $testimonial = Testimonial::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // If the testimonial image is empty, try to sync it from the user's current avatar
        if (empty($testimonial->image) && !empty($user->avatar)) {
            $data['image'] = $user->avatar;
        }

        // Filter out empty or null values to keep old values
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        $testimonial->update($data);

        return $testimonial->fresh();
    }

    /**
     * Delete a testimonial (only if it belongs to the user)
     */
    public function deleteTestimonial(User $user, int $id): bool
    {
        $testimonial = Testimonial::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return $testimonial->delete();
    }

    /**
     * Get active testimonials for public display
     */
    public function getActiveTestimonials()
    {
        return Testimonial::where('status', true)->latest()->paginate(10);
    }
}
