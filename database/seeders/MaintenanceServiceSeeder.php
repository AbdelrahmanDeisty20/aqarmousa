<?php

namespace Database\Seeders;

use App\Models\MaintenanceService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MaintenanceServiceSeeder extends Seeder
{
    public function run(): void
    {
        $targetDir = 'maintenance-services';
        Storage::disk('public')->makeDirectory($targetDir);

        $services = [
            // Home Services
            [
                'title_ar' => 'خدمة تنظيف المنازل',
                'title_en' => 'Home Cleaning Service',
                'category' => 'home',
                'image' => 'maintenance-services/housekeeping.jpg',
            ],
            [
                'title_ar' => 'خدمة مكافحة الحشرات',
                'title_en' => 'Pest Control Service',
                'category' => 'home',
                'image' => 'maintenance-services/insects.jpg',
            ],
            [
                'title_ar' => 'غسيل السجاد والموكيت',
                'title_en' => 'Carpet & Upholstery Cleaning',
                'category' => 'home',
                'image' => 'maintenance-services/blanket.jpg',
            ],
            [
                'title_ar' => 'تنسيق الحدائق',
                'title_en' => 'Landscaping & Gardening',
                'category' => 'home',
                'image' => 'maintenance-services/gardens.jpg',
            ],

            // Technical Services
            [
                'title_ar' => 'صيانة التكييف',
                'title_en' => 'AC Maintenance',
                'category' => 'technical',
                'image' => 'maintenance-services/airconditioner.jpg',
            ],
            [
                'title_ar' => 'أعمال السباكة',
                'title_en' => 'Plumbing Works',
                'category' => 'technical',
                'image' => 'maintenance-services/plumbing.jpg',
            ],
            [
                'title_ar' => 'أعمال الكهرباء',
                'title_en' => 'Electrical Works',
                'category' => 'technical',
                'image' => 'maintenance-services/electrical.jpg',
            ],
            [
                'title_ar' => 'أعمال النجارة',
                'title_en' => 'Carpentry Works',
                'category' => 'technical',
                'image' => 'maintenance-services/nigara.jpg',
            ],
        ];

        foreach ($services as $service) {
            $sourceFile = base_path('images/' . basename($service['image']));
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, Storage::disk('public')->path($service['image']));
            }

            MaintenanceService::updateOrCreate(
                ['title_ar' => $service['title_ar']],
                $service
            );
        }
    }
}
