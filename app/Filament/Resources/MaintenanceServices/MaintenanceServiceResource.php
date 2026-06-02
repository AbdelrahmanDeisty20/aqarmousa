<?php

namespace App\Filament\Resources\MaintenanceServices;

use App\Filament\Resources\MaintenanceServices\Pages\CreateMaintenanceService;
use App\Filament\Resources\MaintenanceServices\Pages\EditMaintenanceService;
use App\Filament\Resources\MaintenanceServices\Pages\ListMaintenanceServices;
use App\Filament\Resources\MaintenanceServices\Schemas\MaintenanceServiceForm;
use App\Filament\Resources\MaintenanceServices\Tables\MaintenanceServicesTable;
use App\Models\MaintenanceService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaintenanceServiceResource extends Resource
{
    protected static ?string $model = MaintenanceService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;
    public static function getNavigationBadge(): ?string
    {
        return (string) MaintenanceService::count();
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.maintenance_services');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.maintenance_service');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.maintenance_services');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.content_management');
    }

    public static function form(Schema $schema): Schema
    {
        return MaintenanceServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaintenanceServicesTable::configure($table);
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
            'index' => ListMaintenanceServices::route('/'),
            'create' => CreateMaintenanceService::route('/create'),
            'edit' => EditMaintenanceService::route('/{record}/edit'),
        ];
    }
}