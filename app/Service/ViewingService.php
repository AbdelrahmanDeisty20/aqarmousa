<?php

namespace App\Service;

use App\Models\Viewing;
use App\Models\User;
use App\Notifications\ViewingStatusNotification;
use Illuminate\Support\Facades\Auth;

class ViewingService
{
    /**
     * Get all viewings for the authenticated user
     */
    public function getUserViewings()
    {
        return Viewing::where('user_id', Auth::id())
            ->with(['unit.media', 'unit.city', 'unit.type', 'unit.compound', 'unit.developer'])
            ->latest()
            ->get();
    }

    /**
     * Create a new viewing request
     */
    public function createViewing(array $data): Viewing
    {
        $viewing = Viewing::create([
            'user_id' => Auth::id(),
            'unit_id' => $data['unit_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'date' => $data['date'],
            'time' => $data['time'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        return $viewing->load(['unit.media', 'unit.city', 'unit.type', 'unit.compound', 'unit.developer']);
    }

    /**
     * Cancel a viewing request
     */
    public function cancelViewing(Viewing $viewing, array $data = []): void
    {
        $viewing->update([
            'status' => 'cancelled',
            'user_message' => $data['user_message'] ?? $viewing->user_message,
        ]);

        // Notify Admins about cancellation
        $this->notifyAdmins($viewing, 'cancelled');
    }

    /**
     * Accept admin's suggested time
     */
    public function acceptViewing(Viewing $viewing): void
    {
        $viewing->update(['status' => 'accepted']);
    }

    /**
     * Propose a new time for viewing
     */
    public function proposeNewTime(Viewing $viewing, array $data): void
    {
        $viewing->update([
            'date' => $data['date'] ?? $viewing->date,
            'time' => $data['time'] ?? $viewing->time,
            'user_message' => $data['user_message'] ?? '',
            'status' => 'pending',
        ]);

        // Notify all admin users
        $this->notifyAdmins($viewing, 'user_response');
    }

    /**
     * Notify all admin users
     */
    protected function notifyAdmins(Viewing $viewing, string $type): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $arTitle = '';
            $enTitle = '';
            $arBody = '';
            $enBody = '';
            $color = 'info';

            if ($type === 'user_response') {
                $arTitle = __('admin.notifications.viewing_updated', [], 'ar');
                $enTitle = __('admin.notifications.viewing_updated', [], 'en');

                $arBody = __('admin.notifications.viewing_updated_body', ['name' => $viewing->name, 'unit_id' => $viewing->unit_id], 'ar');
                $enBody = __('admin.notifications.viewing_updated_body', ['name' => $viewing->name, 'unit_id' => $viewing->unit_id], 'en');
                $color = 'warning';
            } elseif ($type === 'cancelled') {
                $arTitle = __('admin.notifications.viewing_cancelled', [], 'ar');
                $enTitle = __('admin.notifications.viewing_cancelled', [], 'en');

                $arBody = __('admin.notifications.viewing_cancelled_body', ['name' => $viewing->name, 'unit_id' => $viewing->unit_id], 'ar');
                $enBody = __('admin.notifications.viewing_cancelled_body', ['name' => $viewing->name, 'unit_id' => $viewing->unit_id], 'en');
                $color = 'danger';
            } else {
                $arTitle = __('admin.notifications.new_viewing_request', [], 'ar');
                $enTitle = __('admin.notifications.new_viewing_request', [], 'en');

                $arBody = __('admin.notifications.new_viewing_request_body', ['name' => $viewing->name, 'unit_id' => $viewing->unit_id], 'ar');
                $enBody = __('admin.notifications.new_viewing_request_body', ['name' => $viewing->name, 'unit_id' => $viewing->unit_id], 'en');
            }

            $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
            $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

            try {
                \Filament\Notifications\Notification::make()
                    ->title(new \Illuminate\Support\HtmlString($titleHtml))
                    ->body(new \Illuminate\Support\HtmlString($bodyHtml))
                    ->icon('heroicon-o-calendar')
                    ->iconColor($color)
                    ->actions([
                        \Filament\Actions\Action::make('view_ar')
                            ->label(__('admin.actions.view', [], 'ar'))
                            ->url('/admin/viewings/' . $viewing->id . '/edit')
                            ->extraAttributes(['class' => 'lang-ar'])
                            ->markAsRead(),
                        \Filament\Actions\Action::make('view_en')
                            ->label(__('admin.actions.view', [], 'en'))
                            ->url('/admin/viewings/' . $viewing->id . '/edit')
                            ->extraAttributes(['class' => 'lang-en'])
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($admin);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Admin Viewing Notification failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Get viewing by ID for authenticated user
     */
    public function getUserViewing(int $id): Viewing
    {
        return Viewing::where('user_id', Auth::id())
            ->with(['unit.media', 'unit.city', 'unit.type', 'unit.compound', 'unit.developer'])
            ->findOrFail($id);
    }
}
