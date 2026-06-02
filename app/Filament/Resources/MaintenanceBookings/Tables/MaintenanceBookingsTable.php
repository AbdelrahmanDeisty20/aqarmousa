<?php

namespace App\Filament\Resources\MaintenanceBookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaintenanceBookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.title_ar')
                    ->label(__('admin.fields.service'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label(__('admin.fields.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('admin.fields.phone'))
                    ->searchable(),
                TextColumn::make('address')
                    ->label(__('admin.fields.address'))
                    ->searchable()
                    ->limit(30),
                TextColumn::make('status')
                    ->label(__('admin.fields.status'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('admin.fields.pending'),
                        'contacted' => __('admin.fields.contacted'),
                        'done' => __('admin.fields.done'),
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'contacted' => 'info',
                        'done' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
