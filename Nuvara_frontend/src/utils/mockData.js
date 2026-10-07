// Helper to resolve localized data (similar to Spatie Laravel Translatable)
export const getLocalized = (fieldObj, locale) => {
  if (!fieldObj) return '';
  if (typeof fieldObj === 'string') return fieldObj;
  return fieldObj[locale] || fieldObj['en'] || Object.values(fieldObj)[0] || '';
};

export const mockCategories = [
  {
    id: 'cat-1',
    slug: 'electronics',
    name: {
      en: 'Electronics & Sound',
      es: 'Electrónica y Sonido',
      ar: 'إلكترونيات وصوتيات',
      bn: 'ইলেকট্রনিক্স ও সাউন্ড'
    },
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
    itemCount: 3
  },
  {
    id: 'cat-2',
    slug: 'fashion',
    name: {
      en: 'Fashion & Apparel',
      es: 'Moda y Ropa',
      ar: 'الأزياء والملابس',
      bn: 'ফ্যাশন ও পোশাক'
    },
    image: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&auto=format&fit=crop&q=80',
    itemCount: 3
  },
  {
    id: 'cat-3',
    slug: 'home-living',
    name: {
      en: 'Home & Living',
      es: 'Hogar y Decoración',
      ar: 'المنزل والمعيشة',
      bn: 'হোম ও লিভিং'
    },
    image: 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800&auto=format&fit=crop&q=80',
    itemCount: 3
  },
  {
    id: 'cat-4',
    slug: 'fitness-outdoors',
    name: {
      en: 'Fitness & Outdoors',
      es: 'Deportes y Aire Libre',
      ar: 'الرياضة واللياقة',
      bn: 'ফিটনেস ও আউটডোর'
    },
    image: 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=800&auto=format&fit=crop&q=80',
    itemCount: 3
  },
  {
    id: 'cat-5',
    slug: 'kitchen-dining',
    name: {
      en: 'Kitchen & Dining',
      es: 'Cocina y Comedor',
      ar: 'المطبخ وتناول الطعام',
      bn: 'রান্নাঘর ও ডাইনিং'
    },
    image: 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=800&auto=format&fit=crop&q=80',
    itemCount: 3
  },
  {
    id: 'cat-6',
    slug: 'beauty-wellness',
    name: {
      en: 'Beauty & Wellness',
      es: 'Belleza y Bienestar',
      ar: 'الجمال والعناية الشخصية',
      bn: 'বিউটি ও ওয়েলনেস'
    },
    image: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=800&auto=format&fit=crop&q=80',
    itemCount: 3
  }
];

export const mockProducts = [
  // ELECTRONICS
  {
    id: 'prod-1',
    sku: 'EL-HP-01',
    category_id: 'cat-1',
    category: 'Electronics & Sound',
    brand: 'AeroSound',
    slug: 'wireless-noise-canceling-headphones',
    name: {
      en: 'AeroSound Pro Wireless Headphones',
      es: 'Auriculares Inalámbricos AeroSound Pro',
      ar: 'سماعات الرأس اللاسلكية إيروساوند برو',
      bn: 'অ্যারোসাউন্ড প্রো ওয়্যারলেস হেডফোন'
    },
    description: {
      en: 'Experience ultimate sound quality with active noise cancellation, 40-hour battery life, and high-fidelity custom drivers.',
      es: 'Disfruta de la mejor calidad de sonido con cancelación activa de ruido, 40 horas de batería y transductores de alta fidelidad.',
      ar: 'استمتع بجودة صوت فائقة مع تقنية إلغاء الضوضاء النشطة، وعمر بطارية يصل إلى 40 ساعة ومحركات صوتية عالية الدقة.',
      bn: 'অ্যাক্টিভ নয়েজ ক্যান্সেলেশন, ৪০ ঘণ্টার ব্যাটারি লাইফ এবং হাই-ফিডেলিটি কাস্টম ড্রাইভার সহ প্রিমিয়াম অডিও অভিজ্ঞতা।'
    },
    price: 199.99,
    compare_price: 249.99,
    stock: 18,
    rating: 4.9,
    reviewCount: 128,
    isBestSeller: true,
    isNew: false,
    images: [
      'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-2',
    sku: 'EL-SW-02',
    category_id: 'cat-1',
    category: 'Electronics & Sound',
    brand: 'Krono',
    slug: 'active-chronograph-smartwatch',
    name: {
      en: 'Krono Active Smartwatch v3',
      es: 'Reloj Inteligente Krono Active v3',
      ar: 'ساعة كرونو أكتيف الذكية الإصدار الثالث',
      bn: 'ক্রোনো অ্যাক্টিভ স্মার্টওয়াচ সংস্করণ ৩'
    },
    description: {
      en: 'Track your health, monitor athletic performance, and stay connected with a stunning sapphire AMOLED display.',
      es: 'Monitorea tu salud y tu rendimiento deportivo con una pantalla AMOLED de zafiro espectacular.',
      ar: 'تتبع صحتك وراقب أدائك الرياضي مع شاشة AMOLED الياقوتية المذهلة.',
      bn: 'স্যাফায়ার অ্যামোলেড ডিসপ্লে সহ আপনার স্বাস্থ্য ও খেলাধুলার পারফরম্যান্স নির্ভুলভাবে ট্র্যাক করুন।'
    },
    price: 149.99,
    compare_price: 189.99,
    stock: 14,
    rating: 4.8,
    reviewCount: 94,
    isBestSeller: false,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-3',
    sku: 'EL-SP-03',
    category_id: 'cat-1',
    category: 'Electronics & Sound',
    brand: 'AeroSound',
    slug: 'acoustic-wood-bluetooth-speaker',
    name: {
      en: 'AeroSound Horizon Wooden Speaker',
      es: 'Altavoz de Madera AeroSound Horizon',
      ar: 'مكبر صوت خشبي إيروساوند هورايزون',
      bn: 'অ্যারোসাউন্ড হরাইজন উডেন ব্লুটুথ স্পিকার'
    },
    description: {
      en: 'Natural walnut wood casing delivering rich acoustic resonance, 360-degree room-filling spatial sound, and Bluetooth 5.3.',
      es: 'Carcasa de madera de nogal natural que ofrece una resonancia acústica rica y sonido envolvente de 360 grados.',
      ar: 'هيكل من خشب الجوز الطبيعي يوفر رنينًا صوتيًا غنيًا وصوتًا محيطيًا بزاوية 360 درجة.',
      bn: 'প্রাকৃতিক আখরোট কাঠের কেসিং যা গভীর রেজোন্যান্স ও ৩৬০ ডিগ্রি স্পেশাল সাউন্ড নিশ্চিত করে।'
    },
    price: 179.00,
    compare_price: 220.00,
    stock: 9,
    rating: 4.9,
    reviewCount: 67,
    isBestSeller: true,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800&auto=format&fit=crop&q=80'
    ]
  },

  // FASHION & APPAREL
  {
    id: 'prod-4',
    sku: 'FA-JK-01',
    category_id: 'cat-2',
    category: 'Fashion & Apparel',
    brand: 'Vanguard',
    slug: 'all-weather-windbreaker-jacket',
    name: {
      en: 'Vanguard All-Weather Technical Jacket',
      es: 'Chaqueta Técnica Vanguard Todo Clima',
      ar: 'سترة تقنية فانغارد لكل الأحوال الجوية',
      bn: 'ভ্যানগার্ড অল-ওয়েদার টেকনিক্যাল জ্যাকেট'
    },
    description: {
      en: 'Water-resistant, breathable 3-layer shell designed for effortless movement in city rain or mountain trails.',
      es: 'Capa impermeable y transpirable de 3 capas diseñada para un movimiento sin esfuerzo bajo la lluvia.',
      ar: 'غلاف مقاوم للماء وجيد التهوية مكون من 3 طبقات مصمم لسهولة الحركة تحت المطر.',
      bn: 'জল-প্রতিরোধী এবং অত্যন্ত শ্বাস-প্রশ্বাসযোগ্য ৩-লেয়ার শেল জ্যাকেট।'
    },
    price: 119.00,
    compare_price: 149.00,
    stock: 24,
    rating: 4.7,
    reviewCount: 215,
    isBestSeller: true,
    isNew: false,
    images: [
      'https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-5',
    sku: 'FA-SN-02',
    category_id: 'cat-2',
    category: 'Fashion & Apparel',
    brand: 'Stride',
    slug: 'urban-runner-knit-sneakers',
    name: {
      en: 'Stride Urban Runner Knit Sneakers',
      es: 'Zapatillas de Punto Stride Urban Runner',
      ar: 'حذاء الجري سترايد إربان رانر المنسوج',
      bn: 'স্ট্রাইড আরবান রানার নিট স্নিকার্স'
    },
    description: {
      en: 'Crafted with recycled ocean knit yarn and an ultra-plush rebound foam midsole for cloud-like comfort.',
      es: 'Fabricado con hilo reciclado y una entresuela de espuma reactiva para una comodidad excepcional.',
      ar: 'مصنوع من نسيج معاد تدويره ونعل أوسط رغوي مبطن يوفر راحة تشبه المشي على السحاب.',
      bn: 'রিসাইকেলড ওশান সুতা এবং আল্ট্রা-কুশনযুক্ত রিবাউন্ড ফোম মিডসোল দিয়ে তৈরি আরামদায়ক জুতো।'
    },
    price: 95.00,
    compare_price: 125.00,
    stock: 16,
    rating: 4.6,
    reviewCount: 84,
    isBestSeller: false,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-6',
    sku: 'FA-BG-03',
    category_id: 'cat-2',
    category: 'Fashion & Apparel',
    brand: 'NordicCraft',
    slug: 'minimalist-leather-commuter-tote',
    name: {
      en: 'NordicCraft Full-Grain Leather Tote',
      es: 'Bolso Tote de Cuero Genuino NordicCraft',
      ar: 'حقيبة يد نورديك كرافت من الجلد الطبيعي',
      bn: 'নরডিকক্রাফট ফুল-গ্রেন লেদার টোট ব্যাগ'
    },
    description: {
      en: 'Vegetable-tanned full-grain leather with dedicated 15-inch laptop compartment and reinforced brass hardware.',
      es: 'Cuero curtido vegetal de primera calidad con compartimento acolchado para portátil de 15 pulgadas.',
      ar: 'جلد طبيعي مدبوغ نباتيًا مع حجرة مخصصة للكمبيوتر المحمول ومقابض نحاسية متينة.',
      bn: 'ভেজিটেবল-ট্যানড ফুল-গ্রেন চামড়া, ১৫ ইঞ্চি ল্যাপটপ চেম্বার এবং শক্ত ব্রাস হার্ডওয়্যার সহ তৈরি।'
    },
    price: 165.00,
    compare_price: 210.00,
    stock: 11,
    rating: 4.9,
    reviewCount: 53,
    isBestSeller: true,
    isNew: false,
    images: [
      'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=800&auto=format&fit=crop&q=80'
    ]
  },

  // HOME & LIVING
  {
    id: 'prod-7',
    sku: 'HL-LP-01',
    category_id: 'cat-3',
    category: 'Home & Living',
    brand: 'Luminaire',
    slug: 'minimalist-arc-floor-lamp',
    name: {
      en: 'Luminaire Minimalist Arc Lamp',
      es: 'Lámpara de Pie Minimalista Luminaire',
      ar: 'مصباح أرضي مقوس لومينير مينيماليست',
      bn: 'লুমিনায়ার মিনিমালিস্ট আর্ক ফ্লোর ল্যাম্প'
    },
    description: {
      en: 'Architectural brass arc with dimmable warm LED diffusion and a heavy white marble stability base.',
      es: 'Arco arquitectónico de latón con luz LED cálida regulable y base de mármol blanco.',
      ar: 'قوس نحاسي معماري أنيق مع إضاءة LED دافئة قابلة للتعتيم وقاعدة رخامية بيضاء متينة.',
      bn: 'আর্কিটেকচারাল ব্রাস আর্ক, ডিমেবল ওয়ার্ম এলইডি আলো এবং হেভি মার্বেল বেসের নিখুঁত সংমিশ্রণ।'
    },
    price: 189.00,
    compare_price: 235.00,
    stock: 8,
    rating: 4.9,
    reviewCount: 76,
    isBestSeller: true,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-8',
    sku: 'HL-VS-02',
    category_id: 'cat-3',
    category: 'Home & Living',
    brand: 'TerraStudio',
    slug: 'artisanal-wabi-sabi-ceramic-vase',
    name: {
      en: 'TerraStudio Wabi-Sabi Ceramic Vase',
      es: 'Jarrón de Cerámica Wabi-Sabi TerraStudio',
      ar: 'مزهرية سيراميك تيرا استوديو وابي سابي',
      bn: 'টেরাস্টুডিও ওয়াবি-সাবি সিরামিক ফুলদানি'
    },
    description: {
      en: 'Handcrafted unglazed terracotta ceramic vase celebrating organic textures and sculptural simplicity.',
      es: 'Jarrón de cerámica de terracota hecho a mano con texturas orgánicas y simplicidad escultural.',
      ar: 'مزهرية من الطين النقي مصنوعة يدويًا تحتفي بالقوام الطبيعي والأناقة المنحوتة.',
      bn: 'হাতে তৈরি খাঁটি টেরাকোটা সিরামিক যা ঘরের ভেতরে প্রাকৃতিক শিল্প ও প্রশান্তি বয়ে আনে।'
    },
    price: 64.00,
    compare_price: 80.00,
    stock: 22,
    rating: 4.8,
    reviewCount: 41,
    isBestSeller: false,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1612196808214-b8e1d6145a8c?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-9',
    sku: 'HL-BL-03',
    category_id: 'cat-3',
    category: 'Home & Living',
    brand: 'NordicCraft',
    slug: 'stonewashed-pure-linen-throw',
    name: {
      en: 'NordicCraft Stonewashed Linen Throw',
      es: 'Manta de Lino Lavado a la Piedra',
      ar: 'غطاء من الكتان المغسول بالأحجار',
      bn: 'নরডিকক্রাফট স্টোনওয়াশড পিওর লিনেন থ্রো'
    },
    description: {
      en: '100% French flax linen pre-washed for effortless softness, thermo-regulating breathability all year round.',
      es: 'Lino 100% francés prelavado para una suavidad inigualable y transpirabilidad térmica.',
      ar: 'كتان فرنسي نقي 100% مغسول مسبقًا لنعومة لا مثيل لها وتنظيم حراري مثالي.',
      bn: '১০০% ফরাসি ফ্ল্যাক্স লিনেন দিয়ে তৈরি যা সব ঋতুতেই আরামদায়ক ও নরম উষ্ণতা দেয়।'
    },
    price: 88.00,
    compare_price: 110.00,
    stock: 15,
    rating: 4.7,
    reviewCount: 32,
    isBestSeller: false,
    isNew: false,
    images: [
      'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?w=800&auto=format&fit=crop&q=80'
    ]
  },

  // FITNESS & OUTDOORS
  {
    id: 'prod-10',
    sku: 'FO-YM-01',
    category_id: 'cat-4',
    category: 'Fitness & Outdoors',
    brand: 'Stride',
    slug: 'natural-tree-rubber-yoga-mat',
    name: {
      en: 'Stride Pro Alignment Yoga Mat',
      es: 'Esterilla de Yoga con Guías Stride Pro',
      ar: 'سجادة اليوغا الاحترافية سترايد برو',
      bn: 'স্ট্রাইড প্রো অ্যালাইনমেন্ট যোগা ম্যাট'
    },
    description: {
      en: 'Sustainable natural tree rubber base with non-slip polyurethane top and laser-etched posture alignment grid.',
      es: 'Base de caucho natural con superficie antideslizante y guías de alineación grabadas con láser.',
      ar: 'قاعدة من المطاط الطبيعي المستدام مع سطح مانع للانزلاق وخطوط توجيه محفورة بالليزر.',
      bn: 'প্রাকৃতিক রাবার বেস, অ্যান্টি-স্লিপ গ্রিপ এবং লেজার প্রিন্টেড বডি অ্যালাইনমেন্ট লাইন।'
    },
    price: 78.00,
    compare_price: 95.00,
    stock: 19,
    rating: 4.9,
    reviewCount: 98,
    isBestSeller: true,
    isNew: false,
    images: [
      'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-11',
    sku: 'FO-WF-02',
    category_id: 'cat-4',
    category: 'Fitness & Outdoors',
    brand: 'Vanguard',
    slug: 'insulated-titanium-water-flask',
    name: {
      en: 'Vanguard Vacuum Insulated Steel Flask (750ml)',
      es: 'Botella Térmica de Acero Inoxidable (750ml)',
      ar: 'قارورة ماء معزولة من الفولاذ المقاوم للصدأ (750 مل)',
      bn: 'ভ্যানগার্ড ভ্যাকিউম ইনসুলেটেড ওয়াটার ফ্লাস্ক (৭৫০ মিলি)'
    },
    description: {
      en: 'Double-walled copper lining keeps liquids ice-cold for 24 hours or steaming hot for 12 hours. Leak-proof cap.',
      es: 'Doble pared que mantiene las bebidas frías durante 24 horas o calientes durante 12 horas.',
      ar: 'عزل حراري مزدوج يحافظ على المشروبات باردة لمدة 24 ساعة أو ساخنة لمدة 12 ساعة.',
      bn: 'ডাবল-ওয়াল্ড কপার লাইনিং যা ২৪ ঘণ্টা বরফ-ঠান্ডা ও ১২ ঘণ্টা গরম তাপমাত্রা ধরে রাখে।'
    },
    price: 38.00,
    compare_price: 48.00,
    stock: 35,
    rating: 4.8,
    reviewCount: 143,
    isBestSeller: true,
    isNew: false,
    images: [
      'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-12',
    sku: 'FO-DB-03',
    category_id: 'cat-4',
    category: 'Fitness & Outdoors',
    brand: 'Vanguard',
    slug: 'tactical-waterproof-gym-duffle',
    name: {
      en: 'Vanguard Waterproof Gym & Travel Duffle',
      es: 'Bolsa de Deporte y Viaje Impermeable',
      ar: 'حقيبة رياضية وسفر مقاومة للماء فانغارد',
      bn: 'ভ্যানগার্ড ওয়াটারপ্রুফ জিম ও ট্রাভেল ডাফেল ব্যাগ'
    },
    description: {
      en: 'Ballistic nylon weather-proof duffle with ventilated shoe compartment and modular shoulder straps.',
      es: 'Bolsa de nailon balístico resistente a la intemperie con compartimento ventilado para calzado.',
      ar: 'حقيبة متينة من النايلون الباليستي المقاوم للماء مع حجرة جيدة التهوية للأحذية.',
      bn: 'ব্যালিস্টিক নাইলন ওয়াটারপ্রুফ ডাফেল ব্যাগ যাতে রয়েছে ভেন্টিলেটেড জুতো রাখার আলাদা চেম্বার।'
    },
    price: 92.00,
    compare_price: 115.00,
    stock: 14,
    rating: 4.7,
    reviewCount: 49,
    isBestSeller: false,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80'
    ]
  },

  // KITCHEN & DINING
  {
    id: 'prod-13',
    sku: 'KD-CF-01',
    category_id: 'cat-5',
    category: 'Kitchen & Dining',
    brand: 'TerraStudio',
    slug: 'artisanal-ceramic-pour-over-coffee-maker',
    name: {
      en: 'TerraStudio Ceramic Pour-Over Dripper Set',
      es: 'Juego de Cafetera de Goteo Cerámica Artesanal',
      ar: 'طقم تقطير القهوة الخزفي اليدوي من تيرا استوديو',
      bn: 'টেরাস্টুডিও সিরামিক পোর-ওভার কফি ড্রিপার সেট'
    },
    description: {
      en: 'Hand-turned speckled stoneware dripper with borosilicate heat-resistant glass serving carafe.',
      es: 'Gotero de gres torneado a mano con jarra de vidrio de borosilicato resistente al calor.',
      ar: 'قمع ترشيح قهوة خزفي مصنوع يدويًا مع إبريق زجاجي مقاوم للحرارة عالي الجودة.',
      bn: 'হাতে তৈরি সিরামিক ড্রিপার ও বোরোসিলিকেট হিট-রেজিস্ট্যান্ট গ্লাস সার্ভিং ক্যারাফে।'
    },
    price: 58.00,
    compare_price: 72.00,
    stock: 20,
    rating: 4.9,
    reviewCount: 64,
    isBestSeller: true,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-14',
    sku: 'KD-KN-02',
    category_id: 'cat-5',
    category: 'Kitchen & Dining',
    brand: 'NordicCraft',
    slug: 'damascus-steel-santoku-chef-knife',
    name: {
      en: 'NordicCraft 67-Layer Damascus Santoku Knife (7")',
      es: 'Cuchillo Santoku de Acero Damasco de 67 Capas',
      ar: 'سكين سانتوكو الاحترافي من فولاذ دمشقي 67 طبقة',
      bn: 'নরডিকক্রাফট ৬৭-লেয়ার দামেস্ক স্টিল সান্তোকু শেফ নাইফ'
    },
    description: {
      en: 'VG-10 super steel core with 67 layers of Damascus cladding, razor-sharp 12-degree edge and pakkawood handle.',
      es: 'Núcleo de acero VG-10 con 67 capas de Damasco, filo ultra afilado y mango ergonómico de madera.',
      ar: 'قلب فولاذي ممتاز VG-10 مع 67 طبقة دمشقية، وشفرة حادة كالموس ومقبض خشبي مريح.',
      bn: 'ভিজি-১০ সুপার স্টিল কোর, ৬৭ লেয়ার দামেস্ক ক্লাডিং এবং নিখুঁত ১২ ডিগ্রি শার্পনেস।'
    },
    price: 110.00,
    compare_price: 145.00,
    stock: 12,
    rating: 5.0,
    reviewCount: 88,
    isBestSeller: true,
    isNew: false,
    images: [
      'https://images.unsplash.com/photo-1593618998160-e34014e67546?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-15',
    sku: 'KD-MG-03',
    category_id: 'cat-5',
    category: 'Kitchen & Dining',
    brand: 'TerraStudio',
    slug: 'matte-stoneware-coffee-mugs-set-of-4',
    name: {
      en: 'TerraStudio Matte Ceramic Mug Set (4-Pack)',
      es: 'Juego de 4 Tazas de Cerámica Mate TerraStudio',
      ar: 'طقم 4 أكواب سيراميك مطفية من تيرا استوديو',
      bn: 'টেরাস্টুডিও ম্যাট সিরামিক কফি মাগ সেট (৪ পিস)'
    },
    description: {
      en: 'Organic speckled clay with comfortable ergonomic thumb-rest handles and smooth satin matte glaze.',
      es: 'Cerámica orgánica con asas ergonómicas y esmalte satinado suave al tacto.',
      ar: 'طين فخاري طبيعي مع مقابض مريحة ولمسة نهائية ناعمة وأنيقة.',
      bn: 'প্রাকৃতিক মাটির টেক্সচার এবং আরামদায়ক হ্যান্ডেলযুক্ত ৪টি প্রিমিয়াম সিরামিক মাগ।'
    },
    price: 44.00,
    compare_price: 55.00,
    stock: 28,
    rating: 4.8,
    reviewCount: 52,
    isBestSeller: false,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=80'
    ]
  },

  // BEAUTY & WELLNESS
  {
    id: 'prod-16',
    sku: 'BW-DF-01',
    category_id: 'cat-6',
    category: 'Beauty & Wellness',
    brand: 'AuraBotanics',
    slug: 'ultrasonic-terracotta-aroma-diffuser',
    name: {
      en: 'AuraBotanics Stone Ultrasonic Essential Oil Diffuser',
      es: 'Difusor de Aceites Esenciales de Cerámica AuraBotanics',
      ar: 'فواحة الزيوت العطرية الخزفية بالموجات فوق الصوتية',
      bn: 'অরাবোটানিকস স্টোন আল্ট্রাসনিক অ্যাসেনশিয়াল অয়েল ডিফিউজার'
    },
    description: {
      en: 'Matte ceramic stoneware shell producing whisper-quiet ultrasonic aromatherapy mist with subtle ambient warm glow.',
      es: 'Carcasa de cerámica mate que produce una suave bruma aromaterápica con luz ambiental cálida.',
      ar: 'هيكل سيراميك أنيق يصدر رذاذًا هادئًا للروائح العلاجية مع إضاءة دافئة مريحة.',
      bn: 'হুইসপার-কোয়ায়েট আল্ট্রাসনিক অ্যারোমাথেরাপি কুয়াশা এবং মৃদু অ্যাম্বিয়েন্ট ওয়ার্ম লাইট।'
    },
    price: 72.00,
    compare_price: 90.00,
    stock: 17,
    rating: 4.9,
    reviewCount: 112,
    isBestSeller: true,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-17',
    sku: 'BW-BO-02',
    category_id: 'cat-6',
    category: 'Beauty & Wellness',
    brand: 'AuraBotanics',
    slug: 'organic-golden-jojoba-facial-elixir',
    name: {
      en: 'AuraBotanics Pure Botanical Nourishing Facial Oil (50ml)',
      es: 'Aceite Facial Botánico Nutritivo AuraBotanics (50ml)',
      ar: 'زيت الوجه النباتي المغذي من أورا بوتانيكس (50 مل)',
      bn: 'অরাবোটানিকস পিওর বোটানিক্যাল নারিশিং ফেস অয়েল (৫০ মিলি)'
    },
    description: {
      en: 'Cold-pressed organic rosehip, squalane, and golden jojoba oil to restore moisture balance and radiant glow.',
      es: 'Rosa mosqueta prensada en frío, escualano y aceite de jojoba dorada para restaurar la hidratación.',
      ar: 'مزيج طبيعي معصور على البارد من ثمر الورد والسكوالين والجوجوبا لاستعادة نضارة البشرة.',
      bn: 'কোল্ড-প্রেসড অর্গানিক রোজহিপ, স্কোয়ালেন এবং গোল্ডেন জোজোবা তেলের নারিশিং কম্বিনেশন।'
    },
    price: 48.00,
    compare_price: 60.00,
    stock: 25,
    rating: 4.8,
    reviewCount: 73,
    isBestSeller: false,
    isNew: true,
    images: [
      'https://images.unsplash.com/photo-1608248597359-bb436d4b55bc?w=800&auto=format&fit=crop&q=80'
    ]
  },
  {
    id: 'prod-18',
    sku: 'BW-FR-03',
    category_id: 'cat-6',
    category: 'Beauty & Wellness',
    brand: 'AuraBotanics',
    slug: 'natural-jade-facial-sculpting-roller',
    name: {
      en: 'AuraBotanics Natural Jade Roller & Gua Sha Set',
      es: 'Juego de Rodillo de Jade Natural y Gua Sha',
      ar: 'طقم مدلك الوجه من حجر اليشم الطبيعي وغوا شا',
      bn: 'অরাবোটানিকস ন্যাচারাল জেড রোলার ও গুয়া শা সেট'
    },
    description: {
      en: '100% genuine Xiuyan jade stone designed to boost lymphatic drainage, relieve facial tension, and enhance product absorption.',
      es: 'Piedra de jade natural para estimular la circulación, aliviar la tensión facial y mejorar la absorción.',
      ar: 'حجر اليشم الطبيعي 100% لتعزيز التصريف اللمفاوي وتخفيف توتر الوجه وتحسين امتصاص العناية بالبشرة.',
      bn: '১০০% খাঁটি জেইড স্টোন যা মুখের রক্ত সঞ্চালন বাড়ায় ও ত্বকের সতেজতা ফিরিয়ে আনে।'
    },
    price: 32.00,
    compare_price: 42.00,
    stock: 30,
    rating: 4.7,
    reviewCount: 61,
    isBestSeller: false,
    isNew: false,
    images: [
      'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80'
    ]
  }
];

export const mockOrders = [
  {
    id: 'ORD-2026-9810',
    date: '2026-07-28',
    status: 'Delivered',
    paymentStatus: 'Paid',
    total: 243.99,
    items: [
      {
        id: 'prod-1',
        name: { en: 'AeroSound Pro Wireless Headphones' },
        price: 199.99,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'
      }
    ],
    shippingAddress: {
      fullName: 'Sakib Chowdhury',
      address: '128 Pinecrest Ave',
      city: 'San Francisco',
      state: 'CA',
      zip: '94110',
      country: 'USA'
    }
  }
];

export const mockReviews = [
  {
    id: 'rev-1',
    productId: 'prod-1',
    author: 'Elena R.',
    rating: 5,
    date: '2026-07-20',
    comment: 'The active noise cancellation is remarkably quiet, and the battery life lasts through an entire week of flights.',
    verified: true
  },
  {
    id: 'rev-2',
    productId: 'prod-7',
    author: 'Marcus K.',
    rating: 5,
    date: '2026-07-24',
    comment: 'Architectural beauty. The warm marble base gives it such commanding stability in our living room.',
    verified: true
  }
];
