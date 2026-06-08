<?php

namespace App\Filament\Widgets;

use App\Models\Viewing;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestViewings extends TableWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function getTableHeading(): string
    {
        return app()->getLocale() === 'ar' ? 'آخر طلبات المعاينة' : 'Latest Viewing Requests';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Viewing::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(app()->getLocale() === 'ar' ? 'الاسم' : 'Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone'),
                Tables\Columns\TextColumn::make('unit.title_' . (app()->getLocale() === 'ar' ? 'ar' : 'en'))
                    ->label(app()->getLocale() === 'ar' ? 'الوحدة / الأرض' : 'Unit / Land')
                    ->limit(30),
                Tables\Columns\TextColumn::make('date')
                    ->label(app()->getLocale() === 'ar' ? 'التاريخ المحدد' : 'Requested Date')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->label(app()->getLocale() === 'ar' ? 'الحالة' : 'Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'reschedule_admin', 'reschedule_user' => 'warning',
                        default => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'accepted' => app()->getLocale() === 'ar' ? 'مقبول' : 'Accepted',
                        'rejected' => app()->getLocale() === 'ar' ? 'مرفوض' : 'Rejected',
                        'reschedule_admin' => app()->getLocale() === 'ar' ? 'إعادة جدولة (الأدمن)' : 'Reschedule (Admin)',
                        'reschedule_user' => app()->getLocale() === 'ar' ? 'إعادة جدولة (المستخدم)' : 'Reschedule (User)',
                        default => app()->getLocale() === 'ar' ? 'قيد الانتظار' : 'Pending',
                    }),
            ])
            ->recordUrl(fn (Viewing $record): string => \App\Filament\Resources\Viewings\ViewingResource::getUrl('edit', ['record' => $record]));
    }
}
