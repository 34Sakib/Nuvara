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
use App\Models\Banner;
use App\Models\TrustFeature;
use App\Models\FlashSale;
use App\Models\Testimonial;
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

        // 4. Seed Products
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
                    'en' => 'Experience ultimate sound quality with active noise cancellation, 40-hour battery life, and high-fidelity drivers.',
                    'es' => 'Disfruta de la mejor calidad de sonido con cancelación activa de ruido, 40 horas de batería y transductores de alta fidelidad.',
                    'ar' => 'استمتع بجودة صوت فائقة مع تقنية إلغاء الضوضاء النشطة، وعمر بطارية يصل إلى 40 ساعة.',
                    'bn' => 'অ্যাক্টিভ নয়েজ ক্যান্সেলেশন, ৪০ ঘণ্টার ব্যাটারি লাইফ এবং হাই-ফিডেলিটি ড্রাইভার সহ সেরা অভিজ্ঞতা।'
                ],
                'price' => 199.99,
                'compare_price' => 249.99,
                'stock' => 15,
                'avg_rating' => 4.8,
                'review_count' => 128,
                'is_best_seller' => true,
                'is_new' => false,
                'is_flash_deal' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80'
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
                    'en' => 'Track your health, monitor athletic performance, and stay connected with a stunning AMOLED screen.',
                    'es' => 'Monitorea tu salud y tu rendimiento deportivo con una pantalla AMOLED espectacular.',
                    'ar' => 'تتبع صحتك وراقب أدائك الرياضي مع شاشة AMOLED مذهلة.',
                    'bn' => 'অ্যামোলেড স্ক্রিন সহ আপনার স্বাস্থ্য এবং অ্যাথলেটিক পারফরম্যান্স ট্র্যাক করুন।'
                ],
                'price' => 129.99,
                'compare_price' => 159.99,
                'stock' => 4,
                'avg_rating' => 4.6,
                'review_count' => 94,
                'is_best_seller' => false,
                'is_new' => true,
                'is_flash_deal' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80'
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
                    'en' => 'A water-resistant, ultra-lightweight windbreaker designed for optimal movement.',
                    'es' => 'Un chubasquero resistente al agua y ultraligero.',
                    'ar' => 'سترة خفيفة الوزن للغاية ومقاومة للماء.',
                    'bn' => 'জল-প্রতিরোধী এবং অত্যন্ত হালকা উইন্ডব্রেকার জ্যাকেট।'
                ],
                'price' => 79.99,
                'compare_price' => 99.99,
                'stock' => 25,
                'avg_rating' => 4.7,
                'review_count' => 215,
                'is_best_seller' => true,
                'is_new' => false,
                'is_flash_deal' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=800&auto=format&fit=crop&q=80'
                ]
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
                    'en' => 'Crafted with premium breathable knit mesh and a highly cushioned responsive foam midsole.',
                    'es' => 'Fabricado con malla tejida transpirable premium y entresuela de espuma amortiguadora.',
                    'ar' => 'مصنوع من نسيج شبكي ممتاز ونعل أوسط رغوي مبطن.',
                    'bn' => 'প্রিমিয়াম নিট মেশ এবং কুশনযুক্ত ফোম মিডসোল দিয়ে তৈরি।'
                ],
                'price' => 89.99,
                'compare_price' => 119.99,
                'stock' => 10,
                'avg_rating' => 4.5,
                'review_count' => 42,
                'is_best_seller' => false,
                'is_new' => true,
                'is_flash_deal' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80'
                ]
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
                    'en' => 'Incorporate modern design into your living space with this sleek arc floor lamp.',
                    'es' => 'Incorpora el diseño moderno en tu sala con esta elegante lámpara.',
                    'ar' => 'أضف لمسة عصرية إلى مساحة المعيشة الخاصة بك مع هذا المصباح الأرضي المقوس.',
                    'bn' => 'আপনার বসার ঘরে আধুনিক ডিজাইন যোগ করুন এই ফ্লোর ল্যাম্পের সাথে।'
                ],
                'price' => 149.99,
                'compare_price' => 179.99,
                'stock' => 12,
                'avg_rating' => 4.9,
                'review_count' => 38,
                'is_best_seller' => true,
                'is_new' => true,
                'is_flash_deal' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80'
                ]
            ]
        ];

        $createdProducts = [];
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
                'is_best_seller' => $prodData['is_best_seller'],
                'is_new' => $prodData['is_new'],
                'is_flash_deal' => $prodData['is_flash_deal']
            ]);

            $createdProducts[] = $product;

            foreach ($prodData['images'] as $idx => $imgPath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imgPath,
                    'sort_order' => $idx,
                    'is_primary' => $idx === 0
                ]);
            }
        }

        // 5. Seed Banners (Hero Sliders & Promotional Banner)
        Banner::create([
            'type' => 'hero_slider',
            'title' => ['en' => 'Exclusive Sale', 'es' => 'Venta Exclusiva', 'ar' => 'بيع حصري', 'bn' => 'এক্সক্লুসিভ সেল'],
            'badge' => 'EXCLUSIVE',
            'badge_text' => 'Sale',
            'headline' => ['en' => 'Happening Now!', 'es' => '¡Sucediendo Ahora!', 'ar' => 'يحدث الآن!', 'bn' => 'এখনই চলছে!'],
            'sub' => [
                'en' => 'Discover amazing deals and discounts on our eCommerce website! Shop now for the best offers!',
                'es' => '¡Descubra increíbles ofertas y descuentos en nuestro sitio web!',
                'ar' => 'اكتشف عروضًا وخصومات مذهلة على موقعنا الإلكتروني!',
                'bn' => 'আমাদের ওয়েবসাইটে সেরা অফার ও ডিসকাউন্ট উপভোগ করুন! এখনই কেনাকাটা করুন!'
            ],
            'button_text' => ['en' => 'SHOP NOW', 'es' => 'COMPRAR AHORA', 'ar' => 'تسوق الآن', 'bn' => 'এখনই কিনুন'],
            'link' => '/category/electronics',
            'bg_gradient' => 'from-[#FDE047] via-[#FACC15] to-[#EAB308]',
            'text_color' => 'text-gray-950',
            'badge_bg' => 'bg-white text-black font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
            'btn_style' => 'bg-black text-white hover:bg-gray-800 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm',
            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
            'product_id' => $createdProducts[0]->id ?? null,
            'sort_order' => 1,
            'status' => true
        ]);

        Banner::create([
            'type' => 'hero_slider',
            'title' => ['en' => 'New Arrival Audio Pro', 'es' => 'Audio Pro', 'ar' => 'أوديو برو', 'bn' => 'অডিও প্রো'],
            'badge' => 'NEW ARRIVAL',
            'badge_text' => 'Audio Pro',
            'headline' => ['en' => 'Next-Gen Wireless Sound', 'es' => 'Sonido Inalámbrico de Última Generación', 'ar' => 'صوت لاسلكي من الجيل التالي', 'bn' => 'নেক্সট-জেন ওয়্যারলেস সাউন্ড'],
            'sub' => [
                'en' => 'Immerse yourself in crystal clear studio audio with ultra active noise cancellation.',
                'es' => 'Sumérgete en audio de estudio cristalino con cancelación activa de ruido.',
                'ar' => 'انغمس في صوت الاستوديو الكريستالي النقي مع إلغاء الضوضاء النشط.',
                'bn' => 'অ্যাক্টিভ নয়েজ ক্যান্সেলেশন সহ ক্রিস্টাল ক্লিয়ার স্টুডিও সাউন্ডের অনুভূতি পান।'
            ],
            'button_text' => ['en' => 'EXPLORE DEAL', 'es' => 'EXPLORAR OFERTA', 'ar' => 'استكشف العرض', 'bn' => 'অফার দেখুন'],
            'link' => '/product/wireless-noise-canceling-headphones',
            'bg_gradient' => 'from-[#10B981] via-[#059669] to-[#047857]',
            'text_color' => 'text-white',
            'badge_bg' => 'bg-emerald-300 text-emerald-950 font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
            'btn_style' => 'bg-white text-emerald-950 hover:bg-emerald-50 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm',
            'image' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80',
            'product_id' => $createdProducts[0]->id ?? null,
            'sort_order' => 2,
            'status' => true
        ]);

        Banner::create([
            'type' => 'hero_slider',
            'title' => ['en' => 'Hot Deal Style', 'es' => 'Estilo', 'ar' => 'أناقة', 'bn' => 'স্টাইল'],
            'badge' => 'HOT DEAL',
            'badge_text' => 'Style',
            'headline' => ['en' => 'Urban Lifestyle Fashion', 'es' => 'Moda de Estilo de Vida Urbano', 'ar' => 'أزياء نمط الحياة الحضري', 'bn' => 'আরবান লাইফস্টাইল ফ্যাশন'],
            'sub' => [
                'en' => 'Step out in confidence with our premium crafted street footwear & boutique fashion trends.',
                'es' => 'Camina con confianza con nuestro calzado urbano de primera calidad y moda boutique.',
                'ar' => 'انطلق بثقة مع أحذيتنا الأنيقة المصنوعة بجودة عالية واتجاهات الموضة الراقية.',
                'bn' => 'প্রিমিয়াম স্ট্রিট ফুটওয়্যার এবং বুটিক ফ্যাশন ট্রেন্ডের সাথে আত্মবিশ্বাসের সাথে পথ চলুন।'
            ],
            'button_text' => ['en' => 'SHOP FASHION', 'es' => 'COMPRAR MODA', 'ar' => 'تسوق الأزياء', 'bn' => 'ফ্যাশন শপ'],
            'link' => '/category/fashion',
            'bg_gradient' => 'from-[#F43F5E] via-[#E11D48] to-[#BE123C]',
            'text_color' => 'text-white',
            'badge_bg' => 'bg-white text-rose-950 font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
            'btn_style' => 'bg-gray-950 text-white hover:bg-gray-900 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm',
            'image' => 'https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=800&auto=format&fit=crop&q=80',
            'product_id' => $createdProducts[2]->id ?? null,
            'sort_order' => 3,
            'status' => true
        ]);

        Banner::create([
            'type' => 'promo_banner',
            'title' => ['en' => 'Limited Edition Decor', 'es' => 'Decoración de Edición Limitada', 'ar' => 'ديكور طبعة محدودة', 'bn' => 'লিমিতেড এডিশন ডেকোর'],
            'badge' => 'LIMITED EDITION',
            'badge_text' => 'Decor',
            'headline' => ['en' => 'Modern Living & Home Collection', 'es' => 'Colección de Hogar y Vida Moderna', 'ar' => 'تشكيلة المنزل والمعيشة العصرية', 'bn' => 'মডার্ন লিভিং ও হোম কালেকশন'],
            'sub' => [
                'en' => 'Redefine your living space with minimal aesthetic lighting and smart home accessories.',
                'es' => 'Redefina su espacio vital con iluminación estética mínima y accesorios para el hogar inteligente.',
                'ar' => 'أعد تعريف مساحة معيشتك مع إضاءة جمالية بسيطة وإكسسوارات منزلية ذكية.',
                'bn' => 'ন্যূনতম নান্দনিক লাইটিং এবং স্মার্ট হোম এক্সেসরিজ সহ আপনার থাকার জায়গাটি পুনর্নির্মাণ করুন।'
            ],
            'button_text' => ['en' => 'EXPLORE COLLECTION', 'es' => 'EXPLORAR COLECCIÓN', 'ar' => 'استكشف التشكيلة', 'bn' => 'কালেকশন দেখুন'],
            'link' => '/category/home-living',
            'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1200&auto=format&fit=crop&q=80',
            'sort_order' => 1,
            'status' => true
        ]);

        // 6. Seed Trust Features
        TrustFeature::create([
            'feature_key' => 'free_shipping',
            'title' => ['en' => 'Free Shipping', 'es' => 'Envío Gratis', 'ar' => 'شحن مجاني', 'bn' => 'ফ্রি শিপিং'],
            'sub' => ['en' => 'Free shipping on orders over $150', 'es' => 'Envío gratis en pedidos superiores a $150', 'ar' => 'شحن مجاني للطلبات فوق 150 دولار', 'bn' => '১৫০ ডলারের উপরে অর্ডারে ফ্রি শিপিং'],
            'icon' => 'Truck',
            'icon_color' => 'text-emerald-500 dark:text-emerald-400',
            'bg_color' => 'bg-emerald-500/10 dark:bg-emerald-500/15',
            'sort_order' => 1,
            'status' => true
        ]);

        TrustFeature::create([
            'feature_key' => 'secure_payment',
            'title' => ['en' => '100% Secure Payment', 'es' => 'Pago 100% Seguro', 'ar' => 'دفع آمن 100%', 'bn' => '১০০% নিরাপদ পেমেন্ট'],
            'sub' => ['en' => 'Protected by 256-bit encryption', 'es' => 'Protegido por cifrado de 256 bits', 'ar' => 'محمي بتشفير 256 بت', 'bn' => '২৫৬-বিট এনক্রিপশন দ্বারা সুরক্ষিত'],
            'icon' => 'ShieldCheck',
            'icon_color' => 'text-amber-500 dark:text-amber-400',
            'bg_color' => 'bg-amber-500/10 dark:bg-amber-500/15',
            'sort_order' => 2,
            'status' => true
        ]);

        TrustFeature::create([
            'feature_key' => 'easy_returns',
            'title' => ['en' => 'Easy 30-Day Returns', 'es' => 'Devolución Fácil 30 Días', 'ar' => 'إرجاع سهل خلال 30 يومًا', 'bn' => 'সহজ ৩০ দিনের রিটার্ন'],
            'sub' => ['en' => 'Hassle-free return policy', 'es' => 'Política de devolución sin complicaciones', 'ar' => 'سياسة إرجاع خالية من المتاعب', 'bn' => 'ঝামেলামুক্ত রিটার্ন পলিসি'],
            'icon' => 'RefreshCw',
            'icon_color' => 'text-blue-500 dark:text-blue-400',
            'bg_color' => 'bg-blue-500/10 dark:bg-blue-500/15',
            'sort_order' => 3,
            'status' => true
        ]);

        TrustFeature::create([
            'feature_key' => 'support',
            'title' => ['en' => '24/7 Support', 'es' => 'Soporte 24/7', 'ar' => 'دعم على مدار الساعة 24/7', 'bn' => '২৪/৭ সার্বক্ষণিক সহায়তা'],
            'sub' => ['en' => 'Dedicated customer helpdesk', 'es' => 'Mesa de ayuda al cliente dedicada', 'ar' => 'مكتب مساعدة عملاء مخصص', 'bn' => 'ডেডিকেটেড কাস্টমার হেল্পডেস্ক'],
            'icon' => 'Headphones',
            'icon_color' => 'text-purple-500 dark:text-purple-400',
            'bg_color' => 'bg-purple-500/10 dark:bg-purple-500/15',
            'sort_order' => 4,
            'status' => true
        ]);

        // 7. Seed Flash Sales Campaign
        FlashSale::create([
            'title' => ['en' => 'Flash Deals of the Week', 'es' => 'Ofertas Flash de la Semana', 'ar' => 'عروض ترويجية للأسبوع', 'bn' => 'সপ্তাহের ফ্লাশ ডিল'],
            'ends_at' => now()->addHours(4)->addMinutes(34)->addSeconds(12),
            'discount_label' => 'Up to 30% OFF',
            'status' => true
        ]);

        // 8. Seed Customer Testimonials
        Testimonial::create([
            'name' => 'Israt Jahan',
            'rating' => 5,
            'quote' => [
                'en' => 'Nuvara completely changed my online shopping experience. Shipping was fast and the quality was top-notch.',
                'es' => 'Nuvara cambió por completo mi experiencia de compra. El envío fue rápido y la calidad de primera.',
                'ar' => 'غيّرت نوفارا تجربتي في التسوق عبر الإنترنت تمامًا. الشحن كان سريعًا والجودة كانت ممتازة.',
                'bn' => 'নোভারা আমার অনলাইন শপিংয়ের অভিজ্ঞতা পুরোপুরি বদলে দিয়েছে। খুব দ্রুত শিপিং পেয়েছি এবং কোয়ালিটি ছিল দারুণ।'
            ],
            'is_featured' => true,
            'sort_order' => 1,
            'status' => true
        ]);

        Testimonial::create([
            'name' => 'Diego R.',
            'rating' => 5,
            'quote' => [
                'en' => 'The customer service team is incredibly helpful, and the Arabic font support made checkout so natural.',
                'es' => 'El servicio al cliente es excelente y la facilidad de pago fue impresionante.',
                'ar' => 'فريق خدمة العملاء متعاون للغاية، ودعم اللغة العربية جعل تجربة الدفع طبيعية وسهلة.',
                'bn' => 'গ্রাহক সেবা দল অত্যন্ত সাহায্যকারী এবং ড্যাশবোর্ডটি ব্যবহার করা খুবই সহজ ছিল।'
            ],
            'is_featured' => true,
            'sort_order' => 2,
            'status' => true
        ]);

        // 9. Seed Coupons
        Coupon::create(['code' => 'NUVARA20', 'type' => 'percent', 'value' => 20.00, 'min_order' => 0.00]);
        Coupon::create(['code' => 'FREESHIP', 'type' => 'free_shipping', 'value' => 0.00, 'min_order' => 150.00]);
        Coupon::create(['code' => 'WELCOME10', 'type' => 'flat', 'value' => 10.00, 'min_order' => 50.00]);

        // 10. Seed About Us Dynamic Content
        \App\Models\PageContent::updateOrCreate(
            ['page_key' => 'about'],
            [
                'content' => [
                    'hero_badge' => ['en' => 'Established 2026 • Global Commerce', 'bn' => 'প্রতিষ্ঠিত ২০২৬ • গ্লোবাল কমার্স'],
                    'hero_title' => ['en' => 'Redefining Localized E-Commerce Worldwide', 'bn' => 'বিশ্বজুড়ে রিডিফাইনিং লোকালাইজড ই-কমার্স'],
                    'hero_subtitle' => [
                        'en' => 'At Nuvara, we bridge cultural boundaries through intelligent multi-language support, seamless right-to-left document flow, and curated high-fidelity product offerings.',
                        'bn' => 'নোভারাতে, আমরা বুদ্ধিমান বহুধাবিধ ভাষা সহায়তা, মসৃণ রাইট-টু-লেফ্ট পেজ এবং মানসম্পন্ন প্রোডাক্টের মাধ্যমে সাংস্কৃতিক দূরত্ব দূর করি।'
                    ],
                    'stats' => [
                        ['label' => ['en' => 'Global Customers', 'bn' => 'বিশ্বব্যাপী গ্রাহক'], 'value' => '150,000+', 'icon' => 'Users', 'color' => 'text-amber-500 bg-amber-500/10'],
                        ['label' => ['en' => 'Satisfaction Rate', 'bn' => 'সন্তুষ্টির হার'], 'value' => '99.8%', 'icon' => 'Award', 'color' => 'text-emerald-500 bg-emerald-500/10'],
                        ['label' => ['en' => 'Supported Languages', 'bn' => 'সমর্থিত ভাষা'], 'value' => '4 Native', 'icon' => 'Globe', 'color' => 'text-indigo-500 bg-indigo-500/10'],
                        ['label' => ['en' => 'Quality Guarantee', 'bn' => 'গুণমানের গ্যারান্টি'], 'value' => '30-Day', 'icon' => 'ShieldCheck', 'color' => 'text-rose-500 bg-rose-500/10']
                    ],
                    'story_title' => ['en' => 'Borderlessly Connecting Buyers & Premium Brands', 'bn' => 'সীমানাহীনভাবে যুক্ত করছে ক্রেতা ও প্রিমিয়াম ব্র্যান্ড'],
                    'story_body' => [
                        'en' => 'Founded with the vision that online shopping should never feel foreign or clunky, Nuvara was engineered from the ground up to support instant multi-locale switching, right-to-left layout perfection, and transparent localized pricing.',
                        'bn' => 'অনলাইন কেনাকাটা যাতে কখনও অপরিচিত মনে না হয় সেই লক্ষ্য নিয়ে গঠিত, নোভারা শুরু থেকেই তাৎক্ষণিক বহু-ভাষা এবং বিশ্বস্ত কেনাকাটার সুবিধা দিচ্ছে।'
                    ],
                    'team' => [
                        [
                            'name' => 'Elena Vance',
                            'role' => ['en' => 'Founder & CEO', 'bn' => 'প্রতিষ্ঠাতা ও সিইও'],
                            'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80',
                            'bio' => ['en' => 'Pioneering global digital commerce with a focus on native user experiences.', 'bn' => 'ইউজার এক্সপেরিয়েন্সের সাথে আন্তর্জাতিক ডিজিটাল বাণিজ্য পরিচালনা।']
                        ],
                        [
                            'name' => 'Marcus Chen',
                            'role' => ['en' => 'Head of Product Design', 'bn' => 'প্রধান প্রোডাক্ট ডিজাইন'],
                            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80',
                            'bio' => ['en' => 'Crafting minimalist, intuitive interfaces tailored for diverse worldwide cultures.', 'bn' => 'বিশ্বব্যাপী সংস্কৃতির জন্য মিনিমালিস্ট ইন্টারফেস তৈরি করছেন।']
                        ],
                        [
                            'name' => 'Aisha Al-Mansoor',
                            'role' => ['en' => 'Chief Technology Officer', 'bn' => 'প্রধান প্রযুক্তি কর্মকর্তা'],
                            'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&auto=format&fit=crop&q=80',
                            'bio' => ['en' => 'Building ultra-resilient, lightning-fast architecture for international scale.', 'bn' => 'আন্তর্জাতিক স্কেলের জন্য অত্যন্ত দ্রুত আর্কিটেকচার তৈরি করছেন।']
                        ]
                    ]
                ]
            ]
        );

        // 11. Seed Contact Dynamic Content
        \App\Models\PageContent::updateOrCreate(
            ['page_key' => 'contact'],
            [
                'content' => [
                    'hero_badge' => ['en' => '24/7 Multilingual Support Hub', 'bn' => '২৪/৭ বহুমুখী সাহায্য কেন্দ্র'],
                    'hero_title' => ['en' => 'Get In Touch With Us', 'bn' => 'আমাদের সাথে যোগাযোগ করুন'],
                    'hero_subtitle' => [
                        'en' => 'Have a question about an order, localized payments, or custom boutique recommendations? Our global team is here to help anytime.',
                        'bn' => 'অর্ডার বা পেমেন্ট সংক্রান্ত প্রশ্ন আছে? আমাদের সাপোর্ট টিম আপনাকে সাহায্য করতে প্রস্তুত।'
                    ],
                    'cards' => [
                        [
                            'title' => ['en' => 'Customer Support', 'bn' => 'কাস্টমার সাপোর্ট'],
                            'value' => 'support@nuvara.com',
                            'sub' => ['en' => 'Response within 2 hours', 'bn' => '২ ঘণ্টার মধ্যে উত্তর'],
                            'icon' => 'Mail',
                            'color' => 'text-blue-500 bg-blue-500/10'
                        ],
                        [
                            'title' => ['en' => 'Direct Hotline', 'bn' => 'হটলাইন নম্বর'],
                            'value' => '+1 (800) 555-NUVARA',
                            'sub' => ['en' => 'Mon - Sun, 24/7 Hotline', 'bn' => 'সোম - রবি, ২৪/৭ হটলাইন'],
                            'icon' => 'Phone',
                            'color' => 'text-emerald-500 bg-emerald-500/10'
                        ],
                        [
                            'title' => ['en' => 'Headquarters', 'bn' => 'প্রধান কার্যালয়'],
                            'value' => 'San Francisco, CA',
                            'sub' => ['en' => '100 Embassy Row, Suite 400', 'bn' => '১০০ এম্বাসি রো, স্যুট ৪০০'],
                            'icon' => 'MapPin',
                            'color' => 'text-indigo-500 bg-indigo-500/10'
                        ]
                    ]
                ]
            ]
        );

        // 12. Seed Dynamic FAQs
        $faqsData = [
            [
                'category' => 'shipping',
                'question' => ['en' => 'What countries does Nuvara ship to?', 'bn' => 'নোভারা কোন কোন দেশে শিপিং করে?'],
                'answer' => [
                    'en' => 'Nuvara delivers worldwide to over 140 countries with express tracked shipping partners including DHL, FedEx, and localized regional postal networks.',
                    'bn' => 'নোভারা ডিএইচএল এবং ফেডেক্স সহ বিশ্বস্ত আন্তর্জাতিক শিপিং পার্টনারদের মাধ্যমে ১৪০টিরও বেশি দেশে ডেলিভারি প্রদান করে।'
                ],
                'sort_order' => 1
            ],
            [
                'category' => 'shipping',
                'question' => ['en' => 'How can I track my live order dispatch status?', 'bn' => 'আমি কীভাবে আমার অর্ডারের লাইভ ট্র্যাকিং চেক করব?'],
                'answer' => [
                    'en' => 'Once your package leaves our fulfillment hubs, you will receive an automated email and SMS notification containing a unique live tracking URL link.',
                    'bn' => 'প্যাকেজটি শিপমেন্ট সেন্টারের থেকে বের হওয়া মাত্রই একটি ট্র্যাকিং লিংকসহ ইমেইল পাবেন।'
                ],
                'sort_order' => 2
            ],
            [
                'category' => 'returns',
                'question' => ['en' => 'What is your hassle-free 30-day return policy?', 'bn' => '৩০ দিনের রিটার্ন পলিসি কীভাবে কাজ করে?'],
                'answer' => [
                    'en' => 'If you are not 100% satisfied with your item, you can initiate a zero-cost return request within 30 days of package receipt in your account dashboard.',
                    'bn' => 'আপনি যদি প্রোডাক্ট নিয়ে সন্তুষ্ট না হন, তবে প্যাকেজ গ্রহণের ৩০ দিনের মধ্যে সম্পূর্ণ বিনামূল্যে রিটার্ন রিকুয়েস্ট দিতে পারেন।'
                ],
                'sort_order' => 3
            ],
            [
                'category' => 'payment',
                'question' => ['en' => 'What localized payment methods do you support?', 'bn' => 'আপনারা কোন কোন পেমেন্ট পদ্ধতি সমর্থন করেন?'],
                'answer' => [
                    'en' => 'We accept all major global credit cards (Visa, Mastercard, Amex), Apple Pay, Google Pay, bKash, and local cash on delivery (COD).',
                    'bn' => 'আমরা ভিসা, মাস্টারকার্ড, বিকাশ, অ্যাপল পে এবং ক্যাশ অন ডেলিভারি সাপোর্ট করি।'
                ],
                'sort_order' => 4
            ],
            [
                'category' => 'general',
                'question' => ['en' => 'How does Right-to-Left (RTL) mode work on Nuvara?', 'bn' => 'রাইট-টু-লেফ্ট (RTL) মোড কীভাবে কাজ করে?'],
                'answer' => [
                    'en' => 'Selecting Arabic (العربية) from the header language dropdown automatically mirrors the entire layout, icons, and menus natively for seamless reading.',
                    'bn' => 'হেডারের ভাষা থেকে আরবি নির্বাচন করলে সম্পূর্ণ ওয়েবসাইটটি স্বয়ংক্রিয়ভাবে ডান-থেকে-বামে রূপান্তরিত হয়।'
                ],
                'sort_order' => 5
            ]
        ];

        foreach ($faqsData as $faq) {
            \App\Models\Faq::create($faq);
        }
    }
}

