<?php

namespace App\Filament\Resources\PageVisits;

use App\Filament\Resources\PageVisits\Pages\ManagePageVisits;
use App\Models\PageVisit;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageVisitResource extends Resource
{
    protected static ?string $model = PageVisit::class;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.monitoring');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.page_visit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.page_visits');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-eye';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('ip_address')
                    ->label('عنوان IP')
                    ->placeholder('-'),
                TextEntry::make('url')
                    ->label('رابط الصفحة التي تمت زيارتها')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('referer')
                    ->label('المصدر / المحيل (Referer)')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('user.name')
                    ->label('المستخدم المسجل')
                    ->placeholder('زائر غير مسجل'),
                TextEntry::make('user_agent')
                    ->label('تفاصيل المتصفح / الجهاز (User Agent)')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('وقت الزيارة')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ip_address')
                    ->label('IP الزائر')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('الرابط المزار')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('user.name')
                    ->label('العضو المسجل')
                    ->placeholder('زائر مجهول')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('وقت الزيارة')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePageVisits::route('/'),
        ];
    }
}
