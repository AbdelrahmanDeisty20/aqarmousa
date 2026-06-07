<?php

namespace App\Filament\Resources\Banners;

use App\Filament\Resources\Banners\Pages\CreateBanner;
use App\Filament\Resources\Banners\Pages\EditBanner;
use App\Filament\Resources\Banners\Pages\ListBanners;
use App\Models\Banner;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.banners');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.banners');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.banner');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.content_management');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Banner::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label(__('admin.fields.image'))
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->helperText(__('admin.fields.allowed_formats', ['formats' => 'jpg, png, jpeg']))
                    ->directory('banners')
                    ->disk('public')
                    ->required(),
                Select::make('url')
                    ->label(__('admin.fields.link'))
                    ->options([
                        '/units' => __('admin.frontend_links.units'),
                        '/terms' => __('admin.frontend_links.terms'),
                        '/about' => __('admin.frontend_links.about'),
                        '/services' => __('admin.frontend_links.services'),
                        '/contactUs' => __('admin.frontend_links.contact_us'),
                    ])
                    ->searchable()
                    ->required(),
                Toggle::make('is_active')
                    ->label(__('admin.fields.active_site'))
                    ->default(true)
                    ->required(),
                TextInput::make('sort_order')
                    ->label(__('admin.fields.sort_order'))
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('admin.fields.image'))
                    ->disk('public')
                    ->rounded()
                    ->size(100),
                TextColumn::make('url')
                    ->label(__('admin.fields.link'))
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label(__('admin.fields.active_site'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('admin.fields.sort_order'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('admin.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBanners::route('/'),
            'create' => CreateBanner::route('/create'),
            'edit' => EditBanner::route('/{record}/edit'),
        ];
    }
}
