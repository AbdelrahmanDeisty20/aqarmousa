<?php

namespace App\Filament\Resources\Viewings\Pages;

use App\Filament\Resources\Viewings\ViewingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListViewings extends ListRecords
{
    protected static string $resource = ViewingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('viewing.forms.create_viewing')),
        ];
    }
}
