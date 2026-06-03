<?php

namespace App\Filament\Exports;

use App\Models\Unit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class UnitExporter extends Exporter
{
    protected static ?string $model = Unit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('title_ar'),
            ExportColumn::make('title_en'),
            ExportColumn::make('description_ar'),
            ExportColumn::make('description_en'),
            ExportColumn::make('price'),
            ExportColumn::make('discount')->label('الخصم'),
            ExportColumn::make('price_per_m2'),
            ExportColumn::make('offer_type'),
            ExportColumn::make('area'),
            ExportColumn::make('length')->label('الطول'),
            ExportColumn::make('width')->label('العرض'),
            ExportColumn::make('category')->label('التصنيف'),
            ExportColumn::make('rooms')->label('الغرف'),
            ExportColumn::make('bathrooms')->label('الحمامات'),
            ExportColumn::make('garages')->label('الجراجات'),
            ExportColumn::make('build_year')->label('سنة البناء'),
            ExportColumn::make('land_area')->label('مساحة الأرض الملحقة'),
            ExportColumn::make('internal_area')->label('المساحة الداخلية'),
            ExportColumn::make('development_status')->label('حالة التطوير'),
            ExportColumn::make('status'), // الحالة

            // --- تصدير الأسماء بدلاً من الأرقام لسهولة القراءة والاستخدام كنموذج ---
            ExportColumn::make('owner.email')->label('إيميل المالك'),
            ExportColumn::make('governorate.name_ar')->label('المحافظة'),
            ExportColumn::make('type.name_ar')->label('نوع الأرض'),
            ExportColumn::make('compound.name_ar')->label('الكمبوند'),
            ExportColumn::make('developer.name_ar')->label('المطور العقاري'),

            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('latitude'),
            ExportColumn::make('longitude'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your unit export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
