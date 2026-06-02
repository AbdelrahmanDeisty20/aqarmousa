<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Service\TestimonialService;
use App\Http\Resources\TestimonialResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    use ApiResponse;
    protected $testimonialService;

    public function __construct(TestimonialService $testimonialService)
    {
        $this->testimonialService = $testimonialService;
    }

    /**
     * Public index for active testimonials
     */
    public function index()
    {
        $testimonials = $this->testimonialService->getActiveTestimonials();

        if ($testimonials->isEmpty()) {
            return $this->error(__('api.no_testimonials_yet'), 200);
        }

        return $this->paginated(TestimonialResource::class, $testimonials);
    }

    /**
     * User's own testimonials
     */
    public function myTestimonials(Request $request)
    {
        $testimonials = $this->testimonialService->getUserTestimonials($request->user());

        if ($testimonials->isEmpty()) {
            return $this->error(__('api.not_found'), 200);
        }

        return $this->paginated(TestimonialResource::class, $testimonials);
    }

    public function store(\App\Http\Requests\Api\Testimonial\StoreTestimonialRequest $request)
    {
        $testimonial = $this->testimonialService->submitTestimonial($request->user(), $request->validated());

        // Notify Admins via Filament (Synchronous)
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notifyNow(new \App\Notifications\NewTestimonialNotification($testimonial));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Testimonial Notification failed: ' . $e->getMessage());
        }

        return $this->created(new TestimonialResource($testimonial), __('api.testimonial_submitted'));
    }

    public function update(\App\Http\Requests\Api\Testimonial\UpdateTestimonialRequest $request, $id)
    {
        try {
            $testimonial = $this->testimonialService->updateTestimonial($request->user(), $id, $request->validated());
            return $this->success(new TestimonialResource($testimonial), __('api.testimonial_updated'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error(__('api.testimonial_not_found'), 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->testimonialService->deleteTestimonial($request->user(), $id);
            return $this->success([], __('api.testimonial_deleted'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error(__('api.testimonial_not_found'), 403);
        }
    }
}
