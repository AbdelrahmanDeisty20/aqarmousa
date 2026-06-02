<?php

namespace App\Filament\Resources\Cities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_ar')->label(__('admin.fields.name_ar'))->searchable(['name_ar', 'name_en'])->visible(fn () => app()->getLocale() === 'ar'),
                TextColumn::make('name_en')->label(__('admin.fields.name_en'))->searchable(['name_ar', 'name_en'])->visible(fn () => app()->getLocale() === 'en'),
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
