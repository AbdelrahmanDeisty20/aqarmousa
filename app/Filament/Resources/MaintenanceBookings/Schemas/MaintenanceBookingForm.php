<?php

namespace App\Filament\Resources\MaintenanceBookings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action as ActionsAction;

class MaintenanceBookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('maintenance_service_id')
                    ->label(__('admin.fields.service'))
                    ->relationship('service', 'title_ar')
                    ->disabledOn('edit')
                    ->dehydrated()
                    ->required(),
                Select::make('user_id')
                    ->label(__('admin.fields.user'))
                    ->relationship('user', 'name')
                    ->disabledOn('edit')
                    ->dehydrated()
                    ->suffixAction(
                        fn ($record) => $record?->user_id ? ActionsAction::make('view_user')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->url(UserResource::getUrl('edit', ['record' => $record->user_id]))
                            ->openUrlInNewTab() : null
                    ),
                TextInput::make('phone')
                    ->label(__('admin.fields.phone'))
                    ->tel()
                    ->disabledOn('edit')
                    ->dehydrated()
                    ->required(),
                TextInput::make('address')
                    ->label(__('admin.fields.address'))
                    ->disabledOn('edit')
                    ->dehydrated()
                    ->required(),
                Textarea::make('message')
                    ->label(__('admin.fields.message'))
                    ->disabledOn('edit')
                    ->dehydrated()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('admin.fields.status'))
                    ->options([
                        'pending' => __('admin.fields.pending'),
                        'contacted' => __('admin.fields.contacted'),
                        'done' => __('admin.fields.done'),
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}
