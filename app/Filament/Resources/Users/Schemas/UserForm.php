<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label(__('admin.fields.name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('admin.fields.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('phone')
                    ->label(__('admin.fields.phone'))
                    ->tel()
                    ->regex('/^01[0125][0-9]{8}$/')
                    ->validationMessages([
                        'regex' => __('validation.custom.phone.regex'),
                    ]),
                TextInput::make('address')
                    ->label(__('admin.fields.address'))
                    ->required(fn(Get $get) => $get('role') === 'seller')
                    ->maxLength(500),
                FileUpload::make('avatar')
                    ->label(__('admin.fields.avatar'))
                    ->helperText(__('admin.fields.allowed_formats', ['formats' => 'jpg, png, jpeg']))
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png','image/jpg'])
                    ->directory('avatars')
                    ->disk('public')
                    ->visibility('public')
                    ->downloadable()
                    ->openable()
                    ->nullable(),
                FileUpload::make('id_photo')
                    ->label(__('admin.fields.id_photo'))
                    ->helperText(__('admin.fields.allowed_formats', ['formats' => 'jpg, png, jpeg']))
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png','image/jpg'])
                    ->directory('id_photos')
                    ->disk('public')
                    ->visibility('public')
                    ->downloadable()
                    ->openable()
                    ->nullable()
                    ->visible(fn(Get $get) => $get('role') === 'seller'),
                Select::make('city_id')
                    ->label(__('admin.fields.city'))
                    ->relationship('city', 'name_ar')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('password')
                    ->label(__('admin.fields.password'))
                    ->helperText(__('admin.fields.keep_current_password'))
                    ->password()
                    ->rule(\Illuminate\Validation\Rules\Password::min(8)->letters()->numbers())
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create'),
                Select::make('roles')
                    ->label(__('admin.resources.roles'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Select::make('role')
                    ->label(__('admin.fields.role'))
                    ->options([
                        'admin' => __('admin.fields.roles.admin'),
                        'buyer' => __('admin.fields.roles.buyer'),
                    ])
                    ->required()
                    ->live(),
                Select::make('status')
                    ->label(__('admin.fields.status'))
                    ->options([
                        // 'pending' => __('admin.fields.statuses.pending'),
                        'approved' => __('admin.fields.statuses.approved'),
                        // 'rejected' => __('admin.fields.statuses.rejected'),
                    ])->required(),
                \Filament\Forms\Components\Toggle::make('email_verified')
                    ->label(__('admin.fields.email_verified'))
                    ->helperText(__('admin.fields.email_verified_helper'))
                    ->afterStateHydrated(function (\Filament\Forms\Components\Toggle $component, $state, $record) {
                        if ($record) {
                            $component->state($record->email_verified_at !== null);
                        }
                    })
                    ->live(),
            ]);
    }
}