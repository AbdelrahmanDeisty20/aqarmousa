<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Review\StoreReviewRequest;
use App\Http\Requests\Api\Review\UpdateReviewRequest;
use App\Service\ReviewService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }


    // Get all reviews by the current user
    public function index(Request $request)
    {
        $reviews = $this->reviewService->getUserReviews($request->user());
        if ($reviews->isEmpty()) {
            return $this->error(__('api.no_reviews_found'), 200);
        }
        return $this->paginated(\App\Http\Resources\ReviewResource::class, $reviews);
    }

    public function store(StoreReviewRequest $request)
    {
        $review = $this->reviewService->submitReview($request->user(), $request->validated());

        // Notify Admins via Filament (Synchronous)
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notifyNow(new \App\Notifications\NewReviewNotification($review));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Review Notification failed: ' . $e->getMessage());
        }

        return new \App\Http\Resources\ReviewResource($review);
    }

    // Update user's own review
    public function update(UpdateReviewRequest $request, $id)
    {
        try {
            $review = $this->reviewService->updateReview($request->user(), $id, $request->validated());
            return new \App\Http\Resources\ReviewResource($review);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => __('api.review_not_found')
            ], 403);
        }
    }

    // Delete user's own review
    public function destroy(Request $request, $id)
    {
        try {
            $this->reviewService->deleteReview($request->user(), $id);

            return response()->json([
                'message' => __('api.review_deleted')
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => __('api.review_not_found')
            ], 403);
        }
    }
}
