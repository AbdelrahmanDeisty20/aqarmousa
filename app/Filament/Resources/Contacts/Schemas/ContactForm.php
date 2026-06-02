<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')->label(__('admin.fields.name'))->required()->disabled(),
                TextInput::make('email')->email()->label(__('admin.fields.email'))->required()->disabled(),
                TextInput::make('phone')->label(__('admin.fields.phone'))->disabled(),
                TextInput::make('address')->label(__('admin.fields.address'))->disabled(),
                \Filament\Forms\Components\Placeholder::make('unit_link')
                    ->label(__('admin.resources.unit'))
                    ->content(fn($record) => $record?->unit ? new \Illuminate\Support\HtmlString('<a href="' . \App\Filament\Resources\Units\UnitResource::getUrl('edit', ['record' => $record->unit_id]) . '" class="text-primary-600 hover:underline font-bold">' . ($record->unit->{'title_' . app()->getLocale()} ?? $record->unit->title_ar) . '</a>') : '-')
                    ->visible(fn($record) => $record?->unit_id !== null),
                \Filament\Forms\Components\Select::make('unit_id')
                    ->label(__('admin.resources.unit'))
                    ->relationship('unit', 'title_' . app()->getLocale())
                    ->hidden(fn($record) => $record?->unit_id !== null)
                    ->disabled(),
                \Filament\Forms\Components\Placeholder::make('seller_link')
                    ->label(__('admin.resources.user'))
                    ->content(fn($record) => $record?->seller ? new \Illuminate\Support\HtmlString('<a href="' . \App\Filament\Resources\Users\UserResource::getUrl('edit', ['record' => $record->seller_id]) . '" class="text-primary-600 hover:underline font-bold">' . $record->seller->name . '</a>') : '-')
                    ->visible(fn($record) => $record?->seller_id !== null),
                \Filament\Forms\Components\Select::make('seller_id')
                    ->label(__('admin.resources.user'))
                    ->relationship('seller', 'name')
                    ->hidden(fn($record) => $record?->seller_id !== null)
                    ->disabled(),
                Textarea::make('message')->label(__('admin.fields.message'))->required()->disabled(),
            ]);
    }
}
