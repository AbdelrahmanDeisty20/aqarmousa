<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $stats = [
            [
                'label' => app()->getLocale() === 'ar' ? 'مدن نغطيها' : 'Cities Covered',
                'value' => \App\Models\City::count() . '+',
                'icon' => 'location-icon',
            ],
            [
                'label' => app()->getLocale() === 'ar' ? 'عملاء سعداء' : 'Happy Clients',
                'value' => \App\Models\User::count() . '+',
                'icon' => 'clients-icon',
            ],
            [
                'label' => app()->getLocale() === 'ar' ? 'عقارات تم بيعها' : 'Properties Sold',
                'value' => \App\Models\Unit::where('status', 'sold')->count() . '+',
                'icon' => 'sold-icon',
            ],
            [
                'label' => app()->getLocale() === 'ar' ? 'مشروعات تم تنفيذها' : 'Projects Executed',
                'value' => \App\Models\Compound::count() . '+',
                'icon' => 'projects-icon',
            ],
        ];

        return $this->success($stats);
    }
}
