<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title_ar')
                    ->label(__('admin.fields.title_ar'))
                    ->searchable(['title_ar', 'title_en'])
                    ->visible(fn () => app()->getLocale() === 'ar'),
                TextColumn::make('title_en')
                    ->label(__('admin.fields.title_en'))
                    ->searchable(['title_ar', 'title_en'])
                    ->visible(fn () => app()->getLocale() === 'en'),
                TextColumn::make('created_at')->label(__('admin.fields.created_at'))->date(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
