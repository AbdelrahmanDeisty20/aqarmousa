<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['email_verified'])) {
            $data['email_verified_at'] = $data['email_verified'] ? now() : null;
            unset($data['email_verified']);
        }

        return $data;
    }
}