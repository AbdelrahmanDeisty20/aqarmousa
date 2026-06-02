<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label(__('admin.resources.user')),
                TextColumn::make('unit.title_ar')->label(__('admin.resources.unit'))->visible(fn() => app()->getLocale() === 'ar'),
                TextColumn::make('unit.title_en')->label(__('admin.resources.unit'))->visible(fn() => app()->getLocale() === 'en'),
                TextColumn::make('rating')->label(__('admin.fields.rating')),
                TextColumn::make('comment')->label(__('admin.fields.comment')),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('rating_filter')
                    ->label(__('admin.fields.rating'))
                    ->options([
                        '1' => '⭐ 1',
                        '2' => '⭐ 2',
                        '3' => '⭐ 3',
                        '4' => '⭐ 4',
                        '5' => '⭐ 5',
                        'bad' => __('admin.widgets.stats_overview.bad_reviews'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if ($data['value'] === 'bad') {
                            $query->where('rating', '<', 3);
                        } elseif ($data['value']) {
                            $query->where('rating', $data['value']);
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
