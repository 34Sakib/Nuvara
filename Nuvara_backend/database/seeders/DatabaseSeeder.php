<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Address;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed User & Address
        $user = User::create([
            'name' => 'Sakib',
            'email' => 'Sakib@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1 (555) 019-2834',
            'locale_preference' => 'en',
            'theme_preference' => 'light',
        ]);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@nuvara.com',
            'password' => Hash::make('adminpassword'),
            'phone' => '+1 (555) 000-0000',
            'locale_preference' => 'en',
            'theme_preference' => 'dark',
            'role' => 'admin',
        ]);

        Address::create([
            'user_id' => $user->id,
            'label' => 'Home (Default)',
            'fullName' => 'Your Name',
            'address' => '128 Pinecrest Ave',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94110',
            'country' => 'USA',
            'is_default' => true,
        ]);

        // 2. Seed Brands
        $brandsData = [
            ['name' => 'AeroSound', 'slug' => 'aerosound'],
            ['name' => 'Krono', 'slug' => 'krono'],
            ['name' => 'Vanguard', 'slug' => 'vanguard'],
            ['name' => 'Stride', 'slug' => 'stride'],
            ['name' => 'Luminaire', 'slug' => 'luminaire'],
            ['name' => 'IronCore', 'slug' => 'ironcore'],
        ];
        $brands = [];
        foreach ($brandsData as $brandItem) {
            $brands[$brandItem['name']] = Brand::create($brandItem);
        }

        // 3. Seed Categories
        $categoriesData = [
            [
                'slug' => 'electronics',
                'name' => [
                    'en' => 'Electronics',
                    'es' => 'Electrónica',
                    'ar' => 'إلكترونيات',
                    'bn' => 'ইলেকট্রনিক্স'
                ],
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=600&auto=format&fit=crop&q=80',
                'sort_order' => 1
            ],
            [
                'slug' => 'fashion',
                'name' => [
                    'en' => 'Fashion & Apparel',
                    'es' => 'Moda y Ropa',
                    'ar' => 'الأزياء والملابس',
                    'bn' => 'ফ্যাশন ও পোশাক'
                ],
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&auto=format&fit=crop&q=80',
                'sort_order' => 2
            ],
            [
                'slug' => 'home-living',
                'name' => [
                    'en' => 'Home & Living',
                    'es' => 'Hogar y Decoración',
                    'ar' => 'المنزل والمعيشة',
                    'bn' => 'হোম ও লিভিং'
                ],
                'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=600&auto=format&fit=crop&q=80',
                'sort_order' => 3
            ],
            [
                'slug' => 'fitness-outdoors',
                'name' => [
                    'en' => 'Fitness & Outdoors',
                    'es' => 'Deportes y Aire Libre',
                    'ar' => 'الرياضة واللياقة',
                    'bn' => 'ফিটনেস ও আউটডোর'
                ],
                'image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=600&auto=format&fit=crop&q=80',
                'sort_order' => 4
            ]
        ];
        $categories = [];
        foreach ($categoriesData as $catItem) {
            $categories[$catItem['slug']] = Category::create($catItem);
        }

        // 4. Seed Products, Images, Variants, Reviews
        $productsData = [
            [
                'sku' => 'EL-HP-01',
                'category_slug' => 'electronics',
                'brand_name' => 'AeroSound',
                'slug' => 'wireless-noise-canceling-headphones',
                'name' => [
                    'en' => 'AeroSound Pro Wireless Headphones',
                    'es' => 'Auriculares Inalámbricos AeroSound Pro',
                    'ar' => 'سماعات الرأس اللاسلكية إيروساوند برو',
                    'bn' => 'অ্যারোসাউন্ড প্রো ওয়্যারলেস হেডফোন'
                ],
                'description' => [
                    'en' => 'Experience ultimate sound quality with active noise cancellation, 40-hour battery life, and high-fidelity drivers designed to bring your music to life.',
                    'es' => 'Disfruta de la mejor calidad de sonido con cancelación activa de ruido, 40 horas de batería y transductores de alta fidelidad diseñados para dar vida a tu música.',
                    'ar' => 'استمتع بجودة صوت فائقة مع تقنية إلغاء الضوضاء النشطة، وعمر بطارية يصل إلى 40 ساعة، ومكبرات صوت عالية الدقة مصممة لإحياء الموسيقى الخاصة بك.',
                    'bn' => 'অ্যাক্টিভ নয়েজ ক্যান্সেলেশন, ৪০ ঘণ্টার ব্যাটারি লাইফ এবং হাই-ফিডেলিটি ড্রাইভার সহ উপভোগ করুন সেরা সাউন্ড কোয়ালিটি।'
                ],
                'price' => 199.99,
                'compare_price' => 249.99,
                'stock' => 15,
                'avg_rating' => 4.8,
                'review_count' => 128,
                'is_best_seller' => true,
                'is_new' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    'colors' => [
                        ['name' => 'Carbon Black', 'value' => '#1A1A1A'],
                        ['name' => 'Indigo Blue', 'value' => '#4338CA'],
                        ['name' => 'Platinum Silver', 'value' => '#E5E7EB']
                    ]
                ],
                'specs' => [
                    'en' => [
                        ['key' => 'Driver Size', 'value' => '40mm High Fidelity'],
                        ['key' => 'ANC Depth', 'value' => 'Up to 38dB'],
                        ['key' => 'Bluetooth Version', 'value' => '5.2 with aptX HD'],
                        ['key' => 'Charging Time', 'value' => '1.5 Hours']
                    ],
                    'es' => [
                        ['key' => 'Tamaño del Driver', 'value' => '40mm Alta Fidelidad'],
                        ['key' => 'Profundidad ANC', 'value' => 'Hasta 38dB'],
                        ['key' => 'Versión Bluetooth', 'value' => '5.2 con aptX HD'],
                        ['key' => 'Tiempo de Carga', 'value' => '1.5 Horas']
                    ],
                    'ar' => [
                        ['key' => 'حجم المكبر', 'value' => '40 مم عالي الدقة'],
                        ['key' => 'عمق إلغاء الضوضاء', 'value' => 'يصل إلى 38 ديسيبل'],
                        ['key' => 'إصدار البلوتوث', 'value' => '5.2 مع aptX HD'],
                        ['key' => 'وقت الشحن', 'value' => 'ساعة ونصف']
                    ],
                    'bn' => [
                        ['key' => 'ড্রাইভার সাইজ', 'value' => '৪০ মিমি হাই ফিডেলিটি'],
                        ['key' => 'নয়েজ ক্যান্সেলেশন', 'value' => '৩৮ ডেসিবেল পর্যন্ত'],
                        ['key' => 'ব্লুটুথ সংস্করণ', 'value' => '৫.২ aptX HD সহ'],
                        ['key' => 'চার্জিং সময়', 'value' => '১.৫ ঘণ্টা']
                    ]
                ],
                'reviews' => [
                    [
                        'name' => 'Sophia Carter',
                        'rating' => 5,
                        'comment' => [
                            'en' => 'Absolute bliss! The active noise canceling is incredibly good, blocking all my office chatter.',
                            'es' => '¡Una maravilla absoluta! La cancelación de ruido es increíble, bloquea todo el ruido de la oficina.',
                            'ar' => 'متعة مطلقة! ميزة إلغاء الضوضاء النشطة ممتازة للغاية، تحجب كل الضوضاء في المكتب.',
                            'bn' => 'অসাধারণ অনুভূতি! নয়েজ ক্যান্সেলেশন খুবই ভালো কাজ করে, অফিসের কোনো শব্দই শোনা যায় না।'
                        ]
                    ],
                    [
                        'name' => 'Ahmed Al-Farsi',
                        'rating' => 5,
                        'comment' => [
                            'en' => 'Very comfortable to wear for hours. Soundstage is deep, battery easily lasts all week.',
                            'es' => 'Muy cómodos para usar por horas. El sonido es profundo, la batería dura toda la semana.',
                            'ar' => 'مريحة جداً للارتداء لساعات طويلة. الصوت عميق والبطارية تدوم بسهولة طوال الأسبوع.',
                            'bn' => 'কয়েক ঘণ্টা ধরে পরলেও অনেক আরামদায়ক লাগে। ব্যাটারি সহজেই এক সপ্তাহ চলে যায়।'
                        ]
                    ]
                ]
            ],
            [
                'sku' => 'EL-SW-02',
                'category_slug' => 'electronics',
                'brand_name' => 'Krono',
                'slug' => 'active-chronograph-smartwatch',
                'name' => [
                    'en' => 'Krono Active Smartwatch v3',
                    'es' => 'Reloj Inteligente Krono Active v3',
                    'ar' => 'ساعة كرونو أكتيف الذكية الإصدار الثالث',
                    'bn' => 'ক্রোনো অ্যাক্টিভ স্মার্টওয়াচ সংস্করণ ৩'
                ],
                'description' => [
                    'en' => 'Track your health, monitor athletic performance, and stay connected with a stunning AMOLED screen and up to 14 days of standby battery life.',
                    'es' => 'Monitorea tu salud, tu rendimiento deportivo y mantente conectado con una pantalla AMOLED espectacular y hasta 14 días de batería en espera.',
                    'ar' => 'تتبع صحتك، وراقب أدائك الرياضي، وابقَ على اتصال مع شاشة AMOLED مذهلة وبطارية تدوم حتى 14 يوماً في وضع الاستعداد.',
                    'bn' => 'অ্যামোলেড স্ক্রিন এবং ১৪ দিনের স্ট্যান্ডবাই ব্যাটারি লাইফ সহ আপনার স্বাস্থ্য এবং অ্যাথলেটিক পারফরম্যান্স ট্র্যাক করুন।'
                ],
                'price' => 129.99,
                'compare_price' => 159.99,
                'stock' => 4,
                'avg_rating' => 4.6,
                'review_count' => 94,
                'is_best_seller' => false,
                'is_new' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    'colors' => [
                        ['name' => 'Obsidian Black', 'value' => '#1A1A1A'],
                        ['name' => 'Space Silver', 'value' => '#9CA3AF']
                    ]
                ],
                'specs' => [
                    'en' => [
                        ['key' => 'Display', 'value' => '1.43" AMOLED Display'],
                        ['key' => 'Waterproof', 'value' => '5ATM Grade'],
                        ['key' => 'Battery Standby', 'value' => 'Up to 14 Days'],
                        ['key' => 'Sensors', 'value' => 'Heart Rate, SpO2, Sleep Tracker']
                    ],
                    'es' => [
                        ['key' => 'Pantalla', 'value' => 'Pantalla AMOLED de 1.43"'],
                        ['key' => 'Impermeabilidad', 'value' => 'Grado 5ATM'],
                        ['key' => 'Batería en Espera', 'value' => 'Hasta 14 Días'],
                        ['key' => 'Sensores', 'value' => 'Ritmo cardíaco, SpO2, Monitor de Sueño']
                    ],
                    'ar' => [
                        ['key' => 'الشاشة', 'value' => 'شاشة AMOLED مقاس 1.43 بوصة'],
                        ['key' => 'مقاومة الماء', 'value' => 'تصنيف 5ATM'],
                        ['key' => 'استعداد البطارية', 'value' => 'يصل إلى 14 يومًا'],
                        ['key' => 'المستشعرات', 'value' => 'نبضات القلوب، الأكسجين SpO2، تتبع النوم']
                    ],
                    'bn' => [
                        ['key' => 'ডিসপ্লে', 'value' => '১.৪৩" অ্যামোলেড ডিসপ্লে'],
                        ['key' => 'ওয়াটারপ্রুফ', 'value' => '5ATM গ্রেড'],
                        ['key' => 'ব্যাটারি স্ট্যান্ডবাই', 'value' => '১৪ দিন পর্যন্ত'],
                        ['key' => 'সেন্সর', 'value' => 'হার্ট রেট, SpO2, স্লিপ ট্র্যাকার']
                    ]
                ],
                'reviews' => [
                    [
                        'name' => 'Mark Henderson',
                        'rating' => 4,
                        'comment' => [
                            'en' => 'Excellent AMOLED display, very bright outdoors. Step counts are quite accurate.',
                            'es' => 'Excelente pantalla AMOLED, muy brillante en exteriores. El conteo de pasos es preciso.',
                            'ar' => 'شاشة AMOLED ممتازة وواضحة جداً في الخارج. حساب الخطوات دقيق للغاية.',
                            'bn' => 'চমৎকার অ্যামোলেড ডিসপ্লে, বাইরেও খুব উজ্জ্বল থাকে। স্টেপ কাউন্টার বেশ নির্ভুল।'
                        ]
                    ]
                ]
            ],
            [
                'sku' => 'FA-JK-01',
                'category_slug' => 'fashion',
                'brand_name' => 'Vanguard',
                'slug' => 'all-weather-windbreaker-jacket',
                'name' => [
                    'en' => 'Vanguard All-Weather Windbreaker',
                    'es' => 'Chubasquero Vanguard Todo Clima',
                    'ar' => 'سترة مقاومة للرياح لكل الأحوال الجوية',
                    'bn' => 'ভ্যানগার্ড অল-ওয়েদার উইন্ডব্রেকার জ্যাকেট'
                ],
                'description' => [
                    'en' => 'A water-resistant, ultra-lightweight windbreaker designed for optimal movement and outdoor protection. Features double-stitch seams and breathable mesh pockets.',
                    'es' => 'Un chubasquero resistente al agua y ultraligero diseñado para un movimiento óptimo y protección al aire libre. Costuras dobles y bolsillos transpirables.',
                    'ar' => 'سترة خفيفة الوزن للغاية ومقاومة للماء مصممة لتوفير حركة مثالية وحماية في الهواء الطلق.',
                    'bn' => 'জল-প্রতিরোধী এবং অত্যন্ত হালকা উইন্ডব্রেকার জ্যাকেট যা চলাচলের সুবিধা এবং বাইরের আবহাওয়া থেকে সুরক্ষা দেয়।'
                ],
                'price' => 79.99,
                'compare_price' => 99.99,
                'stock' => 25,
                'avg_rating' => 4.7,
                'review_count' => 215,
                'is_best_seller' => true,
                'is_new' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1548883354-7622d03aca27?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    'colors' => [
                        ['name' => 'Olive Green', 'value' => '#3F4E4F'],
                        ['name' => 'Navy Blue', 'value' => '#1A2F4C'],
                        ['name' => 'Sunset Orange', 'value' => '#D97706']
                    ],
                    'sizes' => ['S', 'M', 'L', 'XL']
                ],
                'specs' => [
                    'en' => [
                        ['key' => 'Material', 'value' => '100% Recycled Polyester'],
                        ['key' => 'Fit', 'value' => 'Regular Athletic Fit'],
                        ['key' => 'Pocket Styles', 'value' => 'Double zipper with mesh lining']
                    ],
                    'es' => [
                        ['key' => 'Material', 'value' => '100% Poliéster Reciclado'],
                        ['key' => 'Ajuste', 'value' => 'Ajuste Deportivo Regular'],
                        ['key' => 'Bolsillos', 'value' => 'Cremallera doble con forro de malla']
                    ],
                    'ar' => [
                        ['key' => 'المادة', 'value' => '100% بوليستر معاد تدويره'],
                        ['key' => 'الملائمة', 'value' => 'قصة رياضية عادية'],
                        ['key' => 'الجيوب', 'value' => 'سحاب مزدوج ببطانة شبكية']
                    ],
                    'bn' => [
                        ['key' => 'উপাদান', 'value' => '১০০% রিসাইকেলড পলিয়েস্টার'],
                        ['key' => 'ফিট', 'value' => 'রেগুলার অ্যাথলেটিক ফিট'],
                        ['key' => 'পকেট স্টাইল', 'value' => 'ডবল জিপার মেশ লাইনিং সহ']
                    ]
                ],
                'reviews' => []
            ],
            [
                'sku' => 'FA-SN-02',
                'category_slug' => 'fashion',
                'brand_name' => 'Stride',
                'slug' => 'urban-runner-knit-sneakers',
                'name' => [
                    'en' => 'Stride Urban Runner Sneakers',
                    'es' => 'Zapatillas de Punto Stride Urban Runner',
                    'ar' => 'حذاء الجري سترايد إربان رانر المنسوج',
                    'bn' => 'স্ট্রাইড আরবান রানার স্নিকার্স'
                ],
                'description' => [
                    'en' => 'Crafted with premium breathable knit mesh and a highly cushioned responsive foam midsole. Perfect for daily commutes or long runs.',
                    'es' => 'Fabricado con malla tejida transpirable premium y una entresuela de espuma amortiguadora y receptiva. Perfecto para el día a día.',
                    'ar' => 'مصنوع من نسيج شبكي ممتاز يسمح بمرور الهواء ونعل أوسط رغوي مبطن ومستجيب بشكل كبير.',
                    'bn' => 'প্রিমিয়াম শ্বাসযোগ্য নিট মেশ এবং কুশনযুক্ত ফোম মিডসোল দিয়ে তৈরি। প্রতিদিন যাতায়াত বা দৌড়ানোর জন্য আদর্শ।'
                ],
                'price' => 89.99,
                'compare_price' => 119.99,
                'stock' => 0,
                'avg_rating' => 4.5,
                'review_count' => 42,
                'is_best_seller' => false,
                'is_new' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    'colors' => [
                        ['name' => 'Ruby Red', 'value' => '#DC2626'],
                        ['name' => 'Minimal Gray', 'value' => '#6B7280']
                    ],
                    'sizes' => ['8', '9', '10', '11']
                ],
                'specs' => [
                    'en' => [
                        ['key' => 'Midsole Technology', 'value' => 'CloudFoam cushioning'],
                        ['key' => 'Weight', 'value' => '240g per shoe'],
                        ['key' => 'Upper Material', 'value' => 'Stretch-knit breathable fabric']
                    ],
                    'es' => [
                        ['key' => 'Tecnología de Entresuela', 'value' => 'Amortiguación CloudFoam'],
                        ['key' => 'Peso', 'value' => '240g por zapato'],
                        ['key' => 'Parte Superior', 'value' => 'Tejido elástico transpirable'],
                    ],
                    'ar' => [
                        ['key' => 'تقنية النعل الأوسط', 'value' => 'تبطين CloudFoam'],
                        ['key' => 'الوزن', 'value' => '240 جرام للحذاء الواحد'],
                        ['key' => 'المادة العلوية', 'value' => 'نسيج مرن يسمح بالتهوية']
                    ],
                    'bn' => [
                        ['key' => 'মিডসোল প্রযুক্তি', 'value' => 'ক্লাউডফোম কুশনিং'],
                        ['key' => 'ওজন', 'value' => 'প্রতি জুতো ২৪০ গ্রাম'],
                        ['key' => 'উপরিভাগের উপাদান', 'value' => 'স্ট্রেচ-নিট ফ্যাব্রিক']
                    ]
                ],
                'reviews' => []
            ],
            [
                'sku' => 'HL-LP-01',
                'category_slug' => 'home-living',
                'brand_name' => 'Luminaire',
                'slug' => 'minimalist-arc-floor-lamp',
                'name' => [
                    'en' => 'Luminaire Minimalist Floor Lamp',
                    'es' => 'Lámpara de Pie Minimalista Luminaire',
                    'ar' => 'مصباح أرضي لومينير مينيماليست',
                    'bn' => 'লুমিনায়ার মিনিমালিস্ট ফ্লোর ল্যাম্প'
                ],
                'description' => [
                    'en' => 'Incorporate modern design into your living space with this sleek arc floor lamp. Featuring custom dimmable LED bulb and brushed brass metal base.',
                    'es' => 'Incorpora el diseño moderno en tu sala con esta elegante lámpara de arco. Cuenta con bombilla LED regulable y base de latón cepillado.',
                    'ar' => 'أضف لمسة عصرية إلى مساحة المعيشة الخاصة بك مع هذا المصباح الأرضي المقوس والأنيق. يتميز بمصباح LED قابل للتعتيم وقاعدة من النحاس المصقول.',
                    'bn' => 'আপনার বসার ঘরে আধুনিক ডিজাইন যোগ করুন এই ব্রাশড ব্রাস মেটাল বেসের ফ্লোর ল্যাম্পের সাথে।'
                ],
                'price' => 149.99,
                'compare_price' => 179.99,
                'stock' => 12,
                'avg_rating' => 4.9,
                'review_count' => 38,
                'is_best_seller' => true,
                'is_new' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    'colors' => [
                        ['name' => 'Brushed Brass', 'value' => '#D97706'],
                        ['name' => 'Matte Black', 'value' => '#1A1A1A']
                    ]
                ],
                'specs' => [
                    'en' => [
                        ['key' => 'Height', 'value' => '185cm'],
                        ['key' => 'Light Temperature', 'value' => '2700K - 5000K Adjustable'],
                        ['key' => 'Base Diameter', 'value' => '30cm Solid Metal']
                    ],
                    'es' => [
                        ['key' => 'Altura', 'value' => '185cm'],
                        ['key' => 'Temperatura de Luz', 'value' => '2700K - 5000K Ajustable'],
                        ['key' => 'Diámetro de Base', 'value' => '30cm Metal Sólido']
                    ],
                    'ar' => [
                        ['key' => 'الارتفاع', 'value' => '185 سم'],
                        ['key' => 'درجة حرارة الضوء', 'value' => '2700 كلفن - 5000 كلفن قابلة للتعديل'],
                        ['key' => 'قطر القاعدة', 'value' => '30 سم معدن صلب']
                    ],
                    'bn' => [
                        ['key' => 'উচ্চতা', 'value' => '১৮৫ সেমি'],
                        ['key' => 'আলোর তাপমাত্রা', 'value' => '২৭০০K - ৫০০০K অ্যাডজাস্টেবল'],
                        ['key' => 'বেস ব্যাস', 'value' => '৩০ সেমি সলিড মেটাল']
                    ]
                ],
                'reviews' => []
            ],
            [
                'sku' => 'FO-DB-01',
                'category_slug' => 'fitness-outdoors',
                'brand_name' => 'IronCore',
                'slug' => 'adjustable-workout-dumbbells',
                'name' => [
                    'en' => 'IronCore Adjustable Dumbbell Set',
                    'es' => 'Juego de Mancuernas Ajustables IronCore',
                    'ar' => 'مجموعة أثقال دامبل الحديدية القابلة للتعديل',
                    'bn' => 'আয়রনকোর অ্যাডজাস্টেবল ডাম্বেল সেট'
                ],
                'description' => [
                    'en' => 'All-in-one dumbbell solution allowing adjustments from 5 lbs up to 52.5 lbs. Features durable mold plate construction and ergonomic grip handle.',
                    'es' => 'Solución todo en uno de mancuernas que permite ajustes de 5 a 52.5 libras. Placas moldeadas duraderas y mango de agarre ergonómico.',
                    'ar' => 'حل متكامل للأثقال يتيح لك التعديل من 5 أرطال إلى 52.5 رطلاً مع مقبض مريح.',
                    'bn' => '৫ পাউন্ড থেকে ৫২.৫ পাউন্ড পর্যন্ত ওজন পরিবর্তনের সুবিধা সহ অল-ইন-ওয়ান ডাম্বেল সমাধান।'
                ],
                'price' => 299.99,
                'compare_price' => 349.99,
                'stock' => 8,
                'avg_rating' => 4.8,
                'review_count' => 75,
                'is_best_seller' => true,
                'is_new' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1638536532686-d610adfc8e5c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    'sizes' => ['Single (52.5 lbs)', 'Pair (105 lbs)']
                ],
                'specs' => [
                    'en' => [
                        ['key' => 'Weight Increment', 'value' => '5, 7.5, 10, 12.5, 15, 17.5, 20... lbs'],
                        ['key' => 'Core Grip Material', 'value' => 'Textured Chrome Alloy']
                    ],
                    'es' => [
                        ['key' => 'Incremento de Peso', 'value' => '5, 7.5, 10, 12.5, 15, 17.5, 20... libras'],
                        ['key' => 'Material de Agarre', 'value' => 'Aleación de cromo texturizado']
                    ],
                    'ar' => [
                        ['key' => 'زيادات الوزن', 'value' => '5، 7.5، 10، 12.5، 15، 17.5، 20... رطل'],
                        ['key' => 'مادة المقبض الرئيسية', 'value' => 'سبائك كروم منسوجة']
                    ],
                    'bn' => [
                        ['key' => 'ওজন ইনক্রিমেন্ট', 'value' => '৫, ৭.৫, ১০, ১২.৫, ১৫, ১৭.৫, ২০... পাউন্ড'],
                        ['key' => 'গ্রিপের উপাদান', 'value' => 'টেক্সচার্ড ক্রোম অ্যালয়']
                    ]
                ],
                'reviews' => []
            ]
        ];

        foreach ($productsData as $prodData) {
            $brand = $brands[$prodData['brand_name']];
            $category = $categories[$prodData['category_slug']];

            $product = Product::create([
                'sku' => $prodData['sku'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'slug' => $prodData['slug'],
                'name' => $prodData['name'],
                'description' => $prodData['description'],
                'price' => $prodData['price'],
                'compare_price' => $prodData['compare_price'],
                'stock' => $prodData['stock'],
                'avg_rating' => $prodData['avg_rating'],
                'review_count' => $prodData['review_count'],
                'specs' => $prodData['specs'],
                'is_best_seller' => $prodData['is_best_seller'],
                'is_new' => $prodData['is_new']
            ]);

            // Seed images
            foreach ($prodData['images'] as $idx => $imgPath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imgPath,
                    'sort_order' => $idx,
                    'is_primary' => $idx === 0
                ]);
            }

            // Seed variants
            $variants = $prodData['variants'];
            if (isset($variants['colors']) && count($variants['colors']) > 0 && isset($variants['sizes']) && count($variants['sizes']) > 0) {
                foreach ($variants['colors'] as $color) {
                    foreach ($variants['sizes'] as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $product->sku . '-' . strtoupper(str_replace(' ', '', $color['name'])) . '-' . $size,
                            'attribute_set' => ['color' => $color['name'], 'color_val' => $color['value'], 'size' => $size],
                            'stock' => 5
                        ]);
                    }
                }
            } elseif (isset($variants['colors']) && count($variants['colors']) > 0) {
                foreach ($variants['colors'] as $color) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $product->sku . '-' . strtoupper(str_replace(' ', '', $color['name'])),
                        'attribute_set' => ['color' => $color['name'], 'color_val' => $color['value']],
                        'stock' => 5
                    ]);
                }
            } elseif (isset($variants['sizes']) && count($variants['sizes']) > 0) {
                foreach ($variants['sizes'] as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $product->sku . '-' . strtoupper(str_replace(' ', '', $size)),
                        'attribute_set' => ['size' => $size],
                        'stock' => 5
                    ]);
                }
            }

            // Seed reviews
            foreach ($prodData['reviews'] as $revData) {
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => null, // guest review
                    'rating' => $revData['rating'],
                    'comment' => $revData['comment'],
                    'images' => [],
                    'is_approved' => true
                ]);
            }
        }

        // 5. Seed Coupons
        Coupon::create([
            'code' => 'NUVARA20',
            'type' => 'percent',
            'value' => 20.00,
            'min_order' => 0.00,
        ]);
        Coupon::create([
            'code' => 'FREESHIP',
            'type' => 'free_shipping',
            'value' => 0.00,
            'min_order' => 150.00,
        ]);
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'flat',
            'value' => 10.00,
            'min_order' => 50.00,
        ]);

        // 6. Seed Demo Orders for Analytics Graphs
        $user = User::where('role', 'user')->first();
        if ($user) {
            $products = Product::all();
            
            // Seed daily orders (for last 7 days)
            $dayAmounts = [180.00, 310.00, 240.00, 420.00, 290.00, 580.00, 610.00];
            for ($i = 0; $i < 7; $i++) {
                $orderDate = now()->subDays(6 - $i);
                $total = $dayAmounts[$i];
                $order = \App\Models\Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'NVR-' . strtoupper(uniqid()),
                    'status' => 'delivered',
                    'subtotal' => $total - 15.00,
                    'discount' => 0.00,
                    'shipping_fee' => 15.00,
                    'total' => $total,
                    'currency' => 'USD',
                    'locale' => 'en',
                    'shipping_name' => $user->name,
                    'shipping_address' => '128 Pinecrest Ave',
                    'shipping_city' => 'San Francisco',
                    'shipping_state' => 'CA',
                    'shipping_zip' => '94110',
                    'shipping_country' => 'USA',
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
                
                $product = $products->random();
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'unit_price' => $total - 15.00,
                    'total' => $total - 15.00,
                    'variant_id' => null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
            }

            // Seed historical monthly orders (for last 6 months)
            $monthAmounts = [2400.00, 3100.00, 1800.00, 4500.00, 3800.00, 5800.00];
            for ($i = 0; $i < 6; $i++) {
                $orderDate = now()->subMonths(5 - $i);
                $total = $monthAmounts[$i];
                $order = \App\Models\Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'NVR-M-' . strtoupper(uniqid()),
                    'status' => 'delivered',
                    'subtotal' => $total - 10.00,
                    'discount' => 0.00,
                    'shipping_fee' => 10.00,
                    'total' => $total,
                    'currency' => 'USD',
                    'locale' => 'en',
                    'shipping_name' => $user->name,
                    'shipping_address' => '128 Pinecrest Ave',
                    'shipping_city' => 'San Francisco',
                    'shipping_state' => 'CA',
                    'shipping_zip' => '94110',
                    'shipping_country' => 'USA',
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
                
                $product = $products->random();
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'unit_price' => $total - 10.00,
                    'total' => $total - 10.00,
                    'variant_id' => null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
            }

            // Seed historical yearly orders (for last 3 years)
            $yearAmounts = [24000.00, 38000.00, 54000.00];
            for ($i = 0; $i < 3; $i++) {
                $orderDate = now()->subYears(2 - $i);
                $total = $yearAmounts[$i];
                $order = \App\Models\Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'NVR-Y-' . strtoupper(uniqid()),
                    'status' => 'delivered',
                    'subtotal' => $total - 10.00,
                    'discount' => 0.00,
                    'shipping_fee' => 10.00,
                    'total' => $total,
                    'currency' => 'USD',
                    'locale' => 'en',
                    'shipping_name' => $user->name,
                    'shipping_address' => '128 Pinecrest Ave',
                    'shipping_city' => 'San Francisco',
                    'shipping_state' => 'CA',
                    'shipping_zip' => '94110',
                    'shipping_country' => 'USA',
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
                
                $product = $products->random();
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'unit_price' => $total - 10.00,
                    'total' => $total - 10.00,
                    'variant_id' => null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate
                ]);
            }
        }
    }
}
