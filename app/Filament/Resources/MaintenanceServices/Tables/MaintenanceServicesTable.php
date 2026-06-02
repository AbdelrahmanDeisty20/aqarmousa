<?php

namespace App\Filament\Resources\MaintenanceServices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaintenanceServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title_ar')
                    ->label(__('admin.fields.title_ar'))
                    ->searchable(),
                TextColumn::make('title_en')
                    ->label(__('admin.fields.title_en'))
                    ->searchable(),
                TextColumn::make('category')
                    ->label(__('admin.fields.category'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'home' => __('admin.fields.home_service'),
                        'technical' => __('admin.fields.technical_service'),
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'home' => 'success',
                        'technical' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                ImageColumn::make('image')
                    ->label(__('admin.fields.image'))
                    ->disk('public'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
