<?php

namespace App\Filament\Resources\MaintenanceBookings\Pages;

use App\Filament\Resources\MaintenanceBookings\MaintenanceBookingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMaintenanceBooking extends ViewRecord
{
    protected static string $resource = MaintenanceBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
