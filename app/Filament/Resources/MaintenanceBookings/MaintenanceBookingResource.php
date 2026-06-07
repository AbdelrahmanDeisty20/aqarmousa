<?php

namespace App\Filament\Resources\MaintenanceBookings;

use App\Filament\Resources\MaintenanceBookings\Pages\CreateMaintenanceBooking;
use App\Filament\Resources\MaintenanceBookings\Pages\EditMaintenanceBooking;
use App\Filament\Resources\MaintenanceBookings\Pages\ListMaintenanceBookings;
use App\Filament\Resources\MaintenanceBookings\Pages\ViewMaintenanceBooking;
use App\Filament\Resources\MaintenanceBookings\Schemas\MaintenanceBookingForm;
use App\Filament\Resources\MaintenanceBookings\Schemas\MaintenanceBookingInfolist;
use App\Filament\Resources\MaintenanceBookings\Tables\MaintenanceBookingsTable;
use App\Models\MaintenanceBooking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaintenanceBookingResource extends Resource
{
    protected static ?string $model = MaintenanceBooking::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    public static function getNavigationBadge(): ?string
    {
        return (string) MaintenanceBooking::count();
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.maintenance_bookings');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.maintenance_booking');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.maintenance_bookings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.users_interaction');
    }

    public static function form(Schema $schema): Schema
    {
        return MaintenanceBookingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MaintenanceBookingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaintenanceBookingsTable::configure($table);
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
            'index' => ListMaintenanceBookings::route('/'),
            'create' => CreateMaintenanceBooking::route('/create'),
            'view' => ViewMaintenanceBooking::route('/{record}'),
            'edit' => EditMaintenanceBooking::route('/{record}/edit'),
        ];
    }
}
