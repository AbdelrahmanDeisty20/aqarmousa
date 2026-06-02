<?php

namespace App\Filament\Resources\Compounds\Pages;

use App\Filament\Resources\Compounds\CompoundResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompound extends CreateRecord
{
    protected static string $resource = CompoundResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCancelUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
