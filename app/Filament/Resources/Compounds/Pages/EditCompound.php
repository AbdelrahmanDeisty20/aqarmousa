<?php

namespace App\Filament\Resources\Compounds\Pages;

use App\Filament\Resources\Compounds\CompoundResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompound extends EditRecord
{
    protected static string $resource = CompoundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCancelUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
