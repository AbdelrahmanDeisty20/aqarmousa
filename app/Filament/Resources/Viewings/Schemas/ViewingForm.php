<?php

namespace App\Filament\Resources\Viewings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\Units\UnitResource;
use Filament\Actions\Action as ActionsAction;

use App\Filament\Resources\Users\UserResource;

class ViewingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label(__('viewing.fields.user'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit')
                    ->suffixAction(
                        ActionsAction::make('view_user')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->tooltip(__('viewing.fields.user'))
                            ->url(fn($record) => $record?->user_id ? UserResource::getUrl('edit', ['record' => $record->user_id]) : null)
                            ->openUrlInNewTab()
                            ->visible(fn($record) => $record?->user_id !== null)
                    ),
                Select::make('unit_id')
                    ->label(__('viewing.fields.unit'))
                    ->relationship('unit')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->title)
                    ->searchable(['title_ar', 'title_en'])
                    ->preload()
                    ->required()
                    ->disabledOn('edit')
                    ->suffixAction(
                        ActionsAction::make('view_unit')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->tooltip(__('viewing.fields.unit'))
                            ->url(fn($record) => $record?->unit_id ? UnitResource::getUrl('edit', ['record' => $record->unit_id]) : null)
                            ->openUrlInNewTab()
                            ->visible(fn($record) => $record?->unit_id !== null)
                    ),
                TextInput::make('name')
                    ->label(__('viewing.fields.client_name'))
                    ->required()
                    ->disabledOn('edit'),
                TextInput::make('email')
                    ->label(__('viewing.fields.email'))
                    ->email()
                    ->required()
                    ->disabledOn('edit'),
                TextInput::make('phone')
                    ->label(__('viewing.fields.phone'))
                    ->tel()
                    ->required()
                    ->disabledOn('edit'),
                DatePicker::make('date')
                    ->label(__('viewing.fields.date'))
                    ->required(),
                TimePicker::make('time')
                    ->label(__('viewing.fields.time'))
                    ->required(),
                Select::make('status')
                    ->label(__('viewing.fields.status'))
                    ->required()
                    ->options([
                        'pending' => __('viewing.statuses.pending'),
                        'accepted' => __('viewing.statuses.accepted'),
                        'reschedule_admin' => __('viewing.statuses.reschedule_admin'),
                        'cancelled' => __('viewing.statuses.cancelled'),
                    ])
                    ->default('pending'),
                Textarea::make('notes')
                    ->label(__('viewing.fields.notes'))
                    ->columnSpanFull()
                    ->disabledOn('edit'),
                Textarea::make('user_message')
                    ->label(__('viewing.fields.user_message'))
                    ->helperText(__('viewing.fields.user_message_helper'))
                    ->columnSpanFull()
                    ->disabledOn('edit'),
            ]);
    }
}
