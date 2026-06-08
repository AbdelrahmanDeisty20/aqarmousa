<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateUnitDescriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed land unit types if they don't exist
        $typesData = [
            ['en' => 'Residential Land', 'ar' => 'أرض سكنية', 'icon' => 'home', 'image_file' => 'residential_land.png'],
            ['en' => 'Commercial Land', 'ar' => 'أرض تجارية', 'icon' => 'store', 'image_file' => 'commercial_land.png'],
            ['en' => 'Agricultural Land', 'ar' => 'أرض زراعية', 'icon' => 'nature', 'image_file' => 'agricultural_land.png'],
            ['en' => 'Industrial Land', 'ar' => 'أرض صناعية', 'icon' => 'construction', 'image_file' => 'industrial_land.png'],
        ];

        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('unit-types');
        foreach ($typesData as $type) {
            $sourceImage = base_path('land types/' . $type['image_file']);
            if (\Illuminate\Support\Facades\File::exists($sourceImage)) {
                \Illuminate\Support\Facades\File::copy($sourceImage, \Illuminate\Support\Facades\Storage::disk('public')->path('unit-types/' . $type['image_file']));
            }

            \App\Models\UnitType::firstOrCreate(
                ['name_en' => $type['en']],
                ['name_ar' => $type['ar']]
            );
        }

        // Clean up old unit types not in the list
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\UnitType::whereNotIn('name_en', array_column($typesData, 'en'))->delete();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 2. Clean up old amenities and seed land amenities
        $landAmenitiesData = [
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

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        $allowedNames = array_column($landAmenitiesData, 'name_en');
        $disallowedAmenities = \App\Models\Amenity::whereNotIn('name_en', $allowedNames)->get();
        foreach ($disallowedAmenities as $disallowed) {
            $disallowed->units()->detach();
            $disallowed->delete();
        }
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $targetDir = 'amenities';
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($targetDir);

        $amenityIds = [];
        foreach ($landAmenitiesData as $index => $amenity) {
            $extension = pathinfo($amenity['icon_file'], PATHINFO_EXTENSION);
            $imageName = "amenity-" . ($index + 1) . "." . $extension;
            $sourceFile = base_path('images/' . $amenity['icon_file']);

            if (\Illuminate\Support\Facades\File::exists($sourceFile)) {
                \Illuminate\Support\Facades\File::copy($sourceFile, \Illuminate\Support\Facades\Storage::disk('public')->path($targetDir . '/' . $imageName));
            }

            $dbAmenity = \App\Models\Amenity::updateOrCreate(
                ['name_en' => $amenity['name_en']],
                [
                    'name_ar' => $amenity['name_ar'],
                    'icon' => $targetDir . '/' . $imageName
                ]
            );
            $amenityIds[] = $dbAmenity->id;
        }

        // 3. Update Units
        $units = \App\Models\Unit::all();

        $descriptions = [
            [
                'ar' => "أرض سكنية مميزة في موقع استراتيجي، صالحة للبناء الفوري ومكتملة المرافق والخدمات الأساسية.",
                'en' => "Premium residential land in a strategic location, ready for immediate construction and fully serviced with essential utilities.",
                'address_ar' => "التجمع الخامس، منطقة بيت الوطن، القاهرة الجديدة",
                'address_en' => "5th Settlement, Beit El Watan Area, New Cairo",
                'development_status' => "مكتمل المرافق",
                'unit_type_name_en' => 'Residential Land',
            ],
            [
                'ar' => "أرض زراعية خصبة بمساحة واسعة وتوفر مصدر دائم لمياه الري، محاطة بسور خارجي وبوابة.",
                'en' => "Spacious fertile agricultural land with a permanent irrigation water source, surrounded by a fence and a gate.",
                'address_ar' => "طريق مصر الإسكندرية الصحراوي، الكيلو 58",
                'address_en' => "Cairo-Alexandria Desert Road, KM 58",
                'development_status' => "جاهز للزراعة",
                'unit_type_name_en' => 'Agricultural Land',
            ],
            [
                'ar' => "قطعة أرض تجارية استثمارية على واجهة شارع رئيسي حيوي، مثالية لإقامة مول أو مبنى إداري.",
                'en' => "Commercial investment plot on a vital main street front, perfect for building a mall or administrative building.",
                'address_ar' => "الشيخ زايد، المحور المركزي، بجوار هايبر وان",
                'address_en' => "Sheikh Zayed, Central Axis, Next to Hyper One",
                'development_status' => "مكتمل المرافق",
                'unit_type_name_en' => 'Commercial Land',
            ],
            [
                'ar' => "أرض صناعية مجهزة بالكامل بالبنية التحتية من كهرباء جهد عالي وصرف صحي مخصص للمصانع والورش.",
                'en' => "Industrial land fully equipped with high-voltage electricity and dedicated sewage systems for factories and workshops.",
                'address_ar' => "المنطقة الصناعية الثالثة، مدينة السادس من أكتوبر",
                'address_en' => "Third Industrial Zone, 6th of October City",
                'development_status' => "تحت التطوير",
                'unit_type_name_en' => 'Industrial Land',
            ],
            [
                'ar' => "أرض فضاء متميزة للبيع بترخيص بناء معتمد واجهة بحرية تطل على حديقة عامة.",
                'en' => "Excellent vacant land for sale with approved building permit, northern facing overlooking a public park.",
                'address_ar' => "دمياط الجديدة، منطقة الأكثر تميزاً، بجوار جامعة دمياط",
                'address_en' => "New Damietta, Most Premium Area, Near Damietta University",
                'development_status' => "جاهز للبناء",
                'unit_type_name_en' => 'Residential Land',
            ],
            [
                'ar' => "أرض سكنية في كمبوند هادئ وراقٍ، توفر خصوصية تامة لبناء فيلا مستقلة مع حديقة.",
                'en' => "Residential land in a quiet and upscale compound, offering complete privacy to build a standalone villa with a garden.",
                'address_ar' => "العبور، الحي الترفيهي، كمبوند الياسمين",
                'address_en' => "Obour, Entertainment District, Jasmine Compound",
                'development_status' => "جاهز للبناء",
                'unit_type_name_en' => 'Residential Land',
            ],
        ];

        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('units');

        foreach ($units as $index => $unit) {
            $data = $descriptions[$index % count($descriptions)];

            $unitType = \App\Models\UnitType::where('name_en', $data['unit_type_name_en'])->first();
            $governorate = $unit->governorate;

            $isSale = ($unit->offer_type === 'sale');
            
            $title_en = ($unitType ? $unitType->name_en : 'Land') . ' for ' . $unit->offer_type . ' in ' . ($governorate ? $governorate->name_en : 'Egypt');
            $title_ar = ($unitType ? $unitType->name_ar : 'أرض') . ' للـ ' . ($isSale ? 'بيع' : 'إيجار') . ' في ' . ($governorate ? $governorate->name_ar : 'مصر');

            // 1. Update unit fields
            $unit->update([
                'title_ar' => $title_ar,
                'title_en' => $title_en,
                'description_ar' => $data['ar'],
                'description_en' => $data['en'],
                'address_ar' => $data['address_ar'],
                'address_en' => $data['address_en'],
                'development_status' => $data['development_status'],
                'unit_type_id' => $unitType ? $unitType->id : $unit->unit_type_id,
                'category' => 'land',
            ]);

            // 2. Sync land amenities
            if (!empty($amenityIds)) {
                $randomAmenities = array_rand(array_flip($amenityIds), rand(4, 6));
                $unit->amenities()->sync($randomAmenities);
            }

            // 3. Recreate UnitMedia (images, floorplan, video)
            $unit->media()->delete();

            // Setup image sources
            $primaryImage = 'residential_land.png';
            if ($unitType) {
                if ($unitType->name_en === 'Commercial Land') {
                    $primaryImage = 'commercial_land.png';
                } elseif ($unitType->name_en === 'Agricultural Land') {
                    $primaryImage = 'agricultural_land.png';
                } elseif ($unitType->name_en === 'Industrial Land') {
                    $primaryImage = 'industrial_land.png';
                }
            }

            // Pool of extra images
            $extraImages = [
                'land_extra_1.png',
                'land_extra_2.png',
                'land_extra_3.png',
                'land_extra_4.png'
            ];
            // Shuffle to get a random order
            shuffle($extraImages);

            // Select 3 random extra images + 1 primary image
            $imagesToCopy = [
                $primaryImage,
                $extraImages[0],
                $extraImages[1],
                $extraImages[2]
            ];

            foreach ($imagesToCopy as $mediaIndex => $srcImageName) {
                $sourcePath = base_path('land types/' . $srcImageName);
                $destName = "unit-{$unit->id}-" . ($mediaIndex + 1) . ".png";
                $destPath = 'units/' . $destName;

                if (\Illuminate\Support\Facades\File::exists($sourcePath)) {
                    \Illuminate\Support\Facades\File::copy($sourcePath, \Illuminate\Support\Facades\Storage::disk('public')->path($destPath));
                }

                \App\Models\UnitMedia::create([
                    'unit_id' => $unit->id,
                    'type' => 'image',
                    'url' => $destPath,
                    'order' => $mediaIndex + 1,
                    'processing_status' => 'completed'
                ]);
            }

            // Floorplan
            $floorplanSource = base_path('land types/floorplan.png');
            $floorplanName = "unit-{$unit->id}-floorplan.png";
            $floorplanPath = 'units/' . $floorplanName;
            if (\Illuminate\Support\Facades\File::exists($floorplanSource)) {
                \Illuminate\Support\Facades\File::copy($floorplanSource, \Illuminate\Support\Facades\Storage::disk('public')->path($floorplanPath));
            }
            
            \App\Models\UnitMedia::create([
                'unit_id' => $unit->id,
                'type' => 'floorplan',
                'url' => $floorplanPath,
                'order' => 5,
                'processing_status' => 'completed'
            ]);

            // Video (distribution between land_video_1 and land_video_2)
            $videoFile = ($unit->id % 2 === 0) ? 'land_video_1.mp4' : 'land_video_2.mp4';
            $videoSource = base_path('land types/' . $videoFile);
            $unitVideoName = "unit-{$unit->id}-video.mp4";
            $videoPath = 'units/' . $unitVideoName;
            if (\Illuminate\Support\Facades\File::exists($videoSource)) {
                \Illuminate\Support\Facades\File::copy($videoSource, \Illuminate\Support\Facades\Storage::disk('public')->path($videoPath));
            }

            \App\Models\UnitMedia::create([
                'unit_id' => $unit->id,
                'type' => 'video',
                'url' => $videoPath,
                'order' => 6,
                'processing_status' => 'completed',
            ]);
        }
    }
}
