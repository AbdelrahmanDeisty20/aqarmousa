<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label(__('admin.resources.user')),
                TextColumn::make('unit.title_ar')->label(__('admin.resources.unit'))->visible(fn() => app()->getLocale() === 'ar'),
                TextColumn::make('unit.title_en')->label(__('admin.resources.unit'))->visible(fn() => app()->getLocale() === 'en'),
                TextColumn::make('amount')->label(__('admin.fields.amount'))->money('EGP'),
                TextColumn::make('payment_status')->label(__('admin.fields.payment_status'))
                    ->badge()
                    ->colors([
                        'success' => 'paid',
                        'danger' => 'failed',
                        'warning' => 'pending',
                    ])
                    ->formatStateUsing(fn($state) => __("admin.fields.statuses.{$state}")),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('payment_status_filter')
                    ->label(__('admin.fields.payment_status'))
                    ->options([
                        'pending' => __('admin.fields.statuses.pending'),
                        'paid' => __('admin.fields.statuses.paid'),
                        'failed' => __('admin.fields.statuses.failed'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('payment_status', $data['value']);
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
