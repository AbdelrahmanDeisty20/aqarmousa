<?php

namespace App\Notifications;

use App\Models\Testimonial;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use Filament\Notifications\Notification as FilamentNotification;
use Filament\Actions\Action;

class NewTestimonialNotification extends Notification
{
    use Queueable;

    public $testimonial;

    /**
     * Create a new notification instance.
     */
    public function __construct(Testimonial $testimonial)
    {
        $this->testimonial = $testimonial;
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
        $arTitle = __('admin.notifications.new_testimonial', [], 'ar');
        $enTitle = __('admin.notifications.new_testimonial', [], 'en');

        $arBody = __('admin.notifications.new_testimonial_body', [
            'name' => $this->testimonial->name
        ], 'ar');

        $enBody = __('admin.notifications.new_testimonial_body', [
            'name' => $this->testimonial->name
        ], 'en');

        $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
        $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

        return FilamentNotification::make()
            ->title(new \Illuminate\Support\HtmlString($titleHtml))
            ->body(new \Illuminate\Support\HtmlString($bodyHtml))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->iconColor('info')
            ->actions([
                Action::make('view_ar')
                    ->label(__('admin.resources.testimonial', [], 'ar'))
                    ->url(\App\Filament\Resources\Testimonials\TestimonialResource::getUrl('edit', ['record' => $this->testimonial]))
                    ->extraAttributes(['class' => 'lang-ar'])
                    ->markAsRead(),
                Action::make('view_en')
                    ->label(__('admin.resources.testimonial', [], 'en'))
                    ->url(\App\Filament\Resources\Testimonials\TestimonialResource::getUrl('edit', ['record' => $this->testimonial]))
                    ->extraAttributes(['class' => 'lang-en'])
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
