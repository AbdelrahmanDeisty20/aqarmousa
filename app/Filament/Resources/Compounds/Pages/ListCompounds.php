<?php

namespace App\Filament\Resources\Compounds\Pages;

use App\Filament\Resources\Compounds\CompoundResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompounds extends ListRecords
{
    protected static string $resource = CompoundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
