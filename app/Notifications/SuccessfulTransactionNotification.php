<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use Filament\Notifications\Notification as FilamentNotification;
use Filament\Actions\Action;

class SuccessfulTransactionNotification extends Notification
{
    use Queueable;

    public $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
        $arTitle = __('admin.notifications.successful_transaction', [], 'ar');
        $enTitle = __('admin.notifications.successful_transaction', [], 'en');

        $arBody = __('admin.notifications.successful_transaction_body', [
            'amount' => number_format($this->transaction->amount, 2),
            'unit_id' => $this->transaction->unit_id
        ], 'ar');

        $enBody = __('admin.notifications.successful_transaction_body', [
            'amount' => number_format($this->transaction->amount, 2),
            'unit_id' => $this->transaction->unit_id
        ], 'en');

        $titleHtml = "<span class='lang-ar'>$arTitle</span><span class='lang-en'>$enTitle</span>";
        $bodyHtml = "<span class='lang-ar'>$arBody</span><span class='lang-en'>$enBody</span>";

        return FilamentNotification::make()
            ->title(new \Illuminate\Support\HtmlString($titleHtml))
            ->body(new \Illuminate\Support\HtmlString($bodyHtml))
            ->icon('heroicon-o-currency-dollar')
            ->iconColor('success')
            ->actions([
                Action::make('view_ar')
                    ->label(__('admin.resources.transaction', [], 'ar'))
                    ->url(\App\Filament\Resources\Transactions\TransactionResource::getUrl('edit', ['record' => $this->transaction]))
                    ->extraAttributes(['class' => 'lang-ar'])
                    ->markAsRead(),
                Action::make('view_en')
                    ->label(__('admin.resources.transaction', [], 'en'))
                    ->url(\App\Filament\Resources\Transactions\TransactionResource::getUrl('edit', ['record' => $this->transaction]))
                    ->extraAttributes(['class' => 'lang-en'])
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
