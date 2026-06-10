<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ManageActivityLogs;
use App\Models\ActivityLog;
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

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation_groups.monitoring');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.activity_log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.activity_logs');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-list';
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
                TextEntry::make('user.name')
                    ->label('المستخدم المسجل')
                    ->placeholder('-'),
                TextEntry::make('user_name')
                    ->label('اسم الفاعل')
                    ->placeholder('-'),
                TextEntry::make('user_email')
                    ->label('البريد الإلكتروني')
                    ->placeholder('-'),
                TextEntry::make('action')
                    ->label('العملية / الإجراء'),
                TextEntry::make('description')
                    ->label('الوصف التفصيلي')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('ip_address')
                    ->label('عنوان IP')
                    ->placeholder('-'),
                TextEntry::make('user_agent')
                    ->label('تفاصيل المتصفح / الجهاز')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('وقت العملية')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_name')
                    ->label('اسم المستخدم')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user_email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('action')
                    ->label('العملية')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'حساب جديد' => 'success',
                        'إضافة مفضلة' => 'info',
                        'طلب معاينة' => 'warning',
                        'رسالة تواصل' => 'primary',
                        'إضافة عقار/أرض' => 'success',
                        'تعديل عقار/أرض' => 'info',
                        'حذف عقار/أرض' => 'danger',
                        'حذف مستخدم' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('description')
                    ->label('البيان')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('وقت العملية')
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
            'index' => ManageActivityLogs::route('/'),
        ];
    }
}
