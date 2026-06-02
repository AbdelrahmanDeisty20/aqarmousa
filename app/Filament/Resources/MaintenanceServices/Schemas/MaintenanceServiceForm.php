<?php

namespace App\Filament\Resources\MaintenanceServices\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaintenanceServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title_ar')
                    ->label(__('admin.fields.title_ar'))
                    ->required(),
                TextInput::make('title_en')
                    ->label(__('admin.fields.title_en')),
                \Filament\Forms\Components\Select::make('category')
                    ->label(__('admin.fields.category'))
                    ->options([
                        'home' => __('admin.fields.home_service'),
                        'technical' => __('admin.fields.technical_service'),
                    ])
                    ->required(),
                FileUpload::make('image')
                    ->label(__('admin.fields.image'))
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->helperText(__('admin.fields.allowed_formats', ['formats' => 'jpg, png, jpeg']))
                    ->directory('maintenance-services')
                    ->disk('public')
                    ->required(),
            ]);
    }
}
