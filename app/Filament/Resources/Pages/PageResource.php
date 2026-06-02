<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Models\Page;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.content_management');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.page');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.pages');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-duplicate';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Page::count();
    }

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record->{'title_' . app()->getLocale()} ?? $record->title_ar;
    }

    protected static ?string $recordTitleAttribute = 'title_ar';

    public static function form(Schema $schema): Schema
    {
        return PageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
    }

    public static function canDelete(Model $record): bool
    {
        return !in_array($record->slug, [
            'about-us',
            'contact-us',
            'privacy-policy',
            'terms-and-conditions',
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
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
