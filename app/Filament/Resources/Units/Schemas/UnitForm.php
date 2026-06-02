<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            \Filament\Schemas\Components\Tabs::make('Unit Tabs')
                ->tabs([
                    \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.basic_info' ?? 'Basic Info'))
                        ->schema([
                            TextInput::make('title_ar')
                                ->label(__('admin.fields.title_ar'))
                                ->required(),

                            TextInput::make('title_en')
                                ->label(__('admin.fields.title_en'))
                                ->nullable(),

                            Textarea::make('description_ar')
                                ->label(__('admin.fields.description_ar'))
                                ->required()
                                ->rows(5),

                            Textarea::make('description_en')
                                ->label(__('admin.fields.description_en'))
                                ->nullable()
                                ->rows(5),

                            Select::make('status')
                                ->label(__('admin.fields.status'))
                                ->options([
                                    // 'pending' => __('admin.fields.statuses.pending'),
                                    'approved' => __('admin.fields.statuses.approved'),
                                    // 'rejected' => __('admin.fields.statuses.rejected'),
                                    'sold' => __('admin.fields.statuses.sold'),
                                    'rented' => __('admin.fields.statuses.rented'),
                                ])
                                ->default('approved')
                                ->required(),

                            Select::make('development_status')
                                ->label(__('admin.fields.development_status'))
                                ->options([
                                    // 'under_construction' => __('admin.fields.development_statuses.under_construction'),
                                    // 'ready' => __('admin.fields.development_statuses.ready'),
                                    // 'handover_soon' => __('admin.fields.development_statuses.handover_soon'),
                                    'primary' => __('admin.fields.development_statuses.primary'),
                                    'resale' => __('admin.fields.development_statuses.resale'),
                                ])
                                ->default('primary')
                                ->visible(fn(Get $get) => $get('offer_type') === 'sale')
                                ->required(fn(Get $get) => $get('offer_type') === 'sale')
                                ->nullable(),

                            \Filament\Forms\Components\Toggle::make('is_visible')
                                ->label(__('admin.fields.is_visible'))
                                ->default(true)
                                ->required(),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.details' ?? 'Details'))
                        ->schema([
                            \Filament\Schemas\Components\Grid::make(3)
                                ->schema([
                                    TextInput::make('price')
                                        ->label(__('admin.fields.price'))
                                        ->numeric()
                                        ->prefix('EGP')
                                        ->required(),

                                    Placeholder::make('sold_at_display')
                                        ->label(__('admin.fields.sold_at'))
                                        ->content(fn($record) => $record?->sold_at?->format('Y-m-d H:i') ?? '-')
                                        ->visible(
                                            fn(Get $get, $record) =>
                                            $get('status') === 'sold' && $record?->sold_at
                                        ),

                                    Placeholder::make('rented_at_display')
                                        ->label(__('admin.fields.rented_at'))
                                        ->content(fn($record) => $record?->rented_at?->format('Y-m-d H:i') ?? '-')
                                        ->visible(
                                            fn(Get $get, $record) =>
                                            $get('status') === 'rented' && $record?->rented_at
                                        ),

                                    TextInput::make('price_per_m2')
                                        ->label(__('admin.fields.price_per_m2'))
                                        ->numeric()
                                        ->nullable(),

                                    Select::make('offer_type')
                                        ->label(__('admin.fields.offer_type'))
                                        ->options([
                                            'sale' => __('admin.fields.offer_types.sale'),
                                            'rent' => __('admin.fields.offer_types.rent'),
                                        ])
                                        ->live()
                                        ->required(),

                                    TextInput::make('area')
                                        ->label(__('admin.fields.area'))
                                        ->numeric()
                                        ->suffix('m²')
                                        ->required(),

                                    TextInput::make('land_area')
                                        ->label(__('admin.fields.land_area'))
                                        ->numeric()
                                        ->nullable(),

                                    TextInput::make('internal_area')
                                        ->label(__('admin.fields.internal_area'))
                                        ->numeric()
                                        ->nullable(),
                                ]),

                            \Filament\Schemas\Components\Section::make(__('admin.fields.features' ?? 'Features'))
                                ->schema([
                                    \Filament\Schemas\Components\Grid::make(4)
                                        ->schema([
                                            TextInput::make('rooms')
                                                ->label(__('admin.fields.rooms'))
                                                ->numeric()
                                                ->nullable(),

                                            TextInput::make('bathrooms')
                                                ->label(__('admin.fields.bathrooms'))
                                                ->numeric()
                                                ->nullable(),

                                            TextInput::make('garages')
                                                ->label(__('admin.fields.garages'))
                                                ->numeric()
                                                ->nullable(),

                                            TextInput::make('build_year')
                                                ->label(__('admin.fields.build_year'))
                                                ->numeric()
                                                ->minValue(1900)
                                                ->maxValue(date('Y') + 5)
                                                ->nullable(),
                                        ]),
                                    \Filament\Forms\Components\CheckboxList::make('amenities')
                                        ->label(__('admin.resources.amenities'))
                                        ->relationship('amenities', 'name_' . app()->getLocale())
                                        ->columns(2)
                                        // ->grid(2)
                                        ->bulkToggleable()
                                        ->columnSpanFull(),
                                ])->compact(),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.relations' ?? 'Relations'))
                        ->schema([
                            \Filament\Schemas\Components\Grid::make(2)
                                ->schema([
                                    Select::make('city_id')
                                        ->label(__('admin.resources.city'))
                                        ->relationship('city', 'name_' . app()->getLocale())
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    Select::make('unit_type_id')
                                        ->label(__('admin.resources.unit_type'))
                                        ->relationship('type', 'name_' . app()->getLocale())
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    Select::make('compound_id')
                                        ->label(__('admin.resources.compound'))
                                        ->relationship('compound', 'name_' . app()->getLocale())
                                        ->searchable()
                                        ->preload()
                                        ->nullable(),

                                    Select::make('owner_id')
                                        ->label(__('admin.fields.user'))
                                        ->relationship('owner', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    Select::make('developer_id')
                                        ->label(__('admin.resources.developer'))
                                        ->relationship('developer', 'name_' . app()->getLocale())
                                        ->searchable()
                                        ->preload()
                                        ->nullable(),
                                ]),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.location' ?? 'Location'))
                        ->schema([
                            \Filament\Schemas\Components\Grid::make(2)
                                ->schema([
                                    TextInput::make('latitude')
                                        ->label(__('admin.fields.latitude'))
                                        ->numeric()
                                        ->minValue(-90)
                                        ->maxValue(90)
                                        ->helperText('الرقم الأول في خرائط جوجل (قبل الفاصلة)')
                                        ->nullable(),

                                    TextInput::make('longitude')
                                        ->label(__('admin.fields.longitude'))
                                        ->numeric()
                                        ->minValue(-180)
                                        ->maxValue(180)
                                        ->helperText('الرقم الثاني في خرائط جوجل (بعد الفاصلة)')
                                        ->nullable(),
                                    TextInput::make('address_ar')
                                        ->label(__('admin.fields.address_ar'))
                                        ->columnSpanFull()
                                        ->required()
                                        ->maxLength(500),
                                    TextInput::make('address_en')
                                        ->label(__('admin.fields.address_en'))
                                        ->columnSpanFull()
                                        ->nullable()
                                        ->maxLength(500),
                                ]),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.media' ?? 'Media'))
                        ->schema([
                            Repeater::make('media')
                                ->label(__('admin.fields.media'))
                                ->relationship('media')
                                ->schema([
                                    Placeholder::make('video_preview')
                                        ->label(__('admin.fields.media'))
                                        ->content(fn($get) => $get('type') === 'video' && $get('url')
                                            ? new HtmlString('<video controls width="100%" src="' . \Illuminate\Support\Facades\Storage::disk('public')->url($get('url')) . '"></video>')
                                            : null)
                                        ->hidden(fn($get) => $get('type') !== 'video' || !$get('url'))
                                        ->columnSpanFull(),
                                    FileUpload::make('url')
                                        ->label(fn($get) => match ($get('type')) {
                                            'video' => __('admin.fields.media_types.video'),
                                            'image' => __('admin.fields.media_types.image'),
                                            default => __('admin.fields.file'),
                                        })
                                        ->helperText(fn($get) => match ($get('type')) {
                                            'video' => __('admin.fields.allowed_formats', ['formats' => 'mp4, mov, avi, webm']),
                                            'image' => __('admin.fields.allowed_formats', ['formats' => 'jpg, png, jpeg']),
                                            // '3d' => __('admin.fields.allowed_formats', ['formats' => 'obj, fbx, glb, gltf']),
                                            'floorplan' => __('admin.fields.allowed_formats', ['formats' => 'jpg, png, jpeg']),
                                            default => __('admin.fields.keep_current'),
                                        })
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'video/*', 'application/octet-stream'])
                                        ->disk('public')
                                        ->visibility('public')
                                        ->directory('units/media')
                                        ->downloadable()
                                        ->openable()
                                        ->required(fn($context) => $context === 'create')
                                        ->live(),
                                    Select::make('type')
                                        ->label(__('admin.fields.type'))
                                        ->options([
                                            'image' => __('admin.fields.media_types.image'),
                                            'video' => __('admin.fields.media_types.video'),
                                            // '3d' => __('admin.fields.media_types.3d'),
                                            'floorplan' => __('admin.fields.media_types.floorplan'),
                                        ])
                                        ->default('image')
                                        ->required()
                                        ->live(),
                                ])
                                ->columns(2)
                                ->grid(1)
                                ->collapsible(),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
