<?php

namespace App\Filament\Resources\Compounds;

use App\Filament\Resources\Compounds\Pages\CreateCompound;
use App\Filament\Resources\Compounds\Pages\EditCompound;
use App\Filament\Resources\Compounds\Pages\ListCompounds;
use App\Filament\Resources\Compounds\Schemas\CompoundForm;
use App\Filament\Resources\Compounds\Tables\CompoundsTable;
use App\Models\Compound;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompoundResource extends Resource
{
    protected static ?string $model = Compound::class;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.real_estate');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.compound');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.compounds');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-home-modern';
    }
public static function getNavigationBadge(): ?string
    {
        return (string) Compound::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->{'name_' . app()->getLocale()} ?? $record->name_ar;
    }

    protected static ?string $recordTitleAttribute = 'name_ar';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name_ar', 'name_en', 'description_ar', 'description_en'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            __('admin.resources.city') => $record->city?->{'name_' . app()->getLocale()} ?? $record->city?->name_ar,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return CompoundForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompoundsTable::configure($table);
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
            'index' => ListCompounds::route('/'),
            'create' => CreateCompound::route('/create'),
            'edit' => EditCompound::route('/{record}/edit'),
        ];
    }
}
