<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Forms\Components\Placeholder::make('user_link')
                    ->label(__('admin.resources.user'))
                    ->content(fn($record) => $record?->user ? new \Illuminate\Support\HtmlString('<a href="' . \App\Filament\Resources\Users\UserResource::getUrl('edit', ['record' => $record->user_id]) . '" class="text-primary-600 hover:underline font-bold">' . $record->user->name . '</a>') : '-'),
                Select::make('user_id')
                    ->label(__('admin.resources.user'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->hidden(fn($record) => $record !== null)
                    ->required()
                    ->disabled(),
                \Filament\Forms\Components\Placeholder::make('user_email')
                    ->label(__('admin.fields.email'))
                    ->content(fn($record) => $record?->user?->email ?? '-'),
                \Filament\Forms\Components\Placeholder::make('user_phone')
                    ->label(__('admin.fields.phone'))
                    ->content(fn($record) => $record?->user?->phone ?? '-'),
                \Filament\Forms\Components\Placeholder::make('unit_link')
                    ->label(__('admin.resources.unit'))
                    ->content(fn($record) => $record?->unit ? new \Illuminate\Support\HtmlString('<a href="' . \App\Filament\Resources\Units\UnitResource::getUrl('edit', ['record' => $record->unit_id]) . '" class="text-primary-600 hover:underline font-bold">' . ($record->unit->{'title_' . app()->getLocale()} ?? $record->unit->title_ar) . '</a>') : '-'),
                Select::make('unit_id')
                    ->relationship('unit', 'title_ar')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->{'title_' . app()->getLocale()} ?? $record->title_ar)
                    ->label(__('admin.resources.unit'))
                    ->hidden(fn($record) => $record !== null)
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabled(),
                Select::make('rating')
                    ->label(__('admin.fields.rating'))
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5'
                    ])
                    ->required()
                    ->disabled(),

                Textarea::make('comment')
                    ->label(__('admin.fields.comment'))
                    ->disabled(),

            ]);
    }
}
