<?php

namespace App\Filament\Resources\Developers;

use App\Filament\Resources\Developers\Pages\CreateDeveloper;
use App\Filament\Resources\Developers\Pages\EditDeveloper;
use App\Filament\Resources\Developers\Pages\ListDevelopers;
use App\Filament\Resources\Developers\Schemas\DeveloperForm;
use App\Filament\Resources\Developers\Tables\DevelopersTable;
use App\Models\Developer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeveloperResource extends Resource
{
    protected static ?string $model = Developer::class;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.real_estate');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.developer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.developers');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-briefcase';
    }
    public static function getNavigationBadge(): ?string
    {
        return (string) Developer::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }


    protected static ?string $recordTitleAttribute = 'name_en';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name_ar', 'name_en', 'email', 'phone'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            __('admin.fields.email') => $record->email,
            __('admin.fields.status') => __('admin.fields.statuses.' . $record->status),
        ];
    }

    public static function getGlobalSearchResultImage(\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->logo;
    }

    public static function form(Schema $schema): Schema
    {
        return DeveloperForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevelopersTable::configure($table);
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
            'index' => ListDevelopers::route('/'),
            'create' => CreateDeveloper::route('/create'),
            'edit' => EditDeveloper::route('/{record}/edit'),
        ];
    }
}
