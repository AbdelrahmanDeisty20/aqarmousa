<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
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
                TextColumn::make('slug')->label(__('admin.fields.link')),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Model $record) => in_array($record->slug, [
                        'about-us',
                        // 'contact-us',
                        // 'privacy-policy',
                        'terms-and-conditions',
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function (Model $record) {
                                // Only delete if NOT one of the protected slugs
                                if (!in_array($record->slug, [
                                    'about-us',
                                    // 'contact-us',
                                    // 'privacy-policy',
                                    'terms-and-conditions',
                                ])) {
                                    $record->delete();
                                }
                            });
                        }),
                ]),
            ]);
    }
}
