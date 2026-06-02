<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Filament\Resources\Reviews\Schemas\ReviewForm;
use App\Filament\Resources\Reviews\Tables\ReviewsTable;
use App\Models\Review;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit($record): bool
    {
        return true;
    }

    public static function canDelete($record): bool
    {
        return true;
    }

    public static function canDeleteAny(): bool
    {
        return true;
    }


    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.users_interaction');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.review');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.reviews');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-star';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Review::count();
    }



    protected static ?string $recordTitleAttribute = 'comment';

    public static function form(Schema $schema): Schema
    {
        return ReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewsTable::configure($table);
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
            'index' => ListReviews::route('/'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }
}
