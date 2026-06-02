<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->label(__('admin.fields.name'))->searchable(),
                \Filament\Tables\Columns\TextColumn::make('email')->label(__('admin.fields.email'))->searchable(),
                \Filament\Tables\Columns\TextColumn::make('phone')->label(__('admin.fields.phone'))->searchable(),
                \Filament\Tables\Columns\TextColumn::make('unit.title_' . app()->getLocale())
                    ->label(__('admin.resources.unit'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('seller.name')
                    ->label(__('admin.resources.user')) // Using generic user label or I could use specific like 'Seller'
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label(__('admin.fields.created_at'))->dateTime()->sortable(),
                TextColumn::make('message')->label(__('admin.fields.message'))->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
