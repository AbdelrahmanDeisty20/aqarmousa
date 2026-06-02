<?php

namespace App\Notifications;

use App\Models\Viewing;
use Filament\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class NewViewingRequestNotification extends Notification
{
    use Queueable;

    protected $viewing;

    /**
     * Create a new notification instance.
     */
    public function __construct(Viewing $viewing)
    {
        $this->viewing = $viewing;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $arTitle = __('admin.notifications.new_viewing_request', [], 'ar');
        $enTitle = __('admin.notifications.new_viewing_request', [], 'en');

        $arBody = __('admin.notifications.new_viewing_request_body', [
            'name' => $this->viewing->name,
            'unit_id' => $this->viewing->unit_id
        ], 'ar');

        $enBody = __('admin.notifications.new_viewing_request_body', [
            'name' => $this->viewing->name,
            'unit_id' => $this->viewing->unit_id
        ], 'en');

        $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
        $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

        return FilamentNotification::make()
            ->title(new \Illuminate\Support\HtmlString($titleHtml))
            ->body(new \Illuminate\Support\HtmlString($bodyHtml))
            ->icon('heroicon-o-calendar')
            ->iconColor('info')
            ->actions([
                Action::make('view_ar')
                    ->label(__('admin.actions.view', [], 'ar'))
                    ->url('/admin/viewings/' . $this->viewing->id . '/edit')
                    ->extraAttributes(['class' => 'lang-ar'])
                    ->markAsRead(),
                Action::make('view_en')
                    ->label(__('admin.actions.view', [], 'en'))
                    ->url('/admin/viewings/' . $this->viewing->id . '/edit')
                    ->extraAttributes(['class' => 'lang-en'])
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
