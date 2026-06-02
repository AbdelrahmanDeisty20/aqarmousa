<?php

namespace App\Filament\Resources\UnitMedia;

use App\Filament\Resources\UnitMedia\Pages\CreateUnitMedia;
use App\Filament\Resources\UnitMedia\Pages\EditUnitMedia;
use App\Filament\Resources\UnitMedia\Pages\ListUnitMedia;
use App\Filament\Resources\UnitMedia\Schemas\UnitMediaForm;
use App\Filament\Resources\UnitMedia\Tables\UnitMediaTable;
use App\Models\UnitMedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnitMediaResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = UnitMedia::class;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.real_estate');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.unit_media');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.unit_media');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'url';

    public static function form(Schema $schema): Schema
    {
        return UnitMediaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitMediaTable::configure($table);
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
            'index' => ListUnitMedia::route('/'),
            'create' => CreateUnitMedia::route('/create'),
            'edit' => EditUnitMedia::route('/{record}/edit'),
        ];
    }
}
