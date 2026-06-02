<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\User;
use App\Models\UnitMedia;
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
        $fakerAr = Faker::create('ar_EG');
        $fakerEn = Faker::create('en_US');

        $amenityIds = \App\Models\Amenity::pluck('id')->toArray();

        // Arabic descriptions for units and compounds
        $arabicDescriptions = [
            'شقة فاخرة بتصميم عصري وإطلالة رائعة على المساحات الخضراء. تتميز بتشطيبات عالية الجودة ومساحات واسعة مناسبة للعائلات.',
            'وحدة سكنية متميزة في موقع استراتيجي قريب من جميع الخدمات والمرافق الحيوية. تصميم معماري فريد يجمع بين الفخامة والراحة.',
            'عقار راقي بمواصفات استثنائية يوفر أعلى مستويات الرفاهية والخصوصية. مثالي للباحثين عن حياة عصرية مريحة.',
            'وحدة سكنية بتشطيبات فاخرة ومرافق متكاملة. موقع متميز يوفر سهولة الوصول لأهم المناطق الحيوية في المدينة.',
            'شقة دوبلكس بتصميم عصري وتشطيبات خاصة. تحتوي على ريسبشن كبير ومطبخ أمريكي. الموقع قريب جداً من المدارس والجامعات، مما يجعلها مثالية للعائلات.',
            'فيلا فاخرة محاطة بالحدائق والمساحات الخضراء. تصميم معماري فريد يجمع بين الأصالة والحداثة مع جميع وسائل الراحة العصرية.',
            'تاون هاوس بموقع مميز وتصميم عملي يناسب الأسر الكبيرة. يتميز بمساحات واسعة وإطلالات خلابة على المناظر الطبيعية.',
            'شاليه على البحر مباشرة بإطلالة بانورامية ساحرة. مثالي لقضاء عطلات صيفية لا تُنسى مع العائلة والأصدقاء.',
            'بنتهاوس فاخر في الأدوار العليا مع تراس واسع وإطلالة بانورامية على المدينة. تشطيبات سوبر لوكس ومرافق حصرية.',
            'ستوديو عصري بتصميم ذكي يستغل المساحة بشكل مثالي. مناسب للشباب والمحترفين الباحثين عن سكن عملي وأنيق.',
        ];

        $arabicCompoundDescriptions = [
            'كمبوند سكني متكامل يوفر أرقى مستويات المعيشة مع مساحات خضراء واسعة ومرافق ترفيهية متنوعة. موقع استراتيجي يجمع بين الهدوء والقرب من المدينة.',
            'مشروع سكني فاخر بتصميم معماري عالمي يضم وحدات متنوعة تناسب جميع الاحتياجات. يتميز بالأمن والخصوصية والخدمات المتكاملة.',
            'مجتمع سكني راقي يجمع بين الطبيعة الخلابة والحياة العصرية. يحتوي على نوادي رياضية، مدارس دولية، ومراكز تجارية متكاملة.',
            'كمبوند حديث يوفر نمط حياة عصري ومتكامل مع جميع الخدمات والمرافق على أعلى مستوى. تصميمات معمارية فريدة ومساحات خضراء واسعة.',
            'مشروع سكني متميز في قلب المدينة يجمع بين الموقع الاستراتيجي والتصميم العصري. يوفر بيئة آمنة ومريحة للعائلات.',
        ];

        // Ensure super_admin role exists
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $villaImage = 'modern-luxury-house-with-swimming-pool.jpg';
        $villaSource = base_path('images/' . $villaImage); // Corrected source path
        $videoFile = 'videoplayback.mp4';
        $videoSource = base_path('images/' . $videoFile);

        // 1. Create Cities
        $citiesData = [
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

        $cities = [];
        foreach ($citiesData as $city) {
            $cities[] = City::firstOrCreate(
                ['name_en' => $city['en']],
                ['name_ar' => $city['ar']]
            );
        }

        // 2. Create Unit Types
        $typesData = [
            ['en' => 'Apartment', 'ar' => 'شقة', 'icon' => 'apartment', 'image_file' => 'apartment.jpg'],
            ['en' => 'Villa', 'ar' => 'فيلا', 'icon' => 'villa', 'image_file' => 'villa.jpg'],
            ['en' => 'Townhouse', 'ar' => 'تاون هاوس', 'icon' => 'home', 'image_file' => 'townhouse.jpg'],
            ['en' => 'Twin House', 'ar' => 'توين هاوس', 'icon' => 'holiday_village', 'image_file' => 'twinhouse.jpg'],
            ['en' => 'Chalet', 'ar' => 'شاليه', 'icon' => 'beach_access', 'image_file' => 'chalet.jpg'],
            ['en' => 'Duplex', 'ar' => 'دوبلكس', 'icon' => 'stairs', 'image_file' => 'duplex.jpg'],
            ['en' => 'Penthouse', 'ar' => 'بنتهاوس', 'icon' => 'deck', 'image_file' => 'penthouse.jpg'],
            ['en' => 'Studio', 'ar' => 'ستوديو', 'icon' => 'weekend', 'image_file' => 'studio.jpg'],
            ['en' => 'Shop', 'ar' => 'محلات', 'icon' => 'store', 'image_file' => 'shop.jpg'],
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
                ['name_ar' => $type['ar'], 'icon' => 'unit-types/' . $type['image_file']]
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

        // 4. Create Developers
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
            ['name_en' => 'Marassi', 'name_ar' => 'مراسي', 'city' => 'North Coast', 'dev' => 'Emaar Misr'],
            ['name_en' => 'Mivida', 'name_ar' => 'ميفيدا', 'city' => 'New Cairo', 'dev' => 'Emaar Misr'],
            ['name_en' => 'SODIC West', 'name_ar' => 'سوديك ويست', 'city' => 'Sheikh Zayed', 'dev' => 'SODIC'],
            ['name_en' => 'IL Monte Galala', 'name_ar' => 'المونت جلالة', 'city' => 'Ain Sokhna', 'dev' => 'Tatweer Misr'],
            ['name_en' => 'Palm Hills Alexandria', 'name_ar' => 'بالم هيلز الإسكندرية', 'city' => 'Alexandria', 'dev' => 'Palm Hills'],
            ['name_en' => 'Noor City', 'name_ar' => 'نور سيتي', 'city' => 'New Capital', 'dev' => 'Talaat Moustafa Group (TMG)'],
            ['name_en' => 'Badya', 'name_ar' => 'بادية', 'city' => '6th of October', 'dev' => 'Palm Hills'],
            ['name_en' => 'Heliopolis Gardens', 'name_ar' => 'حدائق مصر الجديدة', 'city' => 'Cairo', 'dev' => 'Emaar Misr'],
            ['name_en' => 'Pyramids Heights', 'name_ar' => 'مرتفعات الأهرام', 'city' => 'Giza', 'dev' => 'SODIC'],
            ['name_en' => 'Mansoura Gardens', 'name_ar' => 'حدائق المنصورة', 'city' => 'Mansoura', 'dev' => 'Mountain View'],
        ];

        $compounds = [];

        // Real coordinates for each city in Egypt
        $cityCoordinates = [
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
            $city = City::where('name_en', $comp['city'])->first();
            $dev = Developer::where('name_en', $comp['dev'])->first();

            if ($city && $dev) {
                $coords = $cityCoordinates[$comp['city']] ?? ['lat' => 30.0444, 'lng' => 31.2357];

                $compounds[] = Compound::firstOrCreate(
                    ['name_en' => $comp['name_en']],
                    [
                        'name_ar' => $comp['name_ar'],
                        'description_en' => $fakerEn->paragraph,
                        'description_ar' => $arabicCompoundDescriptions[array_rand($arabicCompoundDescriptions)],
                        'city_id' => $city->id,
                        'latitude' => $coords['lat'] + (rand(-100, 100) / 10000), // Slight offset
                        'longitude' => $coords['lng'] + (rand(-100, 100) / 10000),
                    ]
                );
            }
        }

        // 6. Create Units
        $offerTypes = ['sale', 'rent'];

        Storage::disk('public')->makeDirectory('units');
        $floorplanSource = base_path('unit types/floorplan.png');
        $videoSource = base_path('unit types/videoplayback.mp4');

        foreach ($compounds as $compound) {
            $numUnits = 7;

            for ($k = 0; $k < $numUnits; $k++) {
                $seller = $admin;
                $type = $unitTypes[array_rand($unitTypes)];
                $dev = $developers[array_rand($developers)];

                $price = ($type->name_en == 'Villa' || $type->name_en == 'Twin House')
                        ? $fakerEn->numberBetween(5000000, 50000000)
                        : $fakerEn->numberBetween(1500000, 8000000);

                $selectedOfferType = $offerTypes[array_rand($offerTypes)];
                $isSale = ($selectedOfferType == 'sale');

                // development_status: primary/resale للبيع، null للإيجار
                $developmentStatus = $isSale ? (['primary', 'resale'][array_rand(['primary', 'resale'])]) : '';

                $unit = Unit::create([
                    'title_en' => $type->name_en . ' for ' . $selectedOfferType . ' in ' . $compound->name_en,
                    'title_ar' => $type->name_ar . ' للـ ' . ($isSale ? 'بيع' : 'إيجار') . ' في ' . $compound->name_ar,
                    'description_en' => $fakerEn->realText(200),
                    'description_ar' => $arabicDescriptions[array_rand($arabicDescriptions)],
                    'address' => $fakerAr->address,
                    'price' => $price,
                    'price_per_m2' => $price / 100, // rough estimate
                    'offer_type' => $selectedOfferType,
                    'area' => $fakerEn->numberBetween(80, 500),
                    'rooms' => $fakerEn->numberBetween(1, 6),
                    'bathrooms' => $fakerEn->numberBetween(1, 4),
                    'garages' => $fakerEn->numberBetween(0, 2),
                    'build_year' => $fakerEn->year,
                    'status' => 'approved', // Active/Approved
                    'is_visible' => true,
                    'development_status' => $developmentStatus,
                    'owner_id' => $seller->id,
                    'city_id' => $compound->city_id,
                    'unit_type_id' => $type->id,
                    'compound_id' => $compound->id,
                    'developer_id' => $dev->id,
                    'latitude' => $compound->latitude + (rand(-100, 100) / 10000), // Slight offset
                    'longitude' => $compound->longitude + (rand(-100, 100) / 10000),
                ]);

                if (!empty($amenityIds)) {
                    $randomAmenities = array_rand(array_flip($amenityIds), rand(4, 6));
                    $unit->amenities()->attach($randomAmenities);
                }

                // Use the type-specific image
                static $sharedTypeImages = [];
                $typeIconPath = str_replace('unit-types/', '', $type->icon);
                $unitTypeSource = base_path('unit types/' . $typeIconPath);

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
