<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnitSoldNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $unit;

    /**
     * Create a new notification instance.
     */
    public function __construct($unit)
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $title = $this->unit->title_ar ?: $this->unit->title_en ?: 'Unit';

        return (new MailMessage)
            ->subject(__('api.notifications.unit_sold_subject'))
            ->greeting(__('api.notifications.greeting', ['name' => $notifiable->name]))
            ->line(__('api.notifications.unit_sold_body', ['title' => $title]))
            ->action(__('api.notifications.view_unit'), config('app.frontend_url') . '/property-details/' . $this->unit->id)
            ->line(__('api.notifications.thank_you'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
