<?php

namespace App\Filament\Widgets;

use App\Models\Viewing;
use Filament\Widgets\ChartWidget;

class ViewingsByMonthChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return __('admin.widgets.monthly_viewings');
    }

    protected function getData(): array
    {
        // Get viewings grouped by month
        $viewings = Viewing::all()->groupBy(fn($v) => $v->created_at->format('Y-m'));

        $labels = $viewings->keys()->toArray();
        $data = $viewings->map(fn($month) => count($month))->values()->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('admin.resources.viewings'),
                    'data' => $data,
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'borderColor' => '#6366f1',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
