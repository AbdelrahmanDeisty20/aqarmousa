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
        $units = \App\Models\Unit::all();

        $descriptions = [
            [
                'ar' => "شقة سكنية فاخرة في موقع متميز، تتميز بتشطيبات عالية الجودة (الترا سوبر لوكس). الشقة واسعة ومصممة بعناية لتوفير أقصى درجات الراحة. تطل على مساحات خضراء واسعة وقريبة من المولات والخدمات الرئيسية.",
                'en' => "Luxury residential apartment in a prime location, featuring high-quality finishes (Ultra Super Lux). The apartment is spacious and carefully designed to provide maximum comfort. It overlooks vast green spaces and is close to malls and main services.",
                'address_ar' => "التجمع الخامس، شارع التسعين الشمالي، بجوار وتر واي",
                'address_en' => "5th Settlement, North 90th St, Next to Waterway",
            ],
            [
                'ar' => "فيلا مستقلة رائعة بحديقة خاصة وحمام سباحة. الفيلا تقع في كمبوند هادئ وآمن، وتوفر خصوصية تامة. تتكون من طابقين وروف، مع مساحة معيشة كبيرة وغرف نوم ماستر.",
                'en' => "Magnificent standalone villa with a private garden and swimming pool. The villa is located in a quiet and secure compound, offering complete privacy. It consists of two floors and a roof, with a large living area and master bedrooms.",
                'address_ar' => "الشيخ زايد، الحي الدبلوماسي، كمبوند الياسمين",
                'address_en' => "Sheikh Zayed, Diplomatic Quarter, Jasmine Compound",
            ],
            [
                'ar' => "وحدة تجارية (مكتب) في موقع حيوي بقلب المحافظة. المكتب مجهز بالكامل وجاهز للعمل فوراً. المبنى حديث ويحتوي على مصاعد وأمن 24 ساعة، وقريب من وسائل المواصلات.",
                'en' => "Commercial unit (office) in a vital location in the heart of the governorate. The office is fully equipped and ready for immediate work. The building is modern, featuring elevators and 24-hour security, and is close to transportation.",
                'address_ar' => "محافظة نصر، شارع عباس العقاد، برج النور",
                'address_en' => "Nasr Governorate, Abbas El Akkad St, Al Noor Tower",
            ],
            [
                'ar' => "شاليه بإطلالة مباشرة على البحر، مثالي لقضاء العطلات الصيفية. الشاليه مفروش بالكامل بأثاث مودرن، ويحتوي على تراس كبير. الكمبوند به حمامات سباحة وخدمات ترفيهية.",
                'en' => "Chalet with a direct sea view, perfect for summer vacations. The chalet is fully furnished with modern furniture and has a large terrace. The compound has swimming pools and recreational services.",
                'address_ar' => "الساحل الشمالي، سيدي عبد الرحمن، قرية أمواج",
                'address_en' => "North Coast, Sidi Abdel Rahman, Amwaj Village",
            ],
            [
                'ar' => "روف مميز بمساحة مبنية وتراس خارجي ضخم. يتمتع بفيو مفتوح ورائع للمحافظة. فرصة مميزة لمحبي الخصوصية والهدوء وللإستمتاع بالهواء الطلق.",
                'en' => "Distinctive penthouse with built-up area and a huge outdoor terrace. It enjoys a wonderful open view of the governorate. A unique opportunity for lovers of privacy and tranquility and enjoying the outdoors.",
                'address_ar' => "المعادي، سرايات المعادي، شارع 9",
                'address_en' => "Maadi, Sarayat Maadi, Street 9",
            ],
            [
                'ar' => "شقة دوبلكس بتصميم عصري وتشطيبات خاصة. تحتوي على ريسبشن كبير ومطبخ أمريكي. الموقع قريب جداً من المدارس والجامعات، مما يجعلها مثالية للعائلات.",
                'en' => "Duplex apartment with modern design and special finishes. It features a large reception and an American kitchen. The location is very close to schools and universities, making it ideal for families.",
                'address_ar' => "6 أكتوبر، الحي المتميز، غرب سوميد",
                'address_en' => "6th of October, Distinctive District, West Somid",
            ],
        ];

        foreach ($units as $index => $unit) {
            // Cycle through the descriptions array based on the index
            $data = $descriptions[$index % count($descriptions)];

            $unit->update([
                'description_ar' => $data['ar'],
                'description_en' => $data['en'],
                'address_ar' => $data['address_ar'],
                'address_en' => $data['address_en'],
            ]);
        }
    }
}
