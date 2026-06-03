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
            ['name_en' => 'Water Network', 'name_ar' => 'شبكة مياه', 'icon_file' => 'pool.jpg'],
            ['name_en' => 'Agricultural Irrigation', 'name_ar' => 'مياه ري', 'icon_file' => 'garden.jpg'],
            ['name_en' => 'Security', 'name_ar' => 'أمن وحراسة', 'icon_file' => 'security.jpg'],
            ['name_en' => 'Paved Road', 'name_ar' => 'طريق ممهد', 'icon_file' => 'parking.jpg'],
            ['name_en' => 'Building Permit', 'name_ar' => 'ترخيص بناء', 'icon_file' => 'elevator.jpg'],
            ['name_en' => 'Fenced Land', 'name_ar' => 'سور محيط', 'icon_file' => 'gardens.jpg'],
            ['name_en' => 'Electricity', 'name_ar' => 'كهرباء', 'icon_file' => 'airconditioner.jpg'],
            ['name_en' => 'Sewage Network', 'name_ar' => 'شبكة صرف صحي', 'icon_file' => 'maintenance.jpg'],
            ['name_en' => 'Natural Gas', 'name_ar' => 'غاز طبيعي', 'icon_file' => 'kitchenequipments.jpg'],
            ['name_en' => 'Sea View', 'name_ar' => 'إطلالة على البحر', 'icon_file' => 'seaview.jpg'],
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
