<?php

namespace App\Filament\Resources\AmenityResource;

use App\Filament\Resources\AmenityResource\Pages;
use App\Models\Amenity;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class AmenityResource extends Resource
{
    protected static ?string $model = Amenity::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.real_estate');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.amenity');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.amenities');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Amenity::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name_ar')
                    ->label(__('admin.fields.name_ar'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_en')
                    ->label(__('admin.fields.name_en'))
                    ->required()
                    ->maxLength(255),
                FileUpload::make('icon')
                    ->label(__('admin.fields.icon'))
                    ->helperText('يرجى استخدام صيغ الصور المدعومة: JPG, PNG, JPEG')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png','image/jpg'])
                    ->disk('public')
                    ->visibility('public')
                    ->directory('amenities')
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label(__('admin.fields.icon'))
                    ->disk('public'),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('admin.fields.name_ar'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('admin.fields.name_en'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAmenities::route('/'),
            'create' => Pages\CreateAmenity::route('/create'),
            'edit' => Pages\EditAmenity::route('/{record}/edit'),
        ];
    }
}
