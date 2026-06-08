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
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('amenity_unit')->truncate();
        \App\Models\Amenity::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $targetDir = 'amenities';
        $amenities = [
            ['name_en' => 'Water Network', 'name_ar' => 'شبكة مياه', 'icon_file' => 'water_pipe_icon.png'],
            ['name_en' => 'Agricultural Irrigation', 'name_ar' => 'مياه ري', 'icon_file' => 'irrigation_icon.png'],
            ['name_en' => 'Security', 'name_ar' => 'أمن وحراسة', 'icon_file' => 'security_gate_icon.png'],
            ['name_en' => 'Paved Road', 'name_ar' => 'طريق ممهد', 'icon_file' => 'paved_road_icon.png'],
            ['name_en' => 'Building Permit', 'name_ar' => 'ترخيص بناء', 'icon_file' => 'blueprint_icon.png'],
            ['name_en' => 'Fenced Land', 'name_ar' => 'سور محيط', 'icon_file' => 'fence_icon.png'],
            ['name_en' => 'Electricity', 'name_ar' => 'كهرباء', 'icon_file' => 'electricity_tower_icon.png'],
            ['name_en' => 'Sewage Network', 'name_ar' => 'شبكة صرف صحي', 'icon_file' => 'drainage_icon.png'],
            ['name_en' => 'Natural Gas', 'name_ar' => 'غاز طبيعي', 'icon_file' => 'gas_pipeline_icon.png'],
            ['name_en' => 'Sea View', 'name_ar' => 'إطلالة على البحر', 'icon_file' => 'seaview.jpg'],
        ];

        Storage::disk('public')->makeDirectory($targetDir);

        foreach ($amenities as $index => $amenity) {
            $extension = pathinfo($amenity['icon_file'], PATHINFO_EXTENSION);
            $imageName = "amenity-" . ($index + 1) . "." . $extension;
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
