<?php

namespace App\Filament\Resources\MaintenanceBookings\Pages;

use App\Filament\Resources\MaintenanceBookings\MaintenanceBookingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceBooking extends EditRecord
{
    protected static string $resource = MaintenanceBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
