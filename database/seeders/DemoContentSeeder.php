<?php

namespace Database\Seeders;

use App\Models\Governorate;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\User;
use App\Models\UnitMedia;
use App\Models\Ownership;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Favorite::truncate();
        \App\Models\Review::truncate();
        \App\Models\Viewing::truncate();
        \App\Models\UnitMedia::truncate();
        \App\Models\Ownership::truncate();
        \App\Models\Unit::truncate();
        \App\Models\Compound::truncate();
        \App\Models\Developer::truncate();
        \App\Models\UnitType::truncate();
        \App\Models\Governorate::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $fakerAr = Faker::create('ar_EG');
        $fakerEn = Faker::create('en_US');

        // Seed Governorates & Amenities
        $this->call(GovernorateSeeder::class);
        $this->call(AmenitySeeder::class);

        $amenityIds = \App\Models\Amenity::pluck('id')->toArray();

        // Arabic descriptions tailored specifically for LANDS
        $arabicLandDescriptions = [
            'قطعة أرض سكنية ممتازة بموقع استراتيجي على واجهة بحرية، صالحة للبناء الفوري وتتميز بقربها من جميع الخدمات والمحاور الرئيسية.',
            'أرض تجارية استثمارية ذات موقع حيوي على شارع رئيسي مزدوج، مثالية لإقامة مول تجاري أو مبنى إداري بمساحة واجهة عريضة.',
            'أرض زراعية خصبة ومستوية تمتاز بتوفر مياه ري دائمة وتربة ممتازة لجميع أنواع الزراعات، محاطة بسور حماية وبوابة خاصة.',
            'قطعة أرض صناعية مجهزة ببنية تحتية متكاملة تشمل خط كهرباء جهد عالي وصرف صناعي وطريق ممهد للشاحنات.',
            'أرض ساحلية استثنائية تطل مباشرة على البحر، موقع فاخر لإقامة منتجع أو شاليهات سكنية بمساحة واسعة وترخيص معتمد.',
            'أرض استثمارية مميزة واجهة قبلية على حديقة مركزية، صالحة لإقامة مشروع متعدد الأغراض بحصص مسجلة بالشهر العقاري.',
            'أرض خدمية مخصصة للأنشطة التعليمية أو الطبية بموقع ممتاز وسهولة الوصول من الطريق السريع.',
            'أرض فضاء بتخطيط هندسي منتظم وطول واجهة ممتاز، مكتملة المرافق (كهرباء، مياه، غاز، صرف) وجاهزة للترخيص مباشرة.'
        ];

        $englishLandDescriptions = [
            'Prime residential plot in a strategic location with northern orientation, ready for immediate construction with full utility connections.',
            'Commercial investment land located on a busy dual main street, perfect for developing a shopping mall or administrative complex.',
            'Fertile agricultural land with permanent irrigation water supply, level ground, and secured perimeter fencing with gate.',
            'Industrial plot fully serviced with high-voltage electricity grid, industrial sewage lines, and paved heavy-truck access roads.',
            'Coastal seafront land with stunning sea view, ideal for a luxury resort or private beach subdivision with approved master plan.',
            'Premium investment plot facing a central park, highly suitable for mixed-use development with clear registered ownership title.',
            'Service-designated land for educational or medical projects in an accessible prime sector.',
            'Vacant plot with regular geometric layout and generous street frontage, completely equipped with water, electricity, gas, and sewage.'
        ];

        $arabicCompoundDescriptions = [
            'مخطط أرضي متكامل المزايا يوفر شوارع متسعة ومساحات خضراء ومرافق متطورة لبناء أرقى المجتمعات السكنية والتجارية.',
            'مشروع تقسيم أراضي استثماري فاخر بتصميم عمرانى عالمي يضم قطع أراضي متنوعة المساحات مع شوارع ممهدة وأمن على مدار الساعة.',
            'مخطط أراضي زراعية وسكنية راقي يجمع بين الطبيعة الخلابة والبنية التحتية الحديثة من شبكات ري وكهرباء وطرق أسفلتية.',
            'مجمع أراضي استثمارية بقلب المحافظة يتيح فرصة استثنائية للمطورين والمستثمرين لبناء مشروعاتهم الخاصة بنسب بنائية ممتازة.',
        ];

        // Ensure super_admin role exists
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // 1. Create Unit Types for LANDS ONLY
        $typesData = [
            ['en' => 'Residential Land', 'ar' => 'أرض سكنية', 'icon' => 'home', 'image_file' => 'residential_land.png'],
            ['en' => 'Commercial Land', 'ar' => 'أرض تجارية', 'icon' => 'store', 'image_file' => 'commercial_land.png'],
            ['en' => 'Agricultural Land', 'ar' => 'أرض زراعية', 'icon' => 'nature', 'image_file' => 'agricultural_land.png'],
            ['en' => 'Industrial Land', 'ar' => 'أرض صناعية', 'icon' => 'construction', 'image_file' => 'industrial_land.png'],
            ['en' => 'Coastal Land', 'ar' => 'أرض ساحلية', 'icon' => 'beach_access', 'image_file' => 'land_extra_1.png'],
            ['en' => 'Investment Land', 'ar' => 'أرض استثمارية', 'icon' => 'trending_up', 'image_file' => 'land_extra_2.png'],
            ['en' => 'Service Land', 'ar' => 'أرض خدمية', 'icon' => 'business', 'image_file' => 'land_extra_3.png'],
        ];

        $unitTypes = [];
        Storage::disk('public')->makeDirectory('unit-types');
        foreach ($typesData as $type) {
            $sourceImage = base_path('land types/' . $type['image_file']);
            if (File::exists($sourceImage)) {
                File::copy($sourceImage, Storage::disk('public')->path('unit-types/' . $type['image_file']));
            }

            $unitTypes[] = UnitType::firstOrCreate(
                ['name_en' => $type['en']],
                ['name_ar' => $type['ar']]
            );
        }

        // 2. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '01000000000',
                'status' => 'approved',
            ]
        );

        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        // 3. Create Land Developers / Master Planners
        $developersData = [
            ['name_en' => 'Emaar Land Developments', 'name_ar' => 'إعمار لتطوير الأراضي', 'logo' => 'https://images.unsplash.com/photo-1560179707-f14e90ef3623?q=80&w=400&auto=format&fit=crop'],
            ['name_en' => 'SODIC Master Plans', 'name_ar' => 'سوديك للمخططات العقارية', 'logo' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=400&auto=format&fit=crop'],
            ['name_en' => 'Palm Hills Lands', 'name_ar' => 'بالم هيلز للأراضي', 'logo' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=400&auto=format&fit=crop'],
            ['name_en' => 'Mountain View Subdivisions', 'name_ar' => 'ماونتن فيو لتقسيم الأراضي', 'logo' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?q=80&w=400&auto=format&fit=crop'],
            ['name_en' => 'Talaat Moustafa Land Group', 'name_ar' => 'مجموعة طلعت مصطفى للأراضي', 'logo' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?q=80&w=400&auto=format&fit=crop'],
            ['name_en' => 'Misr Italia Land Ventures', 'name_ar' => 'مصر إيطاليا للاستثمار العقاري والأراضي', 'logo' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=400&auto=format&fit=crop'],
        ];

        $developers = [];
        foreach ($developersData as $devData) {
            $developers[] = Developer::firstOrCreate(
                ['name_en' => $devData['name_en']],
                [
                    'name_ar' => $devData['name_ar'],
                    'email' => str_replace(' ', '', strtolower($devData['name_en'])) . '@contact.com',
                    'phone' => $fakerAr->phoneNumber,
                    'address' => $fakerEn->address,
                    'status' => 'active',
                    'logo' => $devData['logo']
                ]
            );
        }

        // 4. Create Compounds (Land Subdivisions / Master-planned Land Projects)
        $compoundsData = [
            ['name_en' => 'Marassi Land Subdivision', 'name_ar' => 'مخطط أرض مراسي الساحلي', 'governorate' => 'North Coast', 'dev' => 'Emaar Land Developments'],
            ['name_en' => 'Mivida Land Estates', 'name_ar' => 'مخطط أرض ميفيدا السكني', 'governorate' => 'New Cairo', 'dev' => 'Emaar Land Developments'],
            ['name_en' => 'SODIC West Plots', 'name_ar' => 'مخطط قطع أراضي سوديك ويست', 'governorate' => 'Sheikh Zayed', 'dev' => 'SODIC Master Plans'],
            ['name_en' => 'IL Monte Galala Land Heights', 'name_ar' => 'أراضي مرتفعات المونت جلالة', 'governorate' => 'Ain Sokhna', 'dev' => 'Palm Hills Lands'],
            ['name_en' => 'Noor Land Subdivision', 'name_ar' => 'مخطط أراضي مدينة نور', 'governorate' => 'New Capital', 'dev' => 'Talaat Moustafa Land Group'],
            ['name_en' => 'Badya Land Masterplan', 'name_ar' => 'مخطط أراضي بادية أكتوبر', 'governorate' => '6th of October', 'dev' => 'Palm Hills Lands'],
            ['name_en' => 'Mansoura Land District', 'name_ar' => 'مخطط حدائق الأراضي بالمنصورة', 'governorate' => 'Mansoura', 'dev' => 'Mountain View Subdivisions'],
        ];

        $governorateCoordinates = [
            'Cairo' => ['lat' => 30.0444, 'lng' => 31.2357],
            'Giza' => ['lat' => 30.0131, 'lng' => 31.2089],
            'Alexandria' => ['lat' => 31.2001, 'lng' => 29.9187],
            'North Coast' => ['lat' => 30.8481, 'lng' => 29.0547],
            'Ain Sokhna' => ['lat' => 29.6000, 'lng' => 32.3500],
            'New Capital' => ['lat' => 30.0258, 'lng' => 31.7310],
            '6th of October' => ['lat' => 29.9668, 'lng' => 30.9290],
            'Sheikh Zayed' => ['lat' => 30.0181, 'lng' => 30.9714],
            'New Cairo' => ['lat' => 30.0330, 'lng' => 31.4913],
            'Mansoura' => ['lat' => 31.0409, 'lng' => 31.3785],
        ];

        $compounds = [];
        foreach ($compoundsData as $comp) {
            $governorate = Governorate::where('name_en', $comp['governorate'])->first();
            $dev = Developer::where('name_en', $comp['dev'])->first();

            if ($governorate && $dev) {
                $compounds[] = Compound::firstOrCreate(
                    ['name_en' => $comp['name_en']],
                    [
                        'name_ar' => $comp['name_ar'],
                        'description_en' => $fakerEn->paragraph,
                        'description_ar' => $arabicCompoundDescriptions[array_rand($arabicCompoundDescriptions)],
                        'governorate_id' => $governorate->id,
                    ]
                );
            }
        }

        // Online HD Land Images pool
        $onlineLandImages = [
            'Residential Land' => [
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1511497584788-8767611136f6?q=80&w=1200&auto=format&fit=crop'
            ],
            'Commercial Land' => [
                'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=1200&auto=format&fit=crop'
            ],
            'Agricultural Land' => [
                'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1448375240586-882707db888b?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1426604966848-d7adac402bff?q=80&w=1200&auto=format&fit=crop'
            ],
            'Industrial Land' => [
                'https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop'
            ],
            'Coastal Land' => [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1519046904884-53103b34b206?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?q=80&w=1200&auto=format&fit=crop'
            ],
            'Investment Land' => [
                'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1511497584788-8767611136f6?q=80&w=1200&auto=format&fit=crop'
            ],
            'Service Land' => [
                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?q=80&w=1200&auto=format&fit=crop'
            ]
        ];

        // 5. Create LAND Units
        $offerTypes = ['sale', 'rent'];
        $statuses = ['available', 'available', 'available', 'available', 'sold', 'reserved'];
        $developmentStatuses = ['مكتمل المرافق', 'جاهز للبناء', 'جاهز للزراعة', 'تحت التطوير', 'مرافق جزئية'];

        $governorates = Governorate::all();

        foreach ($governorates as $governorate) {
            $numUnits = 4; // 4 lands per governorate

            for ($k = 0; $k < $numUnits; $k++) {
                $type = $unitTypes[array_rand($unitTypes)];

                $length = $fakerEn->numberBetween(20, 100);
                $width = $fakerEn->numberBetween(15, 80);
                $area = $length * $width;

                $pricePerM2 = $fakerEn->numberBetween(1500, 25000);
                $price = $area * $pricePerM2;
                $discount = (rand(0, 4) === 0) ? $fakerEn->numberBetween(50000, 300000) : null;

                $selectedOfferType = $offerTypes[array_rand($offerTypes)];
                $selectedStatus = $statuses[array_rand($statuses)];
                $isSale = ($selectedOfferType === 'sale');
                $devStatus = $developmentStatuses[array_rand($developmentStatuses)];

                $descIndex = rand(0, count($arabicLandDescriptions) - 1);

                // Select a compound if in same governorate or null
                $matchedCompound = Compound::where('governorate_id', $governorate->id)->inRandomOrder()->first();

                $unit = Unit::create([
                    'title_en' => $type->name_en . ' for ' . ($isSale ? 'Sale' : 'Rent') . ' in ' . $governorate->name_en,
                    'title_ar' => $type->name_ar . ' للـ ' . ($isSale ? 'بيع' : 'إيجار') . ' في ' . $governorate->name_ar,
                    'description_en' => $englishLandDescriptions[$descIndex],
                    'description_ar' => $arabicLandDescriptions[$descIndex],
                    'address_ar' => $governorate->name_ar . '، القطاع ' . $fakerAr->cityPrefix . ' - قطعة ' . rand(10, 500),
                    'address_en' => $governorate->name_en . ', Sector ' . rand(1, 20) . ' - Plot ' . rand(10, 500),
                    'price' => $price,
                    'discount' => $discount,
                    'price_per_m2' => $pricePerM2,
                    'offer_type' => $selectedOfferType,
                    'area' => $area,
                    'length' => $length,
                    'width' => $width,
                    'category' => 'land', // STRICTLY LAND
                    'rooms' => null,
                    'bathrooms' => null,
                    'garages' => null,
                    'build_year' => null,
                    'internal_area' => null,
                    'land_area' => $area,
                    'development_status' => $devStatus,
                    'status' => $selectedStatus,
                    'is_visible' => true,
                    'owner_id' => $admin->id,
                    'governorate_id' => $governorate->id,
                    'unit_type_id' => $type->id,
                    'compound_id' => $matchedCompound ? $matchedCompound->id : null,
                    'developer_id' => $matchedCompound ? $matchedCompound->developer_id : null,
                    'latitude' => ($governorateCoordinates[$governorate->name_en] ?? ['lat' => 30.0444, 'lng' => 31.2357])['lat'] + (rand(-150, 150) / 10000),
                    'longitude' => ($governorateCoordinates[$governorate->name_en] ?? ['lat' => 30.0444, 'lng' => 31.2357])['lng'] + (rand(-150, 150) / 10000),
                    'sold_at' => ($selectedStatus === 'sold') ? now()->subDays(rand(1, 30)) : null,
                    'reserved_at' => ($selectedStatus === 'reserved') ? now()->subDays(rand(1, 10)) : null,
                ]);

                // Create Land Ownership details
                $unit->ownership()->create([
                    'contract_type' => ['سند ملكية مسجل (شهر عقاري)', 'عقد بيع ابتدائي ومخصص', 'عقد نهائي صحة ونفاذ', 'عقد حيازة زراعية مسجلة'][rand(0, 3)],
                    'is_registered' => (rand(0, 1) === 1),
                    'plot_number' => 'قطعة أرض رقم ' . rand(101, 999) . ' - حوض ' . rand(1, 15),
                ]);

                // Attach Land Amenities
                if (!empty($amenityIds)) {
                    $randomAmenities = array_rand(array_flip($amenityIds), rand(4, 7));
                    $unit->amenities()->attach($randomAmenities);
                }

                // Attach High-Res Online HTTP Images
                $imagesPool = $onlineLandImages[$type->name_en] ?? $onlineLandImages['Residential Land'];

                foreach ($imagesPool as $mediaIndex => $imageUrl) {
                    UnitMedia::create([
                        'unit_id' => $unit->id,
                        'type' => 'image',
                        'url' => $imageUrl,
                        'order' => $mediaIndex + 1,
                        'processing_status' => 'completed'
                    ]);
                }

                // Add Floorplan / Plot layout blueprint online image URL
                UnitMedia::create([
                    'unit_id' => $unit->id,
                    'type' => 'floorplan',
                    'url' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1200&auto=format&fit=crop',
                    'order' => 5,
                    'processing_status' => 'completed'
                ]);

                // Add Video URL
                $videoUrl = ($unit->id % 2 === 0)
                    ? 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4'
                    : 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4';

                UnitMedia::create([
                    'unit_id' => $unit->id,
                    'type' => 'video',
                    'url' => $videoUrl,
                    'order' => 6,
                    'processing_status' => 'completed'
                ]);
            }
        }
    }
}
