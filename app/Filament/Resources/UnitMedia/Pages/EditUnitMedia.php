<?php

namespace App\Filament\Resources\UnitMedia\Pages;

use App\Filament\Resources\UnitMedia\UnitMediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUnitMedia extends EditRecord
{
    protected static string $resource = UnitMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
