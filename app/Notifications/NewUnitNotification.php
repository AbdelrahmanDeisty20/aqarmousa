<?php

namespace App\Notifications;

use App\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Actions\Action;

class NewUnitNotification extends Notification
{
    use Queueable;

    public $unit;

    /**
     * Create a new notification instance.
     */
    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
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
        $arTitle = __('admin.notifications.new_unit_added', [], 'ar');
        $enTitle = __('admin.notifications.new_unit_added', [], 'en');

        $unitTitle = $this->unit->title_ar ?: $this->unit->title_en;
        $sellerName = $this->unit->owner->name ?? 'Unknown';

        $arBody = __('admin.notifications.new_unit_body', [
            'title' => $unitTitle,
            'seller' => $sellerName
        ], 'ar');

        $enBody = __('admin.notifications.new_unit_body', [
            'title' => $this->unit->title_en ?: $this->unit->title_ar,
            'seller' => $sellerName
        ], 'en');

        $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
        $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

        return FilamentNotification::make()
            ->title(new \Illuminate\Support\HtmlString($titleHtml))
            ->body(new \Illuminate\Support\HtmlString($bodyHtml))
            ->icon('heroicon-o-home-modern')
            ->iconColor('info')
            ->actions([
                Action::make('view_ar')
                    ->label(__('admin.resources.unit', [], 'ar'))
                    ->url("/admin/units/{$this->unit->id}/edit")
                    ->extraAttributes(['class' => 'lang-ar'])
                    ->markAsRead(),
                Action::make('view_en')
                    ->label(__('admin.resources.unit', [], 'en'))
                    ->url("/admin/units/{$this->unit->id}/edit")
                    ->extraAttributes(['class' => 'lang-en'])
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
