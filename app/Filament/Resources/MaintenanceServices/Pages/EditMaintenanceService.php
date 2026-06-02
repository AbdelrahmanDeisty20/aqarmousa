<?php

namespace App\Filament\Resources\MaintenanceServices\Pages;

use App\Filament\Resources\MaintenanceServices\MaintenanceServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceService extends EditRecord
{
    protected static string $resource = MaintenanceServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
