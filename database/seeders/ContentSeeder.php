<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Service;
use App\Models\Banner;
use App\Models\Testimonial;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed FAQs
        $faqs = [
            [
                'question_ar' => 'كيف يمكنني البحث عن عقار؟',
                'question_en' => 'How can I search for a property?',
                'answer_ar' => 'يمكنك استخدام محرك البحث المتقدم في الموقع لتصفية العقارات حسب المدينة، النوع، السعر، والمساحة. كما يمكنك التواصل مع فريقنا للحصول على مساعدة شخصية.',
                'answer_en' => 'You can use our advanced search engine to filter properties by city, type, price, and area. You can also contact our team for personalized assistance.',
            ],
            [
                'question_ar' => 'ما هي الأوراق المطلوبة لشراء عقار؟',
                'question_en' => 'What documents are required to purchase a property?',
                'answer_ar' => 'تحتاج إلى بطاقة الهوية الوطنية، إثبات الدخل، وشهادة عدم الممانعة من جهة العمل إن وجدت. سيقوم فريقنا القانوني بإرشادك خلال كامل العملية.',
                'answer_en' => 'You need a national ID, proof of income, and a no-objection certificate from your employer if applicable. Our legal team will guide you through the entire process.',
            ],
            [
                'question_ar' => 'هل تقدمون خدمات التمويل العقاري؟',
                'question_en' => 'Do you offer mortgage financing services?',
                'answer_ar' => 'نعم، نتعاون مع أفضل البنوك والمؤسسات المالية لتوفير حلول تمويلية مرنة تناسب احتياجاتك.',
                'answer_en' => 'Yes, we partner with leading banks and financial institutions to provide flexible financing solutions tailored to your needs.',
            ],
            [
                'question_ar' => 'كم تستغرق عملية شراء العقار؟',
                'question_en' => 'How long does the property purchase process take?',
                'answer_ar' => 'عادةً ما تستغرق العملية من 2 إلى 4 أسابيع، اعتماداً على نوع العقار وإجراءات التمويل.',
                'answer_en' => 'The process typically takes 2 to 4 weeks, depending on the property type and financing procedures.',
            ],
            [
                'question_ar' => 'هل يمكنني زيارة العقار قبل الشراء؟',
                'question_en' => 'Can I visit the property before purchasing?',
                'answer_ar' => 'بالتأكيد! نوفر جولات ميدانية مجانية لجميع العقارات المعروضة. يمكنك حجز موعد من خلال الموقع أو التواصل معنا مباشرة.',
                'answer_en' => 'Absolutely! We provide free site visits for all listed properties. You can book an appointment through our website or contact us directly.',
            ],
            [
                'question_ar' => 'ما هي رسوم الخدمة؟',
                'question_en' => 'What are the service fees?',
                'answer_ar' => 'رسوم الخدمة تختلف حسب نوع العقار وقيمته. سيتم توضيح جميع الرسوم بشفافية قبل إتمام أي صفقة.',
                'answer_en' => 'Service fees vary depending on the property type and value. All fees will be transparently disclosed before completing any transaction.',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question_ar' => $faq['question_ar']], $faq);
        }

        // Seed Services
        $services = [
            [
                'title_ar' => 'البحث عن العقارات',
                'title_en' => 'Property Search',
                'content_ar' => 'نساعدك في العثور على العقار المثالي الذي يناسب احتياجاتك وميزانيتك من خلال قاعدة بيانات شاملة ومحدثة باستمرار.',
                'content_en' => 'We help you find the perfect property that matches your needs and budget through a comprehensive and constantly updated database.',
                'icon' => '',
            ],
            [
                'title_ar' => 'التقييم العقاري',
                'title_en' => 'Property Valuation',
                'content_ar' => 'نقدم خدمات تقييم عقاري دقيقة ومعتمدة لمساعدتك في اتخاذ قرارات استثمارية صحيحة.',
                'content_en' => 'We provide accurate and certified property valuation services to help you make informed investment decisions.',
                'icon' => '',
            ],
            [
                'title_ar' => 'الاستشارات القانونية',
                'title_en' => 'Legal Consultation',
                'content_ar' => 'فريقنا القانوني المتخصص يوفر لك الاستشارات والإرشادات اللازمة لضمان سلامة المعاملات العقارية.',
                'content_en' => 'Our specialized legal team provides you with the necessary consultations and guidance to ensure safe real estate transactions.',
                'icon' => '',
            ],
            [
                'title_ar' => 'إدارة الممتلكات',
                'title_en' => 'Property Management',
                'content_ar' => 'نوفر خدمات إدارة شاملة لممتلكاتك العقارية، من الصيانة إلى التأجير وتحصيل الإيجارات.',
                'content_en' => 'We provide comprehensive management services for your real estate properties, from maintenance to leasing and rent collection.',
                'icon' => '',
            ],
            [
                'title_ar' => 'التسويق العقاري',
                'title_en' => 'Real Estate Marketing',
                'content_ar' => 'نستخدم أحدث استراتيجيات التسويق الرقمي لضمان وصول عقارك إلى أكبر عدد من المشترين المحتملين.',
                'content_en' => 'We use the latest digital marketing strategies to ensure your property reaches the maximum number of potential buyers.',
                'icon' => '',
            ],
            [
                'title_ar' => 'التمويل العقاري',
                'title_en' => 'Mortgage Financing',
                'content_ar' => 'نساعدك في الحصول على أفضل عروض التمويل العقاري من خلال شراكاتنا مع البنوك الرائدة.',
                'content_en' => 'We help you get the best mortgage financing offers through our partnerships with leading banks.',
                'icon' => '',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['title_ar' => $service['title_ar']], $service);
        }

        // Seed Pages
        $pages = [
            [
                'slug' => 'about-us',
                'title_ar' => 'من نحن',
                'title_en' => 'About Us',
                'content_ar' => 'نحن شركة رائدة في مجال العقارات، نقدم خدمات متكاملة للبحث عن العقارات وشرائها وبيعها. مع أكثر من 15 عاماً من الخبرة في السوق المصري، نفخر بتقديم أفضل الحلول العقارية لعملائنا. فريقنا المتخصص يعمل على مدار الساعة لضمان رضاكم الكامل وتحقيق أهدافكم الاستثمارية.',
                'content_en' => 'We are a leading real estate company, offering comprehensive services for property search, buying, and selling. With over 15 years of experience in the Egyptian market, we pride ourselves on providing the best real estate solutions to our clients. Our specialized team works around the clock to ensure your complete satisfaction and achieve your investment goals.',
                'team_members' => [
                    ['name' => 'Member 1', 'position' => 'Manager', 'photo' => 'team/team1.jpg'],
                    ['name' => 'Member 2', 'position' => 'Developer', 'photo' => 'team/team2.jpg'],
                    ['name' => 'Member 3', 'position' => 'Designer', 'photo' => 'team/team3.jpg'],
                ],
            ],
            // [
            //     'slug' => 'contact-us',
            //     'title_ar' => 'اتصل بنا',
            //     'title_en' => 'Contact Us',
            //     'content_ar' => 'نحن هنا لخدمتك! يمكنك التواصل معنا عبر الهاتف، البريد الإلكتروني، أو زيارة مكتبنا الرئيسي. فريق خدمة العملاء لدينا جاهز للإجابة على جميع استفساراتك ومساعدتك في العثور على العقار المثالي.',
            //     'content_en' => 'We are here to serve you! You can contact us by phone, email, or visit our main office. Our customer service team is ready to answer all your inquiries and help you find the perfect property.',
            //     'team_members' => [],
            // ],

            // [
            //     'slug' => 'privacy-policy',
            //     'title_ar' => 'سياسة الخصوصية',
            //     'title_en' => 'Privacy Policy',
            //     'content_ar' => 'نحن نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية. جميع المعلومات التي تقدمها لنا يتم التعامل معها بسرية تامة ولا يتم مشاركتها مع أي جهات خارجية دون موافقتك الصريحة.',
            //     'content_en' => 'We respect your privacy and are committed to protecting your personal data. All information you provide us is handled with complete confidentiality and is not shared with any third parties without your explicit consent.',
            //     'team_members' => [],
            // ],
            [
                'slug' => 'terms-and-conditions',
                'title_ar' => 'الشروط والأحكام',
                'title_en' => 'Terms and Conditions',
                'content_ar' => 'باستخدامك لموقعنا وخدماتنا، فإنك توافق على الالتزام بالشروط والأحكام التالية. يرجى قراءتها بعناية قبل استخدام أي من خدماتنا.',
                'content_en' => 'By using our website and services, you agree to comply with the following terms and conditions. Please read them carefully before using any of our services.',
                'team_members' => [],
                'sections' => [
                    [
                        'title_ar' => 'جدول الدفع',
                        'title_en' => 'Payment Schedule',
                        'content_ar' => "- يجب دفع 100% من المبلغ الإجمالي في وقت الحجز\n- الرصيد المتبقي : مستحق لاحقاً",
                        'content_en' => "- 100% of the total amount must be paid at the time of booking\n- Remaining balance: Due later"
                    ],
                    [
                        'title_ar' => 'سياسة الإلغاء',
                        'title_en' => 'Cancellation Policy',
                        'content_ar' => "- 75% من الدفعات المسبقة المدفوعة قابلة للاسترداد عند الإلغاء قبل 41 يوماً من الوصول أو قبل ذلك\n- 50% من الدفعات المقدمة المدفوعة قابلة للاسترداد عند الإلغاء قبل 21 يوماً من الوصول أو قبل ذلك\n- 0% قابلة للاسترداد في حالة الإلغاء بعد ذلك",
                        'content_en' => "- 75% of prepaid payments are refundable for cancellations 41 days or more before arrival\n- 50% of prepaid payments are refundable for cancellations 21 days or more before arrival\n- 0% refundable for cancellations thereafter"
                    ],
                    [
                        'title_ar' => 'وديعة الضمان',
                        'title_en' => 'Security Deposit',
                        'content_ar' => "- يجب دفع وديعة تأمين قابلة للاسترداد بنسبة 25%",
                        'content_en' => "- A refundable security deposit of 25% must be paid"
                    ],
                    [
                        'title_ar' => 'ملحوظات',
                        'title_en' => 'Notes',
                        'content_ar' => "- خصم 5% على الإقامة لمدة 14 ليلة أو أكثر\n- خصم 10% على الإقامة لمدة 30 ليلة أو أكثر",
                        'content_en' => "- 5% discount for stays of 14 nights or more\n- 10% discount for stays of 30 nights or more"
                    ]
                ],
            ],
        ];

        foreach ($pages as $page) {
            if ($page['slug'] === 'about-us') {
                foreach ($page['team_members'] as $member) {
                    $source = base_path('images/' . basename($member['photo']));
                    if (File::exists($source)) {
                        Storage::disk('public')->makeDirectory('team');
                        File::copy($source, Storage::disk('public')->path($member['photo']));
                    }
                }
            }
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }

        // Seed Banners
        $banners = [
            [
                'image' => 'banners/banner1.jpg',
                'url' => '/units',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'image' => 'banners/banner2.jpg',
                'url' => '/about',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'image' => 'banners/banner3.jpg',
                'url' => '/services',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'image' => 'banners/banner4.jpg',
                'url' => '/contact',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'image' => 'banners/banner5.jpg',
                'url' => '/faq',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($banners as $banner) {
            $source = base_path('images/' . basename($banner['image']));
            if (File::exists($source)) {
                Storage::disk('public')->makeDirectory('banners');
                File::copy($source, Storage::disk('public')->path($banner['image']));
            }
            Banner::updateOrCreate(['url' => $banner['url']], $banner);
        }

        // Seed Testimonials
        $testimonials = [
            [
                'name' => 'Ahmed Ali',
                'position' => 'CEO',
                'content' => 'Great service from this company!',
                'status' => true,
            ],
            [
                'name' => 'mohamed',
                'position' => 'Client',
                'content' => 'موقع جميل جدا',
                'status' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(['name' => $testimonial['name'], 'content' => $testimonial['content']], $testimonial);
        }

        // Seed Settings
        $settings = [
            'site_name' => 'Propix 8',
            'site_email' => 'admin@admin.com',
            'site_phone' => '01010613746',
            'site_address' => 'mansoura',
            'social_facebook' => 'https://facebook.com',
            'social_instagram' => 'https://instagram.com',
            'social_twitter' => 'https://twitter.com',
            'site_logo' => 'settings/logo.png',
            'home_hero_image' => json_encode([
                "settings/hero1.jpg",
                "settings/hero2.jpg",
                "settings/hero3.jpeg",
                "settings/hero4.jpeg"
            ]),
        ];

        foreach ($settings as $key => $value) {
            if ($key === 'site_logo') {
                $source = base_path('images/logo.png');
                if (File::exists($source)) {
                    Storage::disk('public')->makeDirectory('settings');
                    File::copy($source, Storage::disk('public')->path('settings/logo.png'));
                }
            }
            if ($key === 'home_hero_image') {
                $images = json_decode($value, true);
                foreach ($images as $img) {
                    $source = base_path('images/' . basename($img));
                    if (File::exists($source)) {
                        Storage::disk('public')->makeDirectory('settings');
                        File::copy($source, Storage::disk('public')->path($img));
                    }
                }
            }
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
