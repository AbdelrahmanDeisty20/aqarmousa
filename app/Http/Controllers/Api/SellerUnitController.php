<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Seller\StoreUnitRequest;
use App\Http\Requests\Api\Seller\UpdateUnitRequest;
use App\Http\Requests\Api\Seller\UploadMediaRequest;
use App\Http\Resources\UnitResource;
use App\Http\Resources\UnitListResource;
use App\Models\Unit;
use App\Service\SellerUnitService;
use Illuminate\Http\Request;

class SellerUnitController extends Controller
{
    protected $sellerUnitService;

    public function __construct(SellerUnitService $sellerUnitService)
    {
        $this->sellerUnitService = $sellerUnitService;
    }

    public function index(Request $request)
    {
        $units = $this->sellerUnitService->getSellerUnits($request->user(), $request->all(), $request->get('per_page', 10));

        if ($units->isEmpty()) {
            return $this->error(__('api.no_units_yet'), 200);
        }

        return $this->paginated(UnitListResource::class, $units);
    }

    public function store(StoreUnitRequest $request)
    {
        $unit = $this->sellerUnitService->createUnit($request->user(), $request->validated());

        // Notify Admins via Filament (Synchronous)
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notifyNow(new \App\Notifications\NewUnitNotification($unit));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Notification failed: ' . $e->getMessage());
        }

        return $this->success(new UnitResource($unit), __('api.unit_created'), 201);
    }

    public function update(UpdateUnitRequest $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $updatedUnit = $this->sellerUnitService->updateUnit($request->user(), $unit, $request->validated());
        return $this->success(new UnitResource($updatedUnit), __('api.unit_updated'));
    }

    public function destroy(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $this->sellerUnitService->deleteUnit($request->user(), $unit);
        return $this->success([], __('api.unit_deleted'));
    }

    public function uploadMedia(UploadMediaRequest $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $media = $this->sellerUnitService->uploadMedia($unit, $request->file('file'), $request->type);

        return $this->success($media, __('api.media_uploaded'));
    }

    public function stats(Request $request)
    {
        $stats = $this->sellerUnitService->getSellerStats($request->user());
        return $this->success($stats);
    }
    public function toggleVisibility(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $updatedUnit = $this->sellerUnitService->toggleVisibility($request->user(), $unit);

        $status = $updatedUnit->is_visible ? 'active' : 'inactive';
        return $this->success(new UnitResource($updatedUnit), __('api.unit_visibility_updated', ['status' => $status]));
    }
}
