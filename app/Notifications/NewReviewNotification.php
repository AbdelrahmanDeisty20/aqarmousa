<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use Filament\Notifications\Notification as FilamentNotification;
use Filament\Actions\Action;

class NewReviewNotification extends Notification
{
    use Queueable;

    public $review;

    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
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
        $arTitle = __('admin.notifications.new_review_posted', [], 'ar');
        $enTitle = __('admin.notifications.new_review_posted', [], 'en');

        $arBody = __('admin.notifications.new_review_body', [
            'unit_id' => $this->review->unit_id,
            'rating' => $this->review->rating
        ], 'ar');

        $enBody = __('admin.notifications.new_review_body', [
            'unit_id' => $this->review->unit_id,
            'rating' => $this->review->rating
        ], 'en');

        $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
        $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

        return FilamentNotification::make()
            ->title(new \Illuminate\Support\HtmlString($titleHtml))
            ->body(new \Illuminate\Support\HtmlString($bodyHtml))
            ->icon('heroicon-o-star')
            ->iconColor('info')
            ->actions([
                Action::make('view_ar')
                    ->label(__('admin.resources.review', [], 'ar'))
                    ->url(\App\Filament\Resources\Reviews\ReviewResource::getUrl('edit', ['record' => $this->review]))
                    ->extraAttributes(['class' => 'lang-ar'])
                    ->markAsRead(),
                Action::make('view_en')
                    ->label(__('admin.resources.review', [], 'en'))
                    ->url(\App\Filament\Resources\Reviews\ReviewResource::getUrl('edit', ['record' => $this->review]))
                    ->extraAttributes(['class' => 'lang-en'])
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
