<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

use function Laravel\Prompts\textarea;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title_ar')
                    ->label(__('admin.fields.title_ar'))
                    ->required(),
                TextInput::make('title_en')
                    ->label(__('admin.fields.title_en')),
                    // ->required(),
                Textarea::make('content_ar')
                    ->label(__('admin.fields.content_ar'))
                    ->required()
                    ->fileAttachmentsDirectory('services')
                    ->columnSpanFull(),
                Textarea::make('content_en')
                    ->label(__('admin.fields.content_en'))
                    // ->required()
                    ->fileAttachmentsDirectory('services')
                    ->columnSpanFull(),
            ]);
    }
}
