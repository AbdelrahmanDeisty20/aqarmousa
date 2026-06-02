<?php

namespace App\Filament\Resources\Cities;

use App\Filament\Resources\Cities\Pages\CreateCity;
use App\Filament\Resources\Cities\Pages\EditCity;
use App\Filament\Resources\Cities\Pages\ListCities;
use App\Filament\Resources\Cities\Schemas\CityForm;
use App\Filament\Resources\Cities\Tables\CitiesTable;
use App\Models\City;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->{'name_' . app()->getLocale()} ?? $record->name_ar;
    }

    protected static ?string $recordTitleAttribute = 'name_ar';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name_ar', 'name_en'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.real_estate');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.city');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.cities');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-map-pin';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) City::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function form(Schema $schema): Schema
    {
        return CityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CitiesTable::configure($table);
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
            'index' => ListCities::route('/'),
            'create' => CreateCity::route('/create'),
            'edit' => EditCity::route('/{record}/edit'),
        ];
    }
}
