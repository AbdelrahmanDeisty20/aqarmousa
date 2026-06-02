<?php

namespace App\Filament\Widgets;

use App\Models\Review;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use App\Models\Viewing;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $currency = app()->getLocale() === 'ar' ? ' ج.م' : ' EGP';

        return [
            Stat::make(__('admin.resources.units'), Unit::count())
                ->description(__('admin.widgets.stats_overview.total_units'))
                ->descriptionIcon('heroicon-m-home-modern')
                ->icon('heroicon-o-home')
                ->color('primary')
                ->url(route('filament.admin.resources.units.index')),

            // Stat::make(__('admin.widgets.stats_overview.pending_units'), Unit::where('status', 'pending')->count())
            //     ->description(__('admin.widgets.stats_overview.pending_units_desc'))
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->icon('heroicon-o-clock')
            //     ->color('warning')
            //     ->url(route('filament.admin.resources.units.index', [
            //         'tableFilters' => [
            //             'status_filter' => [
            //                 'value' => 'pending',
            //             ],
            //         ],
            //         'filters' => [
            //             'status_filter' => [
            //                 'value' => 'pending',
            //             ],
            //         ],
            //     ])),

            Stat::make(__('admin.resources.users'), User::count())
                ->description(__('admin.widgets.stats_overview.total_users'))
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-users')
                ->color('info')
                ->url(route('filament.admin.resources.users.index')),

            Stat::make(__('admin.resources.reviews'), Review::count())
                ->description(__('admin.widgets.stats_overview.total_reviews'))
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->icon('heroicon-o-chat-bubble-bottom-center')
                ->color('primary')
                ->url(route('filament.admin.resources.reviews.index')),

            Stat::make(__('admin.widgets.stats_overview.bad_reviews'), Review::where('rating', '<', 3)->count())
                ->description(__('admin.widgets.stats_overview.bad_reviews_desc'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger')
                ->url(route('filament.admin.resources.reviews.index', [
                    'tableFilters' => [
                        'rating_filter' => [
                            'value' => 'bad',
                        ],
                    ],
                    'filters' => [
                        'rating_filter' => [
                            'value' => 'bad',
                        ],
                    ],
                ])),

            Stat::make(__('admin.resources.viewings'), Viewing::count())
                ->description(__('admin.widgets.stats_overview.total_viewings'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->icon('heroicon-o-calendar')
                ->color('success')
                ->url(route('filament.admin.resources.viewings.index')),

            Stat::make(__('admin.widgets.stats_overview.pending_viewings'), Viewing::where('status', 'pending')->count())
                ->description(__('admin.widgets.stats_overview.pending_viewings_desc'))
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->url(route('filament.admin.resources.viewings.index', [
                     'tableFilters' => [
                        'status' => [
                            'value' => 'pending',
                        ],
                    ],
                    'filters' => [
                        'status' => [
                            'value' => 'pending',
                        ],
                    ],
                ])),

            // Payment system disabled - Transaction stats commented out
            /*
            Stat::make(__('admin.resources.transactions'), Transaction::count())
                ->description(__('admin.widgets.stats_overview.total_transactions'))
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->icon('heroicon-o-currency-dollar')
                ->color('primary')
                ->url(route('filament.admin.resources.transactions.index')),

            Stat::make(__('admin.widgets.stats_overview.total_revenue'), number_format(Transaction::sum('amount')) . $currency)
                ->description(__('admin.widgets.stats_overview.total_revenue'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->url(route('filament.admin.resources.transactions.index')),
            */
        ];
    }

    protected function getWidgets(): array
    {
        return [
            UnitsByCityChart::class,
            // TransactionsByMonthChart::class, // Commented out - payment system disabled
        ];
    }
}