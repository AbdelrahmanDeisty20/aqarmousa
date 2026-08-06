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
            ['name_en' => 'Water Network', 'name_ar' => 'شبكة مياه', 'icon_file' => 'water_pipe_icon.png', 'url' => 'https://images.unsplash.com/photo-1548839140-29a749e1cf4e?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Agricultural Irrigation', 'name_ar' => 'مياه ري', 'icon_file' => 'irrigation_icon.png', 'url' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Security', 'name_ar' => 'أمن وحراسة', 'icon_file' => 'security_gate_icon.png', 'url' => 'https://images.unsplash.com/photo-1557597774-9d273605dfa9?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Paved Road', 'name_ar' => 'طريق ممهد', 'icon_file' => 'paved_road_icon.png', 'url' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Building Permit', 'name_ar' => 'ترخيص بناء', 'icon_file' => 'blueprint_icon.png', 'url' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Fenced Land', 'name_ar' => 'سور محيط', 'icon_file' => 'fence_icon.png', 'url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Electricity', 'name_ar' => 'كهرباء', 'icon_file' => 'electricity_tower_icon.png', 'url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Sewage Network', 'name_ar' => 'شبكة صرف صحي', 'icon_file' => 'drainage_icon.png', 'url' => 'https://images.unsplash.com/photo-1542013936693-884638332954?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Natural Gas', 'name_ar' => 'غاز طبيعي', 'icon_file' => 'gas_pipeline_icon.png', 'url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=300&auto=format&fit=crop'],
            ['name_en' => 'Sea View', 'name_ar' => 'إطلالة على البحر', 'icon_file' => 'seaview.jpg', 'url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=300&auto=format&fit=crop'],
        ];

        Storage::disk('public')->makeDirectory($targetDir);

        foreach ($amenities as $index => $amenity) {
            $extension = pathinfo($amenity['icon_file'], PATHINFO_EXTENSION);
            $imageName = "amenity-" . ($index + 1) . "." . $extension;
            $sourceFile = base_path('images/' . $amenity['icon_file']);

            $iconPath = $amenity['url'];
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, Storage::disk('public')->path($targetDir . '/' . $imageName));
            }

            \App\Models\Amenity::updateOrCreate(
                ['name_en' => $amenity['name_en']],
                [
                    'name_ar' => $amenity['name_ar'],
                    'icon' => $iconPath
                ]
            );
        }
    }
}
