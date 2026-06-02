<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('admin.fields.page_info'))
                    ->schema([
                        TextInput::make('title_ar')
                            ->label(__('admin.fields.title_ar'))
                            ->required(),
                        TextInput::make('title_en')
                            ->label(__('admin.fields.title_en'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
                        Textarea::make('content_ar')
                            ->label(__('admin.fields.content_ar'))
                            ->required()
                            ->rows(10)
                            ->columnSpanFull(),
                        Textarea::make('content_en')
                            ->label(__('admin.fields.content_en'))
                            ->rows(10)
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label(__('admin.fields.link'))
                            ->required()
                            ->unique('pages', 'slug', ignoreRecord: true)
                            ->helperText(__('admin.fields.auto_generated_slug')),
                    ]),

                Section::make(__('admin.fields.team_members'))
                    ->description(__('admin.fields.team_description'))
                    ->visible(fn($get) => $get('slug') === 'about-us')
                    ->schema([
                        Repeater::make('team_members')
                            ->label(__('admin.fields.team_members'))
                            ->schema([
                                TextInput::make('name')->label(__('admin.fields.name'))->required(),
                                TextInput::make('position')->label(__('admin.fields.position')),
                                FileUpload::make('photo')
                                    ->label(fn($get) => match ($get('type')) {
                                        default => __('admin.fields.image'),
                                    })
                                    ->helperText('يرجى استخدام صيغ الصور المدعومة: JPG, PNG, JPEG')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpg', 'image/png', 'image/jpeg'])
                                    ->directory('team')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->downloadable()
                                    ->openable()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                            ->collapsible()
                            ->collapsed(),
                    ]),

                Section::make(__('admin.fields.sections'))
                    ->description(__('admin.fields.sections_description'))
                    ->visible(fn($get) => $get('slug') === 'terms-and-conditions')
                    ->schema([
                        Repeater::make('sections')
                            ->label(__('admin.fields.sections'))
                            ->schema([
                                TextInput::make('title_ar')->label(__('admin.fields.title_ar'))->required(),
                                TextInput::make('title_en')->label(__('admin.fields.title_en'))->required(),
                                Textarea::make('content_ar')->label(__('admin.fields.content_ar'))->required()->rows(5)->columnSpanFull(),
                                Textarea::make('content_en')->label(__('admin.fields.content_en'))->required()->rows(5)->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->itemLabel(fn(array $state): ?string => $state['title_ar'] ?? null)
                            ->collapsible()
                            ->collapsed(),
                    ]),
            ]);
    }
}
