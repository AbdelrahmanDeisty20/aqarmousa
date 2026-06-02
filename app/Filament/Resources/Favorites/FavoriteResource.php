<?php

namespace App\Filament\Resources\Favorites;

use App\Filament\Resources\Favorites\Pages\CreateFavorite;
use App\Filament\Resources\Favorites\Pages\EditFavorite;
use App\Filament\Resources\Favorites\Pages\ListFavorites;
use App\Filament\Resources\Favorites\Schemas\FavoriteForm;
use App\Filament\Resources\Favorites\Tables\FavoritesTable;
use App\Models\Favorite;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FavoriteResource extends Resource
{
    protected static ?string $model = Favorite::class;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.users_interaction');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.favorite');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.favorites');
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';
    
    public static function getNavigationBadge(): ?string
    {
        return (string) Favorite::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return FavoriteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FavoritesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFavorites::route('/'),
        ];
    }
}
