<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestContacts extends TableWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function getTableHeading(): string
    {
        return __('admin.navigation_groups.users_interaction') . ' - ' . __('admin.resources.contacts');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Contact::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.fields.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('admin.fields.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('admin.fields.phone')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.fields.created_at'))
                    ->dateTime()
            ])
            ->recordUrl(fn (Contact $record): string => \App\Filament\Resources\Contacts\ContactResource::getUrl('edit', ['record' => $record]));
    }
}
