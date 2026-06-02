<?php

namespace App\Filament\Resources\UnitTypes;

use App\Filament\Resources\UnitTypes\Pages\CreateUnitType;
use App\Filament\Resources\UnitTypes\Pages\EditUnitType;
use App\Filament\Resources\UnitTypes\Pages\ListUnitTypes;
use App\Filament\Resources\UnitTypes\Schemas\UnitTypeForm;
use App\Filament\Resources\UnitTypes\Tables\UnitTypesTable;
use App\Models\UnitType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnitTypeResource extends Resource
{
    protected static ?string $model = UnitType::class;

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->{'name_' . app()->getLocale()} ?? $record->name_ar;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.real_estate');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.unit_type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.unit_types');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-tag';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) UnitType::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
    

    protected static ?string $recordTitleAttribute = 'name_ar';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name_ar', 'name_en'];
    }

    public static function form(Schema $schema): Schema
    {
        return UnitTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitTypesTable::configure($table);
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
            'index' => ListUnitTypes::route('/'),
            'create' => CreateUnitType::route('/create'),
            'edit' => EditUnitType::route('/{record}/edit'),
        ];
    }
}
