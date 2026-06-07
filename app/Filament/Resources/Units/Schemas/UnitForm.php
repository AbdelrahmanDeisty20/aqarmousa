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
                                    'available' => __('admin.fields.statuses.available'),
                                    'sold' => __('admin.fields.statuses.sold'),
                                    'reserved' => __('admin.fields.statuses.reserved'),
                                    'pending' => __('admin.fields.statuses.pending'),
                                    'rejected' => __('admin.fields.statuses.rejected'),
                                ])
                                ->default('available')
                                ->required(),

                            Select::make('category')
                                ->label(__('admin.fields.category' ?? 'Category'))
                                ->options([
                                    'land' => 'أرض (Land)',
                                    'property' => 'عقار (Property)',
                                ])
                                ->default('land')
                                ->required()
                                ->live(),

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

                                    TextInput::make('discount')
                                        ->label(__('admin.fields.discount' ?? 'Discount'))
                                        ->numeric()
                                        ->prefix('EGP')
                                        ->nullable(),

                                    Placeholder::make('sold_at_display')
                                        ->label(__('admin.fields.sold_at'))
                                        ->content(fn($record) => $record?->sold_at?->format('Y-m-d H:i') ?? '-')
                                        ->visible(
                                            fn(Get $get, $record) =>
                                            $get('status') === 'sold' && $record?->sold_at
                                        ),

                                    Placeholder::make('reserved_at_display')
                                        ->label(__('admin.fields.reserved_at' ?? 'Reserved At'))
                                        ->content(fn($record) => $record?->reserved_at?->format('Y-m-d H:i') ?? '-')
                                        ->visible(
                                            fn(Get $get, $record) =>
                                            $get('status') === 'reserved' && $record?->reserved_at
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

                                    TextInput::make('length')
                                        ->label(__('admin.fields.length' ?? 'Length'))
                                        ->numeric()
                                        ->suffix('m')
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'land'),

                                    TextInput::make('width')
                                        ->label(__('admin.fields.width' ?? 'Width'))
                                        ->numeric()
                                        ->suffix('m')
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'land'),

                                    TextInput::make('rooms')
                                        ->label(__('admin.fields.rooms' ?? 'Rooms'))
                                        ->numeric()
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'property'),

                                    TextInput::make('bathrooms')
                                        ->label(__('admin.fields.bathrooms' ?? 'Bathrooms'))
                                        ->numeric()
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'property'),

                                    TextInput::make('garages')
                                        ->label(__('admin.fields.garages' ?? 'Garages'))
                                        ->numeric()
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'property'),

                                    TextInput::make('build_year')
                                        ->label(__('admin.fields.build_year' ?? 'Build Year'))
                                        ->numeric()
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'property'),

                                    TextInput::make('land_area')
                                        ->label(__('admin.fields.land_area' ?? 'Land Area'))
                                        ->numeric()
                                        ->suffix('m²')
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'property'),

                                    TextInput::make('internal_area')
                                        ->label(__('admin.fields.internal_area' ?? 'Internal Area'))
                                        ->numeric()
                                        ->suffix('m²')
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'property'),

                                    Select::make('development_status')
                                        ->label(__('admin.fields.development_status' ?? 'Development Status'))
                                        ->options([
                                            'under_construction' => __('admin.fields.development_statuses.under_construction'),
                                            'ready' => __('admin.fields.development_statuses.ready'),
                                            'handover_soon' => __('admin.fields.development_statuses.handover_soon'),
                                            'primary' => __('admin.fields.development_statuses.primary'),
                                            'resale' => __('admin.fields.development_statuses.resale'),
                                        ])
                                        ->nullable()
                                        ->visible(fn(Get $get) => $get('category') === 'property'),
                                ]),

                            \Filament\Schemas\Components\Section::make(__('admin.fields.features' ?? 'Features'))
                                ->schema([
                                    \Filament\Forms\Components\CheckboxList::make('amenities')
                                        ->label(__('admin.resources.amenities'))
                                        ->relationship('amenities', 'name_' . app()->getLocale())
                                        ->columns(2)
                                        ->bulkToggleable()
                                        ->columnSpanFull(),
                                ])->compact(),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.ownership' ?? 'Ownership'))
                        ->schema([
                            \Filament\Schemas\Components\Grid::make(3)
                                ->relationship('ownership')
                                ->schema([
                                    TextInput::make('contract_type')
                                        ->label(__('admin.fields.contract_type' ?? 'Contract Type'))
                                        ->maxLength(255)
                                        ->nullable(),

                                    TextInput::make('plot_number')
                                        ->label(__('admin.fields.plot_number' ?? 'Plot Number'))
                                        ->maxLength(255)
                                        ->nullable(),

                                    \Filament\Forms\Components\Toggle::make('is_registered')
                                        ->label(__('admin.fields.is_registered' ?? 'Is Registered'))
                                        ->default(false),
                                ]),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make(__('admin.fields.relations' ?? 'Relations'))
                        ->schema([
                            \Filament\Schemas\Components\Grid::make(2)
                                ->schema([
                                    Select::make('governorate_id')
                                        ->label(__('admin.resources.governorate'))
                                        ->relationship('governorate', 'name_' . app()->getLocale())
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
                            \Filament\Forms\Components\TextInput::make('location_search')
                                ->label('ابحث عن موقع')
                                ->placeholder('اكتب اسم المكان... مثال: المنصورة، الدقهلية')
                                ->suffixAction(
                                    \Filament\Actions\Action::make('search_location')
                                        ->icon('heroicon-o-magnifying-glass')
                                        ->label('بحث')
                                        ->action(function (array $arguments, $component, $livewire) {
                                            $query = $livewire->data['location_search'] ?? null;
                                            if (!$query) return;

                                            $response = \Illuminate\Support\Facades\Http::withHeaders([
                                                'User-Agent' => 'AqarMousa/1.0 (aqarmousa.com)',
                                            ])->get('https://nominatim.openstreetmap.org/search', [
                                                'q'      => $query,
                                                'format' => 'json',
                                                'limit'  => 1,
                                            ]);

                                            $results = $response->json();

                                            if (!empty($results)) {
                                                $lat = (float) $results[0]['lat'];
                                                $lng = (float) $results[0]['lon'];
                                                $livewire->data['latitude']  = $lat;
                                                $livewire->data['longitude'] = $lng;
                                                $livewire->data['location']  = ['lat' => $lat, 'lng' => $lng];
                                            } else {
                                                \Filament\Notifications\Notification::make()
                                                    ->title('لم يتم العثور على الموقع')
                                                    ->body('حاول كتابة اسم أكثر تحديداً')
                                                    ->warning()
                                                    ->send();
                                            }
                                        })
                                )
                                ->columnSpanFull()
                                ->dehydrated(false),


                            \Dotswan\MapPicker\Fields\Map::make('location')
                                ->label(__('admin.fields.location'))
                                ->columnSpanFull()
                                ->defaultLocation(latitude: 30.0444, longitude: 31.2357) // القاهرة
                                ->draggable()
                                ->clickable(true)
                                ->zoom(12)
                                ->showFullscreenControl()
                                ->showZoomControl()
                                ->liveLocation(true, true, 5000)
                                ->afterStateHydrated(function ($state, $set, $record): void {
                                    if ($record && $record->latitude && $record->longitude) {
                                        $set('location', [
                                            'lat' => $record->latitude,
                                            'lng' => $record->longitude,
                                        ]);
                                    }
                                })
                                ->afterStateUpdated(function ($state, $set): void {
                                    $set('latitude', $state['lat'] ?? null);
                                    $set('longitude', $state['lng'] ?? null);
                                }),

                            \Filament\Schemas\Components\Grid::make(2)
                                ->schema([
                                    TextInput::make('latitude')
                                        ->label(__('admin.fields.latitude'))
                                        ->numeric()
                                        ->readOnly()
                                        ->nullable(),

                                    TextInput::make('longitude')
                                        ->label(__('admin.fields.longitude'))
                                        ->numeric()
                                        ->readOnly()
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
