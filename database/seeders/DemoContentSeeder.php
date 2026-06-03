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

        $amenityIds = \App\Models\Amenity::pluck('id')->toArray();

        // Arabic descriptions for units and compounds
        $arabicDescriptions = [
            'أرض مميزة بموقع استراتيجي وإطلالة رائعة على مساحات خضراء. صالحة للبناء السكني ومكتملة المرافق.',
            'أرض فضاء متميزة قريبة من جميع الخدمات والمرافق الحيوية في منطقة راقية وهادئة.',
            'قطعة أرض استثنائية توفر أعلى مستويات الخصوصية بموقع حيوي ومثالي للاستثمار.',
            'أرض تجارية وسكنية بموقع متميز يوفر سهولة الوصول لأهم الطرق والمناطق الحيوية بالمحافظة.',
            'أرض مميزة بتصريح بناء سوبر لوكس وبنية تحتية كاملة من مياه وكهرباء وغاز طبيعي.',
            'أرض فضاء محاطة بالكمبوندات الراقية والمناطق الخضراء، واجهة بحرية وتخطيط معماري ممتاز.',
            'أرض فضاء بموقع مميز وتصميم مستطيل يسهل البناء عليه، قريبة من محور الخدمة الرئيسي.',
            'أرض تطل على حديقة مركزية مباشرة وتتمتع بواجهة ممتازة في أرقى أحياء المحافظة.',
        ];

        $arabicCompoundDescriptions = [
            'كمبوند سكني متكامل يوفر أرقى مستويات المعيشة مع مساحات خضراء واسعة ومرافق ترفيهية متنوعة. موقع استراتيجي يجمع بين الهدوء والقرب من المحافظة.',
            'مشروع سكني فاخر بتصميم معماري عالمي يضم وحدات متنوعة تناسب جميع الاحتياجات. يتميز بالأمن والخصوصية والخدمات المتكاملة.',
            'مجتمع سكني راقي يجمع بين الطبيعة الخلابة والحياة العصرية. يحتوي على نوادي رياضية، مدارس دولية، ومراكز تجارية متكاملة.',
            'كمبوند حديث يوفر نمط حياة عصري ومتكامل مع جميع الخدمات والمرافق على أعلى مستوى. تصميمات معمارية فريدة ومساحات خضراء واسعة.',
            'مشروع سكني متميز في قلب المحافظة يجمع بين الموقع الاستراتيجي والتصميم العصري. يوفر بيئة آمنة ومريحة للعائلات.',
        ];

        // Ensure super_admin role exists
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $villaImage = 'modern-luxury-house-with-swimming-pool.jpg';
        $villaSource = base_path('images/' . $villaImage); // Corrected source path
        $videoFile = 'videoplayback.mp4';
        $videoSource = base_path('images/' . $videoFile);

        // 1. Create Governorates
        $governoratesData = [
            ['en' => 'Cairo', 'ar' => 'القاهرة'],
            ['en' => 'Giza', 'ar' => 'الجيزة'],
            ['en' => 'Alexandria', 'ar' => 'الإسكندرية'],
            ['en' => 'North Coast', 'ar' => 'الساحل الشمالي'],
            ['en' => 'Ain Sokhna', 'ar' => 'العين السخنة'],
            ['en' => 'New Capital', 'ar' => 'العاصمة الإدارية الجديدة'],
            ['en' => '6th of October', 'ar' => 'السادس من أكتوبر'],
            ['en' => 'Sheikh Zayed', 'ar' => 'الشيخ زايد'],
            ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'],
            ['en' => 'Mansoura', 'ar' => 'المنصورة'],
        ];

        $governorates = [];
        foreach ($governoratesData as $governorate) {
            $governorates[] = Governorate::firstOrCreate(
                ['name_en' => $governorate['en']],
                ['name_ar' => $governorate['ar']]
            );
        }

        // 2. Create Unit Types
        $typesData = [
            ['en' => 'Residential Unit', 'ar' => 'أرض سكنية', 'icon' => 'home', 'image_file' => 'villa.jpg'],
            ['en' => 'Commercial Unit', 'ar' => 'أرض تجارية', 'icon' => 'store', 'image_file' => 'shop.jpg'],
            ['en' => 'Agricultural Unit', 'ar' => 'أرض زراعية', 'icon' => 'nature', 'image_file' => 'chalet.jpg'],
            ['en' => 'Industrial Unit', 'ar' => 'أرض صناعية', 'icon' => 'construction', 'image_file' => 'studio.jpg'],
        ];

        $unitTypes = [];
        Storage::disk('public')->makeDirectory('unit-types');
        foreach ($typesData as $type) {
            $sourceImage = base_path('unit types/' . $type['image_file']);
            if (File::exists($sourceImage)) {
                File::copy($sourceImage, Storage::disk('public')->path('unit-types/' . $type['image_file']));
            }

            $unitTypes[] = UnitType::firstOrCreate(
                ['name_en' => $type['en']],
                ['name_ar' => $type['ar']]
            );
        }

        // 3. Create Users
        $usersToCreate = [
            [
                'email' => 'admin@admin.com',
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '01000000000',
                'status' => 'approved',
            ],
        ];

        $admin = null;
        foreach ($usersToCreate as $u) {
            $user = User::firstOrCreate(['email' => $u['email']], $u);

            // Assign super_admin role if not already assigned
            if ($u['role'] === 'admin' && !$user->hasRole('super_admin')) {
                $user->assignRole('super_admin');
            }

            if ($u['email'] === 'admin@admin.com') {
                $admin = $user;
            }
        }

        // 4. Create Developers
        $developersData = [
            ['name_en' => 'Emaar Misr', 'name_ar' => 'إعمار مصر'],
            ['name_en' => 'SODIC', 'name_ar' => 'سوديك'],
            ['name_en' => 'Palm Hills', 'name_ar' => 'بالم هيلز'],
            ['name_en' => 'Mountain View', 'name_ar' => 'ماونتن فيو'],
            ['name_en' => 'Orascom Development', 'name_ar' => 'أوراسكوم للتنمية'],
            ['name_en' => 'Talaat Moustafa Group (TMG)', 'name_ar' => 'مجموعة طلعت مصطفى'],
            ['name_en' => 'Hyde Park', 'name_ar' => 'هايد بارك'],
            ['name_en' => 'Tatweer Misr', 'name_ar' => 'تطوير مصر'],
            ['name_en' => 'Misr Italia', 'name_ar' => 'مصر إيطاليا'],
        ];

        $developers = [];
        Storage::disk('public')->makeDirectory('developers');
        foreach ($developersData as $devData) {
            $logoSource = base_path('images/developers.jpg');
            if (File::exists($logoSource)) {
                File::copy($logoSource, Storage::disk('public')->path('developers/developers.jpg'));
            }

            $developers[] = Developer::firstOrCreate(
                ['name_en' => $devData['name_en']],
                [
                    'name_ar' => $devData['name_ar'],
                    'email' => str_replace(' ', '', strtolower($devData['name_en'])) . '@contact.com',
                    'phone' => $fakerAr->phoneNumber,
                    'address' => $fakerEn->address,
                    'status' => 'active',
                    'logo' => 'developers/developers.jpg'
                ]
            );
        }

        // 5. Create Compounds
        $compoundsData = [
            ['name_en' => 'Marassi', 'name_ar' => 'مراسي', 'governorate' => 'North Coast', 'dev' => 'Emaar Misr'],
            ['name_en' => 'Mivida', 'name_ar' => 'ميفيدا', 'governorate' => 'New Cairo', 'dev' => 'Emaar Misr'],
            ['name_en' => 'SODIC West', 'name_ar' => 'سوديك ويست', 'governorate' => 'Sheikh Zayed', 'dev' => 'SODIC'],
            ['name_en' => 'IL Monte Galala', 'name_ar' => 'المونت جلالة', 'governorate' => 'Ain Sokhna', 'dev' => 'Tatweer Misr'],
            ['name_en' => 'Palm Hills Alexandria', 'name_ar' => 'بالم هيلز الإسكندرية', 'governorate' => 'Alexandria', 'dev' => 'Palm Hills'],
            ['name_en' => 'Noor Governorate', 'name_ar' => 'نور سيتي', 'governorate' => 'New Capital', 'dev' => 'Talaat Moustafa Group (TMG)'],
            ['name_en' => 'Badya', 'name_ar' => 'بادية', 'governorate' => '6th of October', 'dev' => 'Palm Hills'],
            ['name_en' => 'Heliopolis Gardens', 'name_ar' => 'حدائق مصر الجديدة', 'governorate' => 'Cairo', 'dev' => 'Emaar Misr'],
            ['name_en' => 'Pyramids Heights', 'name_ar' => 'مرتفعات الأهرام', 'governorate' => 'Giza', 'dev' => 'SODIC'],
            ['name_en' => 'Mansoura Gardens', 'name_ar' => 'حدائق المنصورة', 'governorate' => 'Mansoura', 'dev' => 'Mountain View'],
        ];

        $compounds = [];

        // Real coordinates for each governorate in Egypt
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

        // 6. Create Units
        $offerTypes = ['sale', 'rent'];
        $statuses = ['available', 'available', 'available', 'available', 'sold', 'reserved'];

        Storage::disk('public')->makeDirectory('units');
        $floorplanSource = base_path('unit types/floorplan.png');
        $videoSource = base_path('unit types/videoplayback.mp4');

        foreach ($compounds as $compound) {
            $numUnits = 7;

            for ($k = 0; $k < $numUnits; $k++) {
                $seller = $admin;
                $type = $unitTypes[array_rand($unitTypes)];
                $dev = $developers[array_rand($developers)];

                $price = $fakerEn->numberBetween(1000000, 15000000);
                $discount = (rand(0, 4) === 0) ? $fakerEn->numberBetween(50000, 500000) : null;

                $selectedOfferType = $offerTypes[array_rand($offerTypes)];
                $selectedStatus = $statuses[array_rand($statuses)];
                $isSale = ($selectedOfferType == 'sale');

                $category = (rand(0, 1) === 0) ? 'land' : 'property';

                if ($category === 'land') {
                    $length = $fakerEn->numberBetween(20, 80);
                    $width = $fakerEn->numberBetween(15, 60);
                    $area = $length * $width;
                    $rooms = null;
                    $bathrooms = null;
                    $garages = null;
                    $buildYear = null;
                    $internalArea = null;
                    $landArea = null;
                    $devStatus = null;
                } else {
                    $length = null;
                    $width = null;
                    $area = $fakerEn->numberBetween(80, 400);
                    $rooms = $fakerEn->numberBetween(2, 6);
                    $bathrooms = $fakerEn->numberBetween(1, 4);
                    $garages = $fakerEn->numberBetween(0, 2);
                    $buildYear = $fakerEn->numberBetween(2010, 2024);
                    $internalArea = $area - $fakerEn->numberBetween(10, 50);
                    $landArea = $area + $fakerEn->numberBetween(20, 100);
                    $devStatus = ['under_construction', 'ready', 'handover_soon', 'primary', 'resale'][rand(0, 4)];
                }

                $unit = Unit::create([
                    'title_en' => ($category === 'land' ? 'Land ' : 'Property ') . $type->name_en . ' for ' . $selectedOfferType . ' in ' . $compound->name_en,
                    'title_ar' => ($category === 'land' ? 'أرض ' : 'عقار ') . $type->name_ar . ' للـ ' . ($isSale ? 'بيع' : 'إيجار') . ' في ' . $compound->name_ar,
                    'description_en' => $fakerEn->realText(200),
                    'description_ar' => $arabicDescriptions[array_rand($arabicDescriptions)],
                    'address_ar' => $fakerAr->address,
                    'address_en' => $fakerEn->address,
                    'price' => $price,
                    'discount' => $discount,
                    'price_per_m2' => $price / $area,
                    'offer_type' => $selectedOfferType,
                    'area' => $area,
                    'length' => $length,
                    'width' => $width,
                    'category' => $category,
                    'rooms' => $rooms,
                    'bathrooms' => $bathrooms,
                    'garages' => $garages,
                    'build_year' => $buildYear,
                    'internal_area' => $internalArea,
                    'land_area' => $landArea,
                    'development_status' => $devStatus,
                    'status' => $selectedStatus,
                    'is_visible' => true,
                    'owner_id' => $seller->id,
                    'governorate_id' => $compound->governorate_id,
                    'unit_type_id' => $type->id,
                    'compound_id' => $compound->id,
                    'developer_id' => $dev->id,
                    'latitude' => ($governorateCoordinates[$compound->governorate->name_en] ?? ['lat' => 30.0444, 'lng' => 31.2357])['lat'] + (rand(-100, 100) / 10000), // Slight offset
                    'longitude' => ($governorateCoordinates[$compound->governorate->name_en] ?? ['lat' => 30.0444, 'lng' => 31.2357])['lng'] + (rand(-100, 100) / 10000),
                    'sold_at' => ($selectedStatus === 'sold') ? now()->subDays(rand(1, 30)) : null,
                    'reserved_at' => ($selectedStatus === 'reserved') ? now()->subDays(rand(1, 10)) : null,
                ]);

                // Create Ownership details for the unit
                $unit->ownership()->create([
                    'contract_type' => ['سند ملكية مسجل', 'عقد بيع ابتدائي', 'عقد نهائي صحة ونفاذ', 'عقد ملكية حيازة'][array_rand(['سند ملكية مسجل', 'عقد بيع ابتدائي', 'عقد نهائي صحة ونفاذ', 'عقد ملكية حيازة'])],
                    'is_registered' => (rand(0, 1) === 1),
                    'plot_number' => 'أرض رقم ' . $fakerEn->numberBetween(100, 999),
                ]);

                if (!empty($amenityIds)) {
                    $randomAmenities = array_rand(array_flip($amenityIds), rand(4, 6));
                    $unit->amenities()->attach($randomAmenities);
                }

                // Use the type-specific image
                static $sharedTypeImages = [];
                $imageFile = 'villa.jpg'; // fallback
                if ($type->name_en === 'Commercial Unit') {
                    $imageFile = 'shop.jpg';
                } elseif ($type->name_en === 'Agricultural Unit') {
                    $imageFile = 'chalet.jpg';
                } elseif ($type->name_en === 'Industrial Unit') {
                    $imageFile = 'studio.jpg';
                }
                $unitTypeSource = base_path('unit types/' . $imageFile);

                if (File::exists($unitTypeSource)) {
                    if (!isset($sharedTypeImages[$type->id])) {
                        $sharedTypeImages[$type->id] = [];
                        for ($m = 1; $m <= 3; $m++) {
                            $imageName = "demo-type-{$type->id}-{$m}.jpg";
                            if (!Storage::disk('public')->exists('units/' . $imageName)) {
                                File::copy($unitTypeSource, Storage::disk('public')->path('units/' . $imageName));
                            }
                            $sharedTypeImages[$type->id][] = 'units/' . $imageName;
                        }
                    }

                    foreach ($sharedTypeImages[$type->id] as $index => $imageUrl) {
                        UnitMedia::create([
                            'unit_id' => $unit->id,
                            'type' => 'image',
                            'url' => $imageUrl,
                            'order' => $index + 1,
                            'processing_status' => 'completed'
                        ]);
                    }
                }

                // Add Floorplan
                static $sharedFloorplanUrl = null;
                if (File::exists($floorplanSource)) {
                    if (!$sharedFloorplanUrl) {
                        $floorplanName = "demo-floorplan.png";
                        if (!Storage::disk('public')->exists('units/' . $floorplanName)) {
                            File::copy($floorplanSource, Storage::disk('public')->path('units/' . $floorplanName));
                        }
                        $sharedFloorplanUrl = 'units/' . $floorplanName;
                    }

                    UnitMedia::create([
                        'unit_id' => $unit->id,
                        'type' => 'floorplan',
                        'url' => $sharedFloorplanUrl,
                        'order' => 4,
                        'processing_status' => 'completed'
                    ]);
                }

                // Add Video
                static $sharedVideoMedia = null;

                if (File::exists($videoSource)) {
                    if (!$sharedVideoMedia) {
                        $unitVideoName = "demo-unit-video.mp4";
                        if (!Storage::disk('public')->exists('units/' . $unitVideoName)) {
                            File::copy($videoSource, Storage::disk('public')->path('units/' . $unitVideoName));
                        }

                        $sharedVideoMedia = UnitMedia::create([
                            'unit_id' => $unit->id,
                            'type' => 'video',
                            'url' => 'units/' . $unitVideoName,
                            'order' => 5,
                            'processing_status' => 'pending'
                        ]);
                    } else {
                        UnitMedia::create([
                            'unit_id' => $unit->id,
                            'type' => 'video',
                            'url' => $sharedVideoMedia->url,
                            'order' => 5,
                            'processed_url' => 'units/hls/' . $sharedVideoMedia->id . '/playlist.m3u8',
                            'processing_status' => 'completed'
                        ]);
                    }
                }
            }
        }
    }
}
