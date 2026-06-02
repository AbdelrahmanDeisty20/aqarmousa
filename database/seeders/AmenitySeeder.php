<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $targetDir = 'amenities';
        $amenities = [
            ['name_en' => 'Private Pool', 'name_ar' => 'حمام سباحة خاص', 'icon_file' => 'pool.jpg'],
            ['name_en' => 'Shared Gym', 'name_ar' => 'صالة ألعاب رياضية مشتركة', 'icon_file' => 'gym.jpg'],
            ['name_en' => 'Security', 'name_ar' => 'أمن وحراسة', 'icon_file' => 'security.jpg'],
            ['name_en' => 'Parking', 'name_ar' => 'موقف سيارات', 'icon_file' => 'parking.jpg'],
            ['name_en' => 'Elevator', 'name_ar' => 'مصعد', 'icon_file' => 'elevator.jpg'],
            ['name_en' => 'Garden', 'name_ar' => 'حديقة', 'icon_file' => 'garden.jpg'],
            ['name_en' => 'Air conditioner', 'name_ar' => 'تكييف', 'icon_file' => 'airconditioner.jpg'],
            ['name_en' => 'Maid Service', 'name_ar' => 'خدمة تنظيف', 'icon_file' => 'cleaning.jpg'],
            ['name_en' => 'Maintenance Service', 'name_ar' => 'خدمة الصيانة', 'icon_file' => 'maintenance.jpg'],
            ['name_en' => 'Sea View', 'name_ar' => 'إطلالة على البحر', 'icon_file' => 'seaview.jpg'],
            ['name_en' => 'Kitchen Appliances', 'name_ar' => 'أجهزة مطبخ', 'icon_file' => 'kitchenequipments.jpg'],
        ];

        Storage::disk('public')->makeDirectory($targetDir);

        foreach ($amenities as $index => $amenity) {
            $imageName = "amenity-" . ($index + 1) . ".jpg";
            $sourceFile = base_path('images/' . $amenity['icon_file']);

            if (File::exists($sourceFile)) {
                File::copy($sourceFile, Storage::disk('public')->path($targetDir . '/' . $imageName));
            }

            \App\Models\Amenity::updateOrCreate(
                ['name_en' => $amenity['name_en']],
                [
                    'name_ar' => $amenity['name_ar'],
                    'icon' => $targetDir . '/' . $imageName
                ]
            );
        }
    }
}
