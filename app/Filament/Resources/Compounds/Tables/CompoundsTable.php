<?php

namespace App\Filament\Resources\Compounds\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompoundsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_ar')->searchable(['name_ar', 'name_en'])->label(__('admin.fields.name_ar'))->visible(fn () => app()->getLocale() === 'ar'),
                TextColumn::make('name_en')->searchable(['name_ar', 'name_en'])->label(__('admin.fields.name_en'))->visible(fn () => app()->getLocale() === 'en'),
                TextColumn::make('city.name_ar')->label(__('admin.resources.city'))->searchable(['name_ar', 'name_en'])->visible(fn () => app()->getLocale() === 'ar'),
                TextColumn::make('city.name_en')->label(__('admin.resources.city'))->searchable(['name_ar', 'name_en'])->visible(fn () => app()->getLocale() === 'en'),
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
