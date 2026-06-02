<?php

namespace App\Filament\Resources\UnitMedia\Pages;

use App\Filament\Resources\UnitMedia\UnitMediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUnitMedia extends ListRecords
{
    protected static string $resource = UnitMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
