const defaultTestimonials = [
  {
    id: 1,
    name: 'Israt Jahan',
    role: {
      en: 'Verified Buyer',
      es: 'Compradora Verificada',
      ar: 'مشتري موثق',
      bn: 'ভেরিফায়েড ক্রেতা'
    },
    rating: 5,
    initials: 'IJ',
    theme: 'green',
    accentColor: 'var(--green, #1F3A2E)',
    borderColor: 'rgba(31, 58, 46, 0.28)',
    quote: {
      en: 'Nuvara completely transformed my shopping experience. Delivery was prompt and the build quality exceeds expectations.',
      es: 'Nuvara cambió por completo mi experiencia de compra. El envío fue rápido y la calidad superó mis expectativas.',
      ar: 'غيّرت نوفارا تجربتي في التسوق تمامًا. كان التوصيل سريعًا وجودة القطع فاقت كل التوقعات.',
      bn: 'নোভারা আমার অনলাইন শপিংয়ের অভিজ্ঞতা পুরোপুরি বদলে দিয়েছে। খুব দ্রুত ডেলিভারি পেয়েছি এবং গুণমান ছিল চমৎকার।'
    }
  },
  {
    id: 2,
    name: 'Arlene McCoy',
    role: {
      en: 'Interior Stylist',
      es: 'Diseñadora de Interiores',
      ar: 'مصممة ديكور داخلي',
      bn: 'ইন্টেরিয়র ডিজাইনার'
    },
    rating: 5,
    initials: 'AM',
    theme: 'brass',
    accentColor: 'var(--brass, #B8863B)',
    borderColor: 'rgba(184, 134, 59, 0.35)',
    quote: {
      en: 'They are divine. So many compliments. Not only that, I got them for a great price. Will definitely shop from Nuvara again.',
      es: 'Son divinos. Recibo tantos elogios. Además los conseguí a un gran precio, sin duda volveré a comprar aquí.',
      ar: 'إنها رائعة للغاية ونالت إعجاب الجميع. بالإضافة إلى السعر المميز، سأتسوق بالتأكيد من نوفارا مجددًا.',
      bn: 'পণ্যগুলো সত্যিই অসাধারণ। সবাই খুব প্রশংসা করেছে এবং দামও ছিল অত্যন্ত চমৎকার। আবার অবশ্যই কিনব।'
    }
  },
  {
    id: 3,
    name: 'Diego Ramirez',
    role: {
      en: 'Verified Buyer',
      es: 'Comprador Verificado',
      ar: 'مشتري معتمد',
      bn: 'ভেরিফায়েড ক্রেতা'
    },
    rating: 5,
    initials: 'DR',
    theme: 'wine',
    accentColor: 'var(--wine, #6B2737)',
    borderColor: 'rgba(107, 39, 55, 0.3)',
    quote: {
      en: 'The customer service team is incredibly helpful, and the packaging made unboxing feel like receiving a luxury gift.',
      es: 'El servicio al cliente es excelente y el empaque hizo que abrir la caja se sintiera como un regalo de lujo.',
      ar: 'فريق خدمة العملاء متعاون وودود للغاية، وتفاصيل التغليف جعلت تجربة فتح الصندوق فاخرة جدًا.',
      bn: 'গ্রাহক সেবা দল অসাধারণ সাহায্যকারী এবং প্যাকেজিংয়ের ফিনিশিং ছিল সত্যিই প্রিমিয়াম ও চোখজুড়ানো।'
    }
  },
  {
    id: 4,
    name: 'Sofia Chen',
    role: {
      en: 'Creative Director',
      es: 'Directora Creativa',
      ar: 'مديرة إبداعية',
      bn: 'ক্রিয়েটিভ ডিরেক্টর'
    },
    rating: 5,
    initials: 'SC',
    theme: 'slate',
    accentColor: '#334155',
    borderColor: 'rgba(51, 65, 85, 0.3)',
    quote: {
      en: 'Minimalist aesthetics, sustainable materials, and honest pricing. Nuvara sets the modern standard for home essentials.',
      es: 'Estética minimalista, materiales sostenibles y precios justos. Nuvara marca un estándar en productos para el hogar.',
      ar: 'جماليات راقية وبسيطة ومواد مستدامة. نوفارا تضع معيارًا حديثًا للمنتجات المنزلية عالية الجودة.',
      bn: 'মিনিমালিস্ট ডিজাইন ও টেকসই কোয়ালিটি। ঘরের প্রয়োজনীয় সেরা জিনিস কেনার জন্য নোভারা সবসময় নির্ভরযোগ্য।'
    }
  },
  {
    id: 5,
    name: 'Tariq Al-Mansoor',
    role: {
      en: 'Collector & Architect',
      es: 'Arquitecto y Coleccionista',
      ar: 'مهندس معماري ومقتنٍ',
      bn: 'আর্কিটেক্ট ও সংগ্রাহক'
    },
    rating: 5,
    initials: 'TA',
    theme: 'brass',
    accentColor: 'var(--brass, #B8863B)',
    borderColor: 'rgba(184, 134, 59, 0.35)',
    quote: {
      en: 'Every piece brings an architectural presence and tactile warmth. The international shipping was completely seamless.',
      es: 'Cada pieza aporta una presencia arquitectónica y calidez táctil. El envío internacional fue totalmente impecable.',
      ar: 'كل قطعة تتميز بحضور معماري راقٍ ولمسة دافئة. تجربة الشحن الدولي كانت سلسة وبلا أي تعقيد.',
      bn: 'প্রতিটি পণ্যের নান্দনিক ডিজাইন ও নিখুঁত ফিনিশিং আমাকে মুগ্ধ করেছে। আন্তর্জাতিক ডেলিভারিও ছিল খুব দ্রুত।'
    }
  },
  {
    id: 6,
    name: 'Elena Rostova',
    role: {
      en: 'Art Curator',
      es: 'Curadora de Arte',
      ar: 'أمينة معارض فنية',
      bn: 'আর্ট কিউরেটর'
    },
    rating: 5,
    initials: 'ER',
    theme: 'green',
    accentColor: 'var(--green, #1F3A2E)',
    borderColor: 'rgba(31, 58, 46, 0.28)',
    quote: {
      en: 'Curated selection with a distinct point of view. It is refreshing to find timeless objects crafted with such care.',
      es: 'Una selección curada con un estilo definido. Es maravilloso encontrar objetos atemporales hechos con tanto cuidado.',
      ar: 'مجموعة مختارة بعناية وذوق فريد. من الممتع حقًا العثور على قطع تجمع بين الأصالة والاهتمام بالتفاصيل.',
      bn: 'অসাধারণ রুচিশীল কালেকশন। প্রতিটি জিনিসে যত্ন ও নিখুঁত কারুকার্যের ছোঁয়া স্পষ্টভাবে দৃশ্যমান।'
    }
  }
];

export default defaultTestimonials;
