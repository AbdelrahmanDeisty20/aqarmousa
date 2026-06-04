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
            ['en' => 'Residential Land', 'ar' => 'أرض سكنية', 'icon' => 'home', 'image_file' => 'villa.jpg'],
            ['en' => 'Commercial Land', 'ar' => 'أرض تجارية', 'icon' => 'store', 'image_file' => 'shop.jpg'],
            ['en' => 'Agricultural Land', 'ar' => 'أرض زراعية', 'icon' => 'nature', 'image_file' => 'chalet.jpg'],
            ['en' => 'Industrial Land', 'ar' => 'أرض صناعية', 'icon' => 'construction', 'image_file' => 'studio.jpg'],
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
            $imageName = "amenity-" . ($index + 1) . ".jpg";
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

            $imageFile = 'villa.jpg';
            if ($unitType) {
                if ($unitType->name_en === 'Commercial Land') {
                    $imageFile = 'shop.jpg';
                } elseif ($unitType->name_en === 'Agricultural Land') {
                    $imageFile = 'chalet.jpg';
                } elseif ($unitType->name_en === 'Industrial Land') {
                    $imageFile = 'studio.jpg';
                }
            }
            $unitTypeSource = base_path('land types/' . $imageFile);
            
            $sharedTypeImages = [];
            for ($m = 1; $m <= 3; $m++) {
                $imageName = "demo-type-" . ($unitType ? $unitType->id : 'default') . "-{$m}.jpg";
                $targetPath = 'units/' . $imageName;
                if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($targetPath) && \Illuminate\Support\Facades\File::exists($unitTypeSource)) {
                    \Illuminate\Support\Facades\File::copy($unitTypeSource, \Illuminate\Support\Facades\Storage::disk('public')->path($targetPath));
                }
                $sharedTypeImages[] = $targetPath;
            }

            foreach ($sharedTypeImages as $mediaIndex => $imageUrl) {
                \App\Models\UnitMedia::create([
                    'unit_id' => $unit->id,
                    'type' => 'image',
                    'url' => $imageUrl,
                    'order' => $mediaIndex + 1,
                    'processing_status' => 'completed'
                ]);
            }

            // Floorplan
            $floorplanSource = base_path('land types/floorplan.png');
            $floorplanName = "demo-floorplan.png";
            $floorplanPath = 'units/' . $floorplanName;
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($floorplanPath) && \Illuminate\Support\Facades\File::exists($floorplanSource)) {
                \Illuminate\Support\Facades\File::copy($floorplanSource, \Illuminate\Support\Facades\Storage::disk('public')->path($floorplanPath));
            }
            
            \App\Models\UnitMedia::create([
                'unit_id' => $unit->id,
                'type' => 'floorplan',
                'url' => $floorplanPath,
                'order' => 4,
                'processing_status' => 'completed'
            ]);

            // Video
            $videoSource = base_path('land types/videoplayback.mp4');
            $unitVideoName = "demo-unit-video.mp4";
            $videoPath = 'units/' . $unitVideoName;
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($videoPath) && \Illuminate\Support\Facades\File::exists($videoSource)) {
                \Illuminate\Support\Facades\File::copy($videoSource, \Illuminate\Support\Facades\Storage::disk('public')->path($videoPath));
            }

            \App\Models\UnitMedia::create([
                'unit_id' => $unit->id,
                'type' => 'video',
                'url' => $videoPath,
                'order' => 5,
                'processing_status' => 'completed',
            ]);
        }
    }
}
