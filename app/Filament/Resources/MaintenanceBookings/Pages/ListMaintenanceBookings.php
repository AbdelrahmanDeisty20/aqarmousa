<?php

namespace App\Filament\Resources\MaintenanceBookings\Pages;

use App\Filament\Resources\MaintenanceBookings\MaintenanceBookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceBookings extends ListRecords
{
    protected static string $resource = MaintenanceBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
