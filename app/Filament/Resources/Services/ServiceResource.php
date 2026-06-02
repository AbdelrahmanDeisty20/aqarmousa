<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.content_management');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.service');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.services');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-wrench-screwdriver';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Service::count();
    }

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->{'title_' . app()->getLocale()} ?? $record->title_ar;
    }

    protected static ?string $recordTitleAttribute = 'title_ar';

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
