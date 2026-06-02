<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreViewingRequest;
use App\Http\Requests\UpdateViewingRequest;
use App\Http\Resources\ViewingResource;
use App\Service\ViewingService;
use App\Traits\ApiResponse;
use Filament\Actions\Action;

class ViewingController extends Controller
{
    use ApiResponse;

    protected $viewingService;

    public function __construct(ViewingService $viewingService)
    {
        $this->viewingService = $viewingService;
    }

    /**
     * Get all viewings for authenticated user
     */
    public function index()
    {
        $viewings = $this->viewingService->getUserViewings();

        if ($viewings->isEmpty()) {
            return $this->success([], __('api.viewing.no_viewings_found'));
        }

        return $this->success(
            ViewingResource::collection($viewings),
            __('api.viewing.retrieved_successfully')
        );
    }

    /**
     * Create a new viewing request
     */
    public function store(StoreViewingRequest $request)
    {
        $viewing = $this->viewingService->createViewing($request->validated());

        // Notify Admins via Filament
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notifyNow(new \App\Notifications\NewViewingRequestNotification($viewing));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Viewing Notification failed: ' . $e->getMessage());
        }

        return $this->created(
            new ViewingResource($viewing),
            __('api.viewing.created_successfully')
        );
    }

    /**
     * Update viewing request (cancel, accept, or propose new time)
     */
    public function update(UpdateViewingRequest $request, $id)
    {
        $viewing = $this->viewingService->getUserViewing($id);

        // Cancel viewing
        if ($request->has('status') && $request->status == 'cancelled') {
            $this->viewingService->cancelViewing($viewing, $request->validated());
            return $this->success(
                new ViewingResource($viewing->fresh()),
                __('api.viewing.cancelled')
            );
        }

        // Accept admin's suggested time
        if ($request->has('status') && $request->status == 'accepted') {
            $this->viewingService->acceptViewing($viewing);
            return $this->success(
                new ViewingResource($viewing->fresh()),
                __('api.viewing.accepted')
            );
        }

        // Propose new time
        if ($request->has('date') || $request->has('time')) {
            $this->viewingService->proposeNewTime($viewing, $request->validated());
            return $this->success(
                new ViewingResource($viewing->fresh()),
                __('api.viewing.updated_pending_approval')
            );
        }

        return $this->error(__('api.viewing.no_changes_made'), 400);
    }

    /**
     * Delete a viewing request
     */
    public function destroy($id)
    {
        $viewing = $this->viewingService->getUserViewing($id);
        $viewing->delete();

        return $this->success(null, __('api.viewing.deleted_successfully'));
    }
}
