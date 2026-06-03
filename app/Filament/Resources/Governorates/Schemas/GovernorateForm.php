<?php

namespace App\Filament\Resources\Governorates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GovernorateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name_ar')
                    ->label(__('admin.fields.name_ar'))
                    ->required(),
                TextInput::make('name_en')
                    ->label(__('admin.fields.name_en')),
                    // ->required(),
            ]);
    }
}
