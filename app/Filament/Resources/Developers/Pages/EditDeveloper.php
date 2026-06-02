<?php

namespace App\Filament\Resources\Developers\Pages;

use App\Filament\Resources\Developers\DeveloperResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeveloper extends EditRecord
{
    protected static string $resource = DeveloperResource::class;

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
