<?php

namespace App\Filament\Resources\UnitMedia\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class UnitMediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('unit_id')->relationship('unit', 'title_ar')->label(__('admin.resources.unit'))->required(),
                Select::make('type')->options([
                    'image' => __('admin.fields.media_types.image'),
                    'video' => __('admin.fields.media_types.video'),
                    'floorplan' => __('admin.fields.media_types.floorplan'),
                ])->label(__('admin.fields.type'))->required()->live(),
                Placeholder::make('video_preview')
                    ->label(__('admin.fields.video_preview'))
                    ->content(fn($get) => $get('type') === 'video' && $get('url')
                        ? new HtmlString('<video controls width="100%" src="' . \Illuminate\Support\Facades\Storage::disk('public')->url($get('url')) . '"></video>')
                        : null)
                    ->hidden(fn($get) => $get('type') !== 'video' || !$get('url'))
                    ->columnSpanFull(),
                FileUpload::make('url')
                    ->label(fn($get) => match ($get('type')) {
                        'video' => __('admin.fields.media_types.video'),
                        'image' => __('admin.fields.media_types.image'),
                        default => __('admin.fields.file'),
                    })
                    ->directory('units/media')
                    ->acceptedFileTypes(['image/*', 'video/*', 'application/octet-stream', 'image/jfif'])
                    ->disk('public')
                    ->visibility('public')
                    ->helperText(__('admin.fields.keep_current'))
                    ->required()
                    ->live(),
            ]);
    }
}
