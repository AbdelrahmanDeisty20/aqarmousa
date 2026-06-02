<?php

namespace App\Http\Controllers\Api\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Maintenance\StoreMaintenanceBookingRequest;
use App\Http\Requests\Api\Maintenance\UpdateMaintenanceBookingRequest;
use App\Http\Resources\MaintenanceBookingResource;
use App\Http\Resources\MaintenanceServiceResource;
use App\Models\MaintenanceBooking;
use App\Service\MaintenanceManagementService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class MaintenanceController extends Controller
{
    use ApiResponse;

    protected $maintenanceService;

    public function __construct(MaintenanceManagementService $maintenanceService)
    {
        $this->maintenanceService = $maintenanceService;
    }

    /**
     * Get all maintenance services grouped by category.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $groupedServices = $this->maintenanceService->getAllServicesGrouped();

        $data = $groupedServices->map(function ($services) {
            return MaintenanceServiceResource::collection($services);
        });

        return $this->success($data);
    }

    /**
     * Get user's maintenance bookings.
     *
     * @return JsonResponse
     */
    public function myBookings(): JsonResponse
    {
        $bookings = $this->maintenanceService->getUserBookings();
        return $this->success(MaintenanceBookingResource::collection($bookings));
    }

    /**
     * Store a new maintenance booking.
     *
     * @param StoreMaintenanceBookingRequest $request
     * @return JsonResponse
     */
    public function store(StoreMaintenanceBookingRequest $request): JsonResponse
    {
        $userId = auth()->id();
        $serviceId = $request->maintenance_service_id;

        if ($this->maintenanceService->hasPendingBooking($serviceId, $userId)) {
            return $this->error(__('admin.fields.already_booked'), 422);
        }

        $booking = $this->maintenanceService->createBooking($request->validated());

        return $this->success(
            new MaintenanceBookingResource($booking->load('service')),
            __('admin.fields.pending_confirmation')
        );
    }

    /**
     * Update a pending maintenance booking.
     *
     * @param UpdateMaintenanceBookingRequest $request
     * @param MaintenanceBooking $booking
     * @return JsonResponse
     */
    public function update(UpdateMaintenanceBookingRequest $request, MaintenanceBooking $booking): JsonResponse
    {
        // Check ownership
        if ($booking->user_id !== auth()->id()) {
            return $this->error(__('api.auth.unauthorized'), 403);
        }

        // Check status
        if ($booking->status !== 'pending') {
            return $this->error(__('api.maintenance.cannot_update_non_pending'), 422); // Reusing existing translation or add new
        }

        $data = array_filter($request->validated(), fn ($value) => !is_null($value) && $value !== '');

        $updatedBooking = $this->maintenanceService->updateBookingDetails($booking, $data);

        return $this->success(new MaintenanceBookingResource($updatedBooking->load('service')));
    }

    /**
     * Delete a pending maintenance booking.
     *
     * @param MaintenanceBooking $booking
     * @return JsonResponse
     */
    public function destroy(MaintenanceBooking $booking): JsonResponse
    {
        // Check ownership
        if ($booking->user_id !== auth()->id()) {
            return $this->error(__('api.auth.unauthorized'), 403);
        }

        // Check status
        if ($booking->status !== 'pending') {
            return $this->error(__('api.maintenance.cannot_delete_non_pending'), 422);
        }

        $this->maintenanceService->deleteBooking($booking);

        return $this->success('', __('api.maintenance.deleted_successfully'));
    }
}