<?php

namespace App\Service;

use App\Models\MaintenanceBooking;
use App\Models\MaintenanceService;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MaintenanceManagementService
{
    /**
     * Get all maintenance services grouped by category.
     *
     * @return Collection
     */
    public function getAllServicesGrouped(): Collection
    {
        return MaintenanceService::all()->groupBy('category');
    }

    /**
     * Create a new maintenance booking.
     *
     * @param array $data
     * @return MaintenanceBooking
     */
    public function createBooking(array $data): MaintenanceBooking
    {
        $booking = MaintenanceBooking::create([
            'maintenance_service_id' => $data['maintenance_service_id'],
            'user_id' => auth()->id(),
            'phone' => $data['phone'],
            'address' => $data['address'],
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        $this->notifyAdmins($booking);

        return $booking;
    }

    /**
     * Get all bookings for the authenticated user.
     *
     * @return Collection
     */
    public function getUserBookings(): Collection
    {
        return MaintenanceBooking::where('user_id', auth()->id())
            ->with('service')
            ->latest()
            ->get();
    }

    /**
     * Update an existing maintenance booking.
     *
     * @param MaintenanceBooking $booking
     * @param array $data
     * @return MaintenanceBooking
     */
    public function updateBookingDetails(MaintenanceBooking $booking, array $data): MaintenanceBooking
    {
        $booking->update($data);
        $this->notifyAdminsOfUpdate($booking);
        return $booking;
    }

    /**
     * Delete a maintenance booking.
     *
     * @param MaintenanceBooking $booking
     * @return bool|null
     */
    public function deleteBooking(MaintenanceBooking $booking): ?bool
    {
        return $booking->delete();
    }

    /**
     * Check if user already has a pending booking for this service.
     *
     * @param int $serviceId
     * @param int $userId
     * @return bool
     */
    public function hasPendingBooking(int $serviceId, int $userId): bool
    {
        return MaintenanceBooking::where('maintenance_service_id', $serviceId)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Notify all admin users about the new booking.
     *
     * @param MaintenanceBooking $booking
     * @return void
     */
    protected function notifyAdmins(MaintenanceBooking $booking): void
    {
        try {
            $admins = User::where('role', 'admin')->get();
            $userName = auth()->user()->name ?? 'مستخدم';

            $serviceTitleAr = $booking->service->title_ar;
            $serviceTitleEn = $booking->service->title_en ?? $booking->service->title_ar;

            $arTitle = __('admin.notifications.new_maintenance_booking', [], 'ar');
            $enTitle = __('admin.notifications.new_maintenance_booking', [], 'en');

            $arBody = __('admin.notifications.new_maintenance_booking_body', ['name' => $userName, 'service' => $serviceTitleAr], 'ar');
            $enBody = __('admin.notifications.new_maintenance_booking_body', ['name' => $userName, 'service' => $serviceTitleEn], 'en');

            $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
            $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

            foreach ($admins as $admin) {
                Notification::make()
                    ->title(new \Illuminate\Support\HtmlString($titleHtml))
                    ->body(new \Illuminate\Support\HtmlString($bodyHtml))
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->iconColor('success')
                    ->actions([
                        \Filament\Actions\Action::make('view_ar')
                            ->label(__('admin.actions.view', [], 'ar'))
                            ->url('/admin/maintenance-bookings/' . $booking->id . '/edit')
                            ->extraAttributes(['class' => 'lang-ar'])
                            ->markAsRead(),
                        \Filament\Actions\Action::make('view_en')
                            ->label(__('admin.actions.view', [], 'en'))
                            ->url('/admin/maintenance-bookings/' . $booking->id . '/edit')
                            ->extraAttributes(['class' => 'lang-en'])
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($admin);
            }
        } catch (\Exception $e) {
            Log::error('Maintenance Booking Notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Notify all admin users about the update of a booking.
     *
     * @param MaintenanceBooking $booking
     * @return void
     */
    protected function notifyAdminsOfUpdate(MaintenanceBooking $booking): void
    {
        try {
            $admins = User::where('role', 'admin')->get();
            $userName = auth()->user()->name ?? 'مستخدم';

            $serviceTitleAr = $booking->service->title_ar;
            $serviceTitleEn = $booking->service->title_en ?? $booking->service->title_ar;

            $arTitle = __('admin.notifications.maintenance_booking_updated', [], 'ar');
            $enTitle = __('admin.notifications.maintenance_booking_updated', [], 'en');

            $arBody = __('admin.notifications.maintenance_booking_updated_body', ['name' => $userName, 'service' => $serviceTitleAr], 'ar');
            $enBody = __('admin.notifications.maintenance_booking_updated_body', ['name' => $userName, 'service' => $serviceTitleEn], 'en');

            $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
            $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

            foreach ($admins as $admin) {
                Notification::make()
                    ->title(new \Illuminate\Support\HtmlString($titleHtml))
                    ->body(new \Illuminate\Support\HtmlString($bodyHtml))
                    ->icon('heroicon-o-pencil-square')
                    ->iconColor('info')
                    ->actions([
                        \Filament\Actions\Action::make('view_ar')
                            ->label(__('admin.actions.view', [], 'ar'))
                            ->url('/admin/maintenance-bookings/' . $booking->id . '/edit')
                            ->extraAttributes(['class' => 'lang-ar'])
                            ->markAsRead(),
                        \Filament\Actions\Action::make('view_en')
                            ->label(__('admin.actions.view', [], 'en'))
                            ->url('/admin/maintenance-bookings/' . $booking->id . '/edit')
                            ->extraAttributes(['class' => 'lang-en'])
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($admin);
            }
        } catch (\Exception $e) {
            Log::error('Maintenance Booking Update Notification failed: ' . $e->getMessage());
        }
    }
}
