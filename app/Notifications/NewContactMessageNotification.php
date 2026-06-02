<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use Filament\Notifications\Notification as FilamentNotification;
use Filament\Actions\Action;

class NewContactMessageNotification extends Notification
{
    use Queueable;

    public $contact;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
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
        $arTitle = __('admin.notifications.new_contact_message', [], 'ar');
        $enTitle = __('admin.notifications.new_contact_message', [], 'en');

        $arBody = __('admin.notifications.new_contact_body', [
            'name' => $this->contact->name,
            'subject' => $this->contact->subject
        ], 'ar');

        $enBody = __('admin.notifications.new_contact_body', [
            'name' => $this->contact->name,
            'subject' => $this->contact->subject
        ], 'en');

        $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
        $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

        return FilamentNotification::make()
            ->title(new \Illuminate\Support\HtmlString($titleHtml))
            ->body(new \Illuminate\Support\HtmlString($bodyHtml))
            ->icon('heroicon-o-envelope')
            ->iconColor('info')
            ->actions([
                Action::make('view_ar')
                    ->label(__('admin.resources.contact', [], 'ar'))
                    ->url(\App\Filament\Resources\Contacts\ContactResource::getUrl('edit', ['record' => $this->contact]))
                    ->extraAttributes(['class' => 'lang-ar'])
                    ->markAsRead(),
                Action::make('view_en')
                    ->label(__('admin.resources.contact', [], 'en'))
                    ->url(\App\Filament\Resources\Contacts\ContactResource::getUrl('edit', ['record' => $this->contact]))
                    ->extraAttributes(['class' => 'lang-en'])
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
