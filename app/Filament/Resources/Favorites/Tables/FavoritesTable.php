<?php

namespace App\Filament\Resources\Favorites\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FavoritesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label(__('admin.resources.user')),
                TextColumn::make('unit.title_ar')->label(__('admin.resources.unit'))->visible(fn () => app()->getLocale() === 'ar'),
                TextColumn::make('unit.title_en')->label(__('admin.resources.unit'))->visible(fn () => app()->getLocale() === 'en'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
