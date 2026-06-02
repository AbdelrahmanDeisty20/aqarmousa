<?php

namespace App\Filament\Resources\Favorites\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class FavoriteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label(__('admin.resources.user'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('unit_id')
                    ->relationship('unit', 'title_ar')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->{'title_' . app()->getLocale()} ?? $record->title_ar)
                    ->label(__('admin.resources.unit'))
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
