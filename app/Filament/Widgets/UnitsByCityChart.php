<?php

// UnitsByCityChart.php
namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Widgets\ChartWidget;

class UnitsByCityChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return __('admin.widgets.units_by_city');
    }

    protected function getData(): array
    {
        $nameField = app()->getLocale() === 'ar' ? 'name_ar' : 'name_en';
        $cities = Unit::with('city')->get()->groupBy("city.$nameField");

        $labels = $cities->keys()->toArray();
        $data = $cities->map(fn($units) => count($units))->values()->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('admin.resources.units'),
                    'data' => $data,
                    'backgroundColor' => '#6366f1',
                    'borderColor' => '#6366f1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
