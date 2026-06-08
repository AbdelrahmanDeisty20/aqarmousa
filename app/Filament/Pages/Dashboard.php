<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\LatestUnits::class,
            \App\Filament\Widgets\LatestContacts::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
