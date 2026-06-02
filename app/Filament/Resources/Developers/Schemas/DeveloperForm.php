<?php

namespace App\Filament\Resources\Developers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class DeveloperForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Tabs::make('Developer Tabs')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.basic_info' ?? 'Basic Info'))
                            ->schema([
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        TextInput::make('name_ar')
                                            ->label(__('admin.fields.name_ar'))
                                            ->required(),

                                        TextInput::make('name_en')
                                            ->label(__('admin.fields.name_en'))
                                            ->required(),

                                        TextInput::make('email')
                                            ->label(__('admin.fields.email'))
                                            ->email()
                                            ->unique(ignoreRecord: true),

                                        TextInput::make('phone')
                                            ->label(__('admin.fields.phone'))
                                            ->tel(),

                                        \Filament\Forms\Components\Toggle::make('status')
                                            ->label(__('admin.fields.status'))
                                            ->formatStateUsing(fn($record) => $record?->status === 'active')
                                            ->dehydrateStateUsing(fn($state) => $state ? 'active' : 'inactive')
                                            ->default(true)
                                            ->required(),
                                    ]),

                                TextInput::make('address')
                                    ->label(__('admin.fields.address'))
                                    ->columnSpanFull(),
                            ]),
                        \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.logo' ?? 'Logo'))
                            ->schema([
                                FileUpload::make('logo')
                                    ->label(__('admin.fields.logo'))
                                    ->helperText('يرجى استخدام صيغ الصور المدعومة: JPG, PNG, JPEG')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('developers')
                                    ->downloadable()
                                    ->openable()
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
