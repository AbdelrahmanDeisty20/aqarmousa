<?php

namespace App\Filament\Resources\UnitMedia\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UnitMediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('unit.title_ar')->label(__('admin.resources.unit')),
                TextColumn::make('type')->label(__('admin.fields.type')),
                TextColumn::make('url')->label(__('admin.fields.link') . ' / ' . __('admin.fields.file')),
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
