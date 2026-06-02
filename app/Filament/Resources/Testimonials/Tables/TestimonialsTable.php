<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('admin.fields.image'))
                    ->disk('public')
                    ->circular()
                    ->state(fn ($record) => $record->user?->avatar ?? $record->image),

                TextColumn::make('name')
                    ->label(__('admin.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => $record->user_id ? \App\Filament\Resources\Users\UserResource::getUrl('edit', ['record' => $record->user_id]) : null),

                TextColumn::make('position')
                    ->label(__('admin.fields.position')),

                TextColumn::make('content')
                    ->label(__('admin.fields.comment'))
                    ->limit(50),

                ToggleColumn::make('status')
                    ->label(__('admin.fields.status')),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()->label(__('admin.actions.view')),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
