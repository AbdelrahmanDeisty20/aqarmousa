<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Widgets\ChartWidget;

class UnitsByOfferTypeChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return __('admin.widgets.units_by_offer_type');
    }

    protected function getData(): array
    {
        $saleCount = Unit::where('offer_type', 'sale')->count();
        $rentCount = Unit::where('offer_type', 'rent')->count();

        return [
            'datasets' => [
                [
                    'label' => __('admin.resources.units'),
                    'data' => [$saleCount, $rentCount],
                    'backgroundColor' => [
                        '#10b981', // Green for sale
                        '#f59e0b', // Amber for rent
                    ],
                ],
            ],
            'labels' => [
                __('admin.fields.offer_types.sale'),
                __('admin.fields.offer_types.rent'),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
