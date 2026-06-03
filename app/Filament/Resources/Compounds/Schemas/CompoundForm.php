<?php

namespace App\Filament\Resources\Compounds\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompoundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Tabs::make('Compound Tabs')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.basic_info' ?? 'Basic Info'))
                            ->schema([
                                TextInput::make('name_ar')
                                    ->label(__('admin.fields.name_ar'))
                                    ->required(),
                                TextInput::make('name_en')
                                    ->label(__('admin.fields.name_en'))
                                    ->required(),
                                Textarea::make('description_ar')
                                    ->label(__('admin.fields.description_ar'))
                                    ->rows(5),
                                Textarea::make('description_en')
                                    ->label(__('admin.fields.description_en'))
                                    ->rows(5),
                            ]),
                        \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.location' ?? 'Location'))
                            ->schema([
                                Select::make('governorate_id')
                                    ->relationship('governorate', 'name_ar')
                                    ->getOptionLabelFromRecordUsing(fn($record) => $record->{'name_' . app()->getLocale()} ?? $record->name_ar)
                                    ->label(__('admin.resources.governorate'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
