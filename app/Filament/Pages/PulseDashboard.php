<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;

class PulseDashboard extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cpu-chip';

    protected string $view = 'filament.pages.pulse-dashboard';

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'أداء ومراقبة الموقع' : 'Site Performance';
    }

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'أدوات المراقبة' : 'Monitoring Tools';
    }
    
    public function getHeading(): string
    {
        return app()->getLocale() === 'ar' ? 'أداء ومراقبة الموقع' : 'Site Performance';
    }
}
