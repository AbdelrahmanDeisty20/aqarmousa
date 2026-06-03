<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['name_en' => 'Cairo', 'name_ar' => 'القاهرة'],
            ['name_en' => 'Giza', 'name_ar' => 'الجيزة'],
            ['name_en' => 'Alexandria', 'name_ar' => 'الإسكندرية'],
            ['name_en' => 'Dakahlia', 'name_ar' => 'الدقهلية'],
            ['name_en' => 'Red Sea', 'name_ar' => 'البحر الأحمر'],
            ['name_en' => 'Beheira', 'name_ar' => 'البحيرة'],
            ['name_en' => 'Fayoum', 'name_ar' => 'الفيوم'],
            ['name_en' => 'Gharbia', 'name_ar' => 'الغربية'],
            ['name_en' => 'Ismailia', 'name_ar' => 'الإسماعيلية'],
            ['name_en' => 'Monufia', 'name_ar' => 'المنوفية'],
            ['name_en' => 'Minya', 'name_ar' => 'المنيا'],
            ['name_en' => 'Qalyubia', 'name_ar' => 'القليوبية'],
            ['name_en' => 'New Valley', 'name_ar' => 'الوادي الجديد'],
            ['name_en' => 'Suez', 'name_ar' => 'السويس'],
            ['name_en' => 'Aswan', 'name_ar' => 'أسوان'],
            ['name_en' => 'Assiut', 'name_ar' => 'أسيوط'],
            ['name_en' => 'Beni Suef', 'name_ar' => 'بني سويف'],
            ['name_en' => 'Port Said', 'name_ar' => 'بورسعيد'],
            ['name_en' => 'Damietta', 'name_ar' => 'دمياط'],
            ['name_en' => 'Sharkia', 'name_ar' => 'الشرقية'],
            ['name_en' => 'South Sinai', 'name_ar' => 'جنوب سيناء'],
            ['name_en' => 'Kafr El Sheikh', 'name_ar' => 'كفر الشيخ'],
            ['name_en' => 'Matrouh', 'name_ar' => 'مطروح'],
            ['name_en' => 'Qena', 'name_ar' => 'قنا'],
            ['name_en' => 'North Sinai', 'name_ar' => 'شمال سيناء'],
            ['name_en' => 'Sohag', 'name_ar' => 'سوهاج'],
            ['name_en' => 'Luxor', 'name_ar' => 'الأقصر'],
        ];

        foreach ($governorates as $gov) {
            Governorate::firstOrCreate(
                ['name_en' => $gov['name_en']],
                ['name_ar' => $gov['name_ar']]
            );
        }
    }
}
