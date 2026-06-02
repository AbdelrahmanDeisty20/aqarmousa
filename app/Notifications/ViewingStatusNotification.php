<?php

namespace App\Notifications;

use App\Models\Viewing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ViewingStatusNotification extends Notification
{
    use Queueable;

    protected $viewing;
    protected $type; // 'accepted', 'reschedule_admin', 'user_response', 'cancelled'
    protected $channels;

    /**
     * Create a new notification instance.
     */
    public function __construct(Viewing $viewing, string $type, array $channels = null)
    {
        $this->viewing = $viewing;
        $this->type = $type;
        $this->channels = $channels;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($this->channels) {
            return $this->channels;
        }

        // Send to both database and email for users, only database for admins
        if ($this->type === 'user_response' || $this->type === 'cancelled') {
            return ['database']; // Admin notifications
        }

        return ['database', 'mail']; // User notifications (accepted, reschedule_admin)
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $unitTitle = $this->viewing->unit?->title ?? __('notifications.unit_number', ['id' => $this->viewing->unit_id]);

        $mailMessage = (new MailMessage)
            ->subject($this->getEmailSubject())
            ->greeting(__('notifications.greeting', ['name' => $this->viewing->name]));

        if ($this->type === 'accepted') {
            $mailMessage
                ->line(__('notifications.viewing_accepted', ['unit' => $unitTitle]))
                ->line("**" . __('notifications.viewing_details') . ":**")
                ->line("📅 " . __('notifications.date') . ": {$this->viewing->date}")
                ->line("🕐 " . __('notifications.time') . ": {$this->viewing->time}")
                ->line("📍 " . __('notifications.address') . ": " . ($this->viewing->unit?->address ?? __('notifications.address_coming_soon')))
                ->line(__('notifications.please_attend'))
                ->action(__('notifications.view_details'), config('app.frontend_url') . '/profile/user-booking');
        } elseif ($this->type === 'reschedule_admin') {
            $mailMessage
                ->line(__('notifications.new_time_proposed', ['unit' => $unitTitle]))
                ->line("**" . __('notifications.proposed_time') . ":**")
                ->line("📅 " . __('notifications.date') . ": {$this->viewing->date}")
                ->line("🕐 " . __('notifications.time') . ": {$this->viewing->time}")
                ->line(__('notifications.review_and_approve'))
                ->action(__('notifications.approve_time'), config('app.frontend_url') . '/profile/user-booking')
                ->line(__('notifications.suggest_another_time'));
        } elseif ($this->type === 'cancelled') {
            $mailMessage
                ->line(__('notifications.viewing_cancelled', ['unit' => $unitTitle]))
                ->action(__('notifications.view_details'), config('app.frontend_url') . '/profile/user-booking');
        }

        return $mailMessage
            ->line(__('notifications.thank_you'))
            ->salutation(__('notifications.regards'));
    }

    /**
     * Get email subject based on type
     */
    protected function getEmailSubject(): string
    {
        return match ($this->type) {
            'accepted' => '✅ ' . __('notifications.viewing_request_accepted'),
            'cancelled' => '❌ ' . __('notifications.viewing_request_cancelled'),
            'reschedule_admin' => '📅 ' . __('notifications.new_time_for_viewing'),
            default => __('notifications.viewing_update'),
        };
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $messages = [
            'accepted' => __('notifications.viewing_accepted_msg', [
                'unit_id' => $this->viewing->unit_id,
                'date' => $this->viewing->date,
                'time' => $this->viewing->time
            ]),
            'rejected' => __('notifications.viewing_rejected_msg', [
                'unit_id' => $this->viewing->unit_id
            ]),
            'reschedule_admin' => __('notifications.new_time_proposed_msg', [
                'unit_id' => $this->viewing->unit_id,
                'date' => $this->viewing->date,
                'time' => $this->viewing->time
            ]),
            'user_response' => __('notifications.user_modified_time', [
                'name' => $this->viewing->name,
                'unit_id' => $this->viewing->unit_id
            ]),
            'cancelled' => __('notifications.user_cancelled_viewing', [
                'name' => $this->viewing->name,
                'unit_id' => $this->viewing->unit_id
            ]),
        ];

        return [
            'title' => $this->type === 'user_response'
                ? __('notifications.user_response')
                : __('notifications.viewing_update_title'),
            'body' => $messages[$this->type] ?? __('notifications.viewing_update'),
            'viewing_id' => $this->viewing->id,
            'unit_id' => $this->viewing->unit_id,
            'date' => $this->viewing->date,
            'time' => $this->viewing->time,
            'user_message' => $this->viewing->user_message,
        ];
    }
}