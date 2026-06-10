<?php

namespace App\Filament\Resources\PageVisits\Pages;

use App\Filament\Resources\PageVisits\PageVisitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePageVisits extends ManageRecords
{
    protected static string $resource = PageVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
