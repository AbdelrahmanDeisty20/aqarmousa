<?php

namespace App\Filament\Resources\Units;

use App\Filament\Resources\Units\Pages\CreateUnit;
use App\Filament\Resources\Units\Pages\EditUnit;
use App\Filament\Resources\Units\Pages\ListUnits;
use App\Filament\Resources\Units\Schemas\UnitForm;
use App\Filament\Resources\Units\Tables\UnitTable;
use App\Models\Unit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnitResource extends Resource
{


    protected static ?string $model = Unit::class;

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->{'title_' . app()->getLocale()} ?? $record->title_ar;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.real_estate');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.unit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.units');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-home';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Unit::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    protected static ?string $recordTitleAttribute = 'title_ar';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title_ar', 'title_en', 'description_ar', 'description_en', 'address_ar', 'address_en'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            __('admin.fields.price') => number_format($record->price) . ' EGP',
            __('admin.fields.status') => __('admin.fields.statuses.' . $record->status),
        ];
    }

    public static function getGlobalSearchResultImage(\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->media()->first()?->url;
    }

    public static function form(Schema $schema): Schema
    {
        return UnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitTable::configure($table);
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
            'index' => ListUnits::route('/'),
            'create' => CreateUnit::route('/create'),
            'edit' => EditUnit::route('/{record}/edit'),
        ];
    }
}
