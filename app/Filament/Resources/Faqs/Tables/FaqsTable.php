<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question_ar')
                    ->label(__('admin.fields.question_ar'))
                    ->visible(fn () => app()->getLocale() === 'ar'),
                TextColumn::make('answer_ar')
                    ->label(__('admin.fields.answer_ar'))
                    ->limit(50)
                    ->visible(fn () => app()->getLocale() === 'ar'),

                TextColumn::make('question_en')
                    ->label(__('admin.fields.question_en'))
                    ->visible(fn () => app()->getLocale() === 'en'),
                TextColumn::make('answer_en')
                    ->label(__('admin.fields.answer_en'))
                    ->limit(50)
                    ->visible(fn () => app()->getLocale() === 'en'),
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
