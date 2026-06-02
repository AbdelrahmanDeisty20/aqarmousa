<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Widgets\ChartWidget;

class UnitsByTypeChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return __('admin.widgets.units_by_type');
    }

    protected function getData(): array
    {
        $nameField = app()->getLocale() === 'ar' ? 'name_ar' : 'name_en';
        $types = Unit::with('type')->get()->groupBy("type.$nameField");

        $labels = $types->keys()->toArray();
        $data = $types->map(fn($units) => count($units))->values()->toArray();

        // Generate colors for each type
        $colors = [
            '#6366f1', // Indigo
            '#ec4899', // Pink
            '#10b981', // Green
            '#f59e0b', // Amber
            '#8b5cf6', // Purple
            '#06b6d4', // Cyan
            '#ef4444', // Red
            '#14b8a6', // Teal
            '#f97316', // Orange
            '#84cc16', // Lime
        ];

        return [
            'datasets' => [
                [
                    'label' => __('admin.resources.units'),
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
