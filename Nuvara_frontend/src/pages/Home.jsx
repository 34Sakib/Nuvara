import React, { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { Link as RouterLink } from 'react-router-dom';
import { motion, AnimatePresence } from 'framer-motion';
import { Truck, ShieldCheck, RefreshCw, Headphones, ArrowRight, Flame, ChevronLeft, ChevronRight, Star, Sparkles } from 'lucide-react';
import { mockCategories, mockProducts, getLocalized } from '../utils/mockData';
import { useLocaleStore } from '../store/localeStore';
import { ProductCard } from '../components/product/ProductCard';
import { CategoryCard } from '../components/category/CategoryCard';
import { SectionDivider } from '../components/ui/SectionDivider';
import api from '../services/api';

const ICON_MAP = {
  Truck,
  ShieldCheck,
  RefreshCw,
  Headphones,
  Sparkles
};

const defaultTestimonials = [
  {
    id: 1,
    name: 'Israt Jahan',
    rating: 5,
    quote: {
      en: 'Nuvara completely changed my online shopping experience. Shipping was fast and the quality was top-notch.',
      es: 'Nuvara cambió por completo mi experiencia de compra. El envío fue rápido y la calidad de primera.',
      ar: 'غيّرت نوفارا تجربتي في التسوق عبر الإنترنت تمامًا. الشحن كان سريعًا والجودة كانت ممتازة.',
      bn: 'নোভারা আমার অনলাইন শপিংয়ের অভিজ্ঞতা পুরোপুরি বদলে দিয়েছে। খুব দ্রুত শিপিং পেয়েছি এবং কোয়ালিটি ছিল দারুণ।'
    }
  },
  {
    id: 2,
    name: 'Diego R.',
    rating: 5,
    quote: {
      en: 'The customer service team is incredibly helpful, and the Arabic font support made checkout so natural.',
      es: 'El servicio al cliente es excelente y la facilidad de pago fue impresionante.',
      ar: 'فريق خدمة العملاء متعاون للغاية، ودعم اللغة العربية جعل تجربة الدفع طبيعية وسهلة.',
      bn: 'গ্রাহক সেবা দল অত্যন্ত সাহায্যকারী এবং ড্যাশবোর্ডটি ব্যবহার করা খুবই সহজ ছিল।'
    }
  }
];

export const Home = () => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();

  const [homeData, setHomeData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('best');
  const [timeLeft, setTimeLeft] = useState({ hours: 4, minutes: 34, seconds: 12 });
  const [currentSlide, setCurrentSlide] = useState(0);
  const [isHovered, setIsHovered] = useState(false);

  useEffect(() => {
    const fetchHomeData = async () => {
      try {
        const res = await api.get('/home', {
          headers: { 'Accept-Language': locale }
        });
        setHomeData(res.data);
      } catch (err) {
        console.error("Failed fetching homepage data from API, using fallbacks", err);
        // Keep the storefront usable when the API is stopped or unreachable.
        setHomeData({});
      } finally {
        setLoading(false);
      }
    };
    fetchHomeData();
  }, [locale]);

  // Flash Sale Timer calculation
  useEffect(() => {
    if (!homeData?.flash_sale?.ends_at) {
      const timer = setInterval(() => {
        setTimeLeft((prev) => {
          if (prev.seconds > 0) {
            return { ...prev, seconds: prev.seconds - 1 };
          } else if (prev.minutes > 0) {
            return { ...prev, minutes: prev.minutes - 1, seconds: 59 };
          } else if (prev.hours > 0) {
            return { hours: prev.hours - 1, minutes: 59, seconds: 59 };
          } else {
            return { hours: 4, minutes: 0, seconds: 0 };
          }
        });
      }, 1000);
      return () => clearInterval(timer);
    }

    const targetTime = new Date(homeData.flash_sale.ends_at).getTime();
    const updateTimer = () => {
      const now = new Date().getTime();
      const diff = Math.max(0, targetTime - now);

      const hours = Math.floor(diff / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);

      setTimeLeft({ hours, minutes, seconds });
    };

    updateTimer();
    const timer = setInterval(updateTimer, 1000);
    return () => clearInterval(timer);
  }, [homeData?.flash_sale?.ends_at]);

  // Auto-play slider
  useEffect(() => {
    if (isHovered) return;
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % Math.max(1, heroSlides.length));
    }, 5000);
    return () => clearInterval(interval);
  }, [isHovered, homeData]);

  const padZero = (num) => (num < 10 ? `0${num}` : num);

  if (loading) {
    return (
      <div className="max-w-7xl mx-auto px-4 py-16 space-y-16 animate-pulse text-left rtl:text-right">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <div className="lg:col-span-6 space-y-4">
            <div className="h-6 w-32 bg-border rounded"></div>
            <div className="h-16 w-3/4 bg-border rounded"></div>
            <div className="h-4 w-5/6 bg-border rounded"></div>
            <div className="h-4 w-2/3 bg-border rounded"></div>
            <div className="h-10 w-40 bg-border rounded"></div>
          </div>
          <div className="lg:col-span-6 h-80 bg-border rounded-lg"></div>
        </div>
      </div>
    );
  }

  // Active Datasets with Fallbacks
  const categoriesList = homeData?.categories?.length > 0 ? homeData.categories : mockCategories;
  const bestSellersList = homeData?.best_sellers?.length > 0 ? homeData.best_sellers : mockProducts.filter(p => p.isBestSeller);
  const newArrivalsList = homeData?.new_arrivals?.length > 0 ? homeData.new_arrivals : mockProducts.filter(p => p.isNew);
  const flashProductsList = homeData?.flash_products?.length > 0 ? homeData.flash_products : mockProducts.slice(0, 4);

  const activeProducts = activeTab === 'best' ? bestSellersList : newArrivalsList;

  // Hero Slides
  const defaultSlides = [
    {
      id: 1,
      badge: 'EXCLUSIVE',
      badgeText: 'Sale',
      headline: 'Happening Now!',
      sub: 'Discover amazing deals and discounts on our eCommerce website! Shop now for the best offers!',
      buttonText: 'SHOP NOW',
      link: '/category/electronics',
      product: mockProducts[0],
      bgGradient: 'from-[#FDE047] via-[#FACC15] to-[#EAB308]',
      textColor: 'text-gray-950',
      badgeBg: 'bg-white text-black font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
      btnStyle: 'bg-black text-white hover:bg-gray-800 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm'
    },
    {
      id: 2,
      badge: 'NEW ARRIVAL',
      badgeText: 'Audio Pro',
      headline: 'Next-Gen Wireless Sound',
      sub: 'Immerse yourself in crystal clear studio audio with ultra active noise cancellation.',
      buttonText: 'EXPLORE DEAL',
      link: '/product/wireless-noise-canceling-headphones',
      product: mockProducts[1],
      bgGradient: 'from-[#10B981] via-[#059669] to-[#047857]',
      textColor: 'text-white',
      badgeBg: 'bg-emerald-300 text-emerald-950 font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
      btnStyle: 'bg-white text-emerald-950 hover:bg-emerald-50 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm'
    }
  ];

  const heroSlides = (homeData?.hero_slides && homeData.hero_slides.length > 0) ? homeData.hero_slides : defaultSlides;
  const activeSlide = (heroSlides && heroSlides.length > 0) ? (heroSlides[currentSlide % heroSlides.length] || heroSlides[0]) : defaultSlides[0];

  // Trust Features
  const defaultTrustFeatures = [
    { key: 'free_shipping', title: t('home.free_shipping'), sub: t('home.free_shipping_sub'), icon: 'Truck', iconColor: 'text-emerald-500 dark:text-emerald-400', bgColor: 'bg-emerald-500/10 dark:bg-emerald-500/15' },
    { key: 'secure_payment', title: t('home.secure_payment'), sub: t('home.secure_payment_sub'), icon: 'ShieldCheck', iconColor: 'text-amber-500 dark:text-amber-400', bgColor: 'bg-amber-500/10 dark:bg-amber-500/15' },
    { key: 'easy_returns', title: t('home.easy_returns'), sub: t('home.easy_returns_sub'), icon: 'RefreshCw', iconColor: 'text-blue-500 dark:text-blue-400', bgColor: 'bg-blue-500/10 dark:bg-blue-500/15' },
    { key: 'support', title: t('home.support'), sub: t('home.support_sub'), icon: 'Headphones', iconColor: 'text-purple-500 dark:text-purple-400', bgColor: 'bg-purple-500/10 dark:bg-purple-500/15' }
  ];
  const trustFeatures = homeData?.trust_features?.length > 0 ? homeData.trust_features : defaultTrustFeatures;

  // Testimonials
  const testimonialsList = homeData?.testimonials?.length > 0 ? homeData.testimonials : defaultTestimonials;

  // Promo Banner
  const promoBanner = homeData?.promo_banner || {
    badge: 'Limited Edition',
    headline: t('home.promo_heading'),
    sub: t('home.promo_sub'),
    buttonText: 'Explore Collection',
    link: '/category/home-living',
    image: 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1200&auto=format&fit=crop&q=80'
  };

  const getStr = (val) => typeof val === 'object' ? getLocalized(val, locale) : (val || '');

  return (
    <div className="space-y-16 animate-fade-in">

      {/* 1. Dynamic Color-Changing Full-Width Hero Slider */}
      <section
        onMouseEnter={() => setIsHovered(true)}
        onMouseLeave={() => setIsHovered(false)}
        className={`w-full relative overflow-hidden transition-all duration-700 ease-in-out bg-gradient-to-r ${activeSlide.bgGradient || 'from-yellow-400 to-yellow-600'} py-12 md:py-20 flex items-center shadow-lg`}
      >
        <div className="absolute inset-0 pointer-events-none opacity-25 mix-blend-multiply"><img src={activeSlide.product?.images?.[0] || activeSlide.image} alt="" className="w-full h-full object-cover blur-3xl scale-110" /></div>
        <div className="absolute inset-0 overflow-hidden pointer-events-none select-none text-white/80">
          <Star className="w-5 h-5 absolute top-10 left-12 opacity-80 animate-pulse" />
          <Sparkles className="w-6 h-6 absolute top-16 right-1/3 opacity-70 animate-bounce" />
          <Star className="w-4 h-4 absolute bottom-20 left-1/4 opacity-60" />
          <Star className="w-6 h-6 absolute bottom-12 right-12 opacity-80 animate-pulse" />
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-center">

            {/* Left Content Column */}
            <div className="lg:col-span-7 text-left rtl:text-right">
              <AnimatePresence mode="wait">
                <motion.div
                  key={activeSlide.id || currentSlide}
                  initial={{ opacity: 0, x: -35 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 35 }}
                  transition={{ duration: 0.45, ease: 'easeOut' }}
                >
                  <div className="flex items-center space-x-3 rtl:space-x-reverse mb-4 flex-wrap gap-y-2">
                    <span className={activeSlide.badgeBg || 'bg-white text-black font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs inline-block'}>
                      {getStr(activeSlide.badge)}
                    </span>
                    {activeSlide.badgeText && (
                      <span className="text-[#0b0b0b] font-black text-3xl sm:text-4xl md:text-5xl ml-2 rtl:mr-2 rtl:ml-0 inline-block">
                        {getStr(activeSlide.badgeText)}
                      </span>
                    )}
                  </div>

                  <h1 className={`text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.05] ${activeSlide.textColor || 'text-gray-950'} mb-4`}>
                    {getStr(activeSlide.headline)}
                  </h1>

                  <p className="text-sm sm:text-base max-w-lg leading-relaxed text-gray-900/90 font-medium mb-8">
                    {getStr(activeSlide.sub)}
                  </p>

                  <RouterLink to={activeSlide.link || '#'}>
                    <button className={`${activeSlide.btnStyle || 'bg-black text-white px-8 py-3.5 rounded-xl text-sm font-bold'} transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center gap-2.5`}>
                      <span>{getStr(activeSlide.buttonText)}</span>
                      <ArrowRight className="w-4 h-4 rtl-flip" />
                    </button>
                  </RouterLink>
                </motion.div>
              </AnimatePresence>
            </div>

            {/* Right Product Image Column */}
            <div className="lg:col-span-5 flex justify-center items-center relative">
              <AnimatePresence mode="wait">
                <motion.div
                  key={activeSlide.id || currentSlide}
                  initial={{ opacity: 0, scale: 0.85, rotate: -4 }}
                  animate={{ opacity: 1, scale: 1, rotate: 0 }}
                  exit={{ opacity: 0, scale: 0.85, rotate: 4 }}
                  transition={{ duration: 0.5, ease: 'easeOut' }}
                  className="w-full max-w-md aspect-square flex items-center justify-center relative"
                >
                  <div className="w-full h-full rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20 relative group bg-black/10 backdrop-blur-sm">
                    <img
                      src={activeSlide.product?.images?.[0] || activeSlide.image || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'}
                      alt={getStr(activeSlide.headline)}
                      className="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                    />
                    {activeSlide.product && (
                      <div className="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                        <span className="text-white text-xs font-bold truncate">
                          {getStr(activeSlide.product.name)} — ${activeSlide.product.price}
                        </span>
                      </div>
                    )}
                  </div>
                </motion.div>
              </AnimatePresence>
            </div>
          </div>
        </div>

        {/* Bottom Pagination Dots */}
        <div className="absolute bottom-6 left-8 sm:left-12 lg:left-24 rtl:right-8 rtl:sm:right-12 rtl:lg:right-24 rtl:left-auto flex items-center space-x-2.5 rtl:space-x-reverse z-20">
          {heroSlides.map((_, index) => (
            <button
              key={index}
              onClick={() => setCurrentSlide(index)}
              className={`h-2.5 rounded-full transition-all duration-400 ${currentSlide === index ? 'bg-black w-9' : 'bg-black/30 hover:bg-black/60 w-2.5'
                }`}
              aria-label={`Go to slide ${index + 1}`}
            />
          ))}
        </div>

        {/* Side Arrow Navigation Controls */}
        <div className="hidden sm:flex absolute right-8 sm:right-12 lg:right-24 bottom-6 rtl:left-8 rtl:sm:left-12 rtl:lg:left-24 rtl:right-auto items-center space-x-2 rtl:space-x-reverse z-20">
          <button
            onClick={() => setCurrentSlide((prev) => (prev === 0 ? heroSlides.length - 1 : prev - 1))}
            className="p-2.5 rounded-full bg-black/20 hover:bg-black/40 text-white transition-colors backdrop-blur-sm focus:outline-none"
            aria-label="Previous slide"
          >
            <ChevronLeft className="w-5 h-5 rtl-flip" />
          </button>
          <button
            onClick={() => setCurrentSlide((prev) => (prev + 1) % heroSlides.length)}
            className="p-2.5 rounded-full bg-black/20 hover:bg-black/40 text-white transition-colors backdrop-blur-sm focus:outline-none"
            aria-label="Next slide"
          >
            <ChevronRight className="w-5 h-5 rtl-flip" />
          </button>
        </div>
      </section>

      {/* 2. Trust Bar */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-40px" }}
          variants={{
            hidden: { opacity: 0, y: 25 },
            visible: { opacity: 1, y: 0, transition: { staggerChildren: 0.12, duration: 0.5 } }
          }}
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-surface/90 backdrop-blur-md border border-border/80 rounded-2xl p-6 md:p-8 shadow-md hover:shadow-xl transition-all duration-300"
        >
          {trustFeatures.map((item) => {
            const IconComponent = ICON_MAP[item.icon] || Truck;
            return (
              <motion.div
                key={item.id || item.key}
                variants={{
                  hidden: { opacity: 0, y: 20, scale: 0.95 },
                  visible: { opacity: 1, y: 0, scale: 1 }
                }}
                whileHover={{ y: -4, scale: 1.02 }}
                className="group flex items-center space-x-4 rtl:space-x-reverse p-3.5 rounded-xl hover:bg-bg-primary/60 border border-transparent hover:border-border/60 transition-all duration-300 cursor-pointer"
              >
                <div className={`p-3.5 rounded-xl ${item.bgColor || 'bg-emerald-500/10'} ${item.iconColor || 'text-emerald-500'} group-hover:scale-110 transition-transform duration-300 flex-shrink-0 shadow-sm`}>
                  <IconComponent className="w-6 h-6" />
                </div>
                <div>
                  <h4 className="text-sm font-bold text-text-primary group-hover:text-brass transition-colors duration-200">
                    {getStr(item.title)}
                  </h4>
                  <p className="text-xs text-text-secondary mt-0.5 font-medium leading-relaxed">
                    {getStr(item.sub)}
                  </p>
                </div>
              </motion.div>
            );
          })}
        </motion.div>
      </section>

      {/* 3. Shop by Category */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-6">
          <h2 className="text-2xl font-bold font-display text-text-primary uppercase tracking-wide">
            {t('home.shop_category')}
          </h2>
          <SectionDivider />
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {categoriesList.map((cat, idx) => (
            <CategoryCard key={cat.id || cat.slug} category={cat} index={idx} />
          ))}
        </div>
      </section>

      {/* 4. Flash Deals (with Countdown) */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-8">
          <div className="inline-flex items-center space-x-3 rtl:space-x-reverse justify-center">
            <div className="p-1.5 rounded-lg bg-wine text-white animate-pulse">
              <Flame className="w-5 h-5" />
            </div>
            <h2 className="text-2xl font-bold font-display text-text-primary uppercase tracking-wide">
              {getStr(homeData?.flash_sale?.title) || t('home.flash_deals')}
            </h2>
          </div>
          <SectionDivider />

          <div className="flex items-center justify-center space-x-2.5 rtl:space-x-reverse text-sm font-bold text-text-secondary mt-2">
            <span>{t('home.flash_deals_ends')}</span>
            <div className="flex space-x-1 rtl:space-x-reverse">
              <span className="px-2.5 py-1 rounded-[4px] bg-wine text-white font-mono text-xs">{padZero(timeLeft.hours)}</span>
              <span>:</span>
              <span className="px-2.5 py-1 rounded-[4px] bg-wine text-white font-mono text-xs">{padZero(timeLeft.minutes)}</span>
              <span>:</span>
              <span className="px-2.5 py-1 rounded-[4px] bg-wine text-white font-mono text-xs">{padZero(timeLeft.seconds)}</span>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-2 lg:grid-cols-4 gap-6">
          {flashProductsList.map((prod) => (
            <ProductCard key={prod.id} product={prod} />
          ))}
        </div>
      </section>

      {/* 5. Best Sellers / New Arrivals Tabs */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-6">
          <h2 className="text-2xl font-bold font-display text-text-primary uppercase tracking-wide">
            Featured Collections
          </h2>
          <SectionDivider />
        </div>

        <div className="flex justify-center border-b border-border mb-8 gap-6">
          <button
            onClick={() => setActiveTab('best')}
            className={`
              pb-4 text-sm font-bold uppercase border-b-2 transition-colors focus:outline-none font-sans
              ${activeTab === 'best' ? 'border-brass text-brass font-extrabold' : 'border-transparent text-text-secondary hover:text-text-primary'}
            `}
          >
            {t('home.best_sellers')}
          </button>
          <button
            onClick={() => setActiveTab('new')}
            className={`
              pb-4 text-sm font-bold uppercase border-b-2 transition-colors focus:outline-none font-sans
              ${activeTab === 'new' ? 'border-brass text-brass font-extrabold' : 'border-transparent text-text-secondary hover:text-text-primary'}
            `}
          >
            {t('home.new_arrivals')}
          </button>
        </div>

        <div className="grid grid-cols-2 lg:grid-cols-4 gap-6">
          {activeProducts.map((prod) => (
            <ProductCard key={prod.id} product={prod} />
          ))}
        </div>
      </section>

      {/* 6. Promotional Lifestyle Banner */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
          className="relative rounded-[8px] overflow-hidden bg-cover bg-center h-80 flex items-center justify-start p-8 md:p-16 border border-border"
          style={{ backgroundImage: `linear-gradient(to right, rgba(0,0,0,0.85) 40%, rgba(0,0,0,0.2)), url('${promoBanner.image}')` }}
        >
          <div className="max-w-md text-left rtl:text-right space-y-3">
            <span className="text-[10px] font-black uppercase tracking-widest text-[#F5EFE4] bg-[#1F3A2E] px-2.5 py-1 rounded-[4px]">
              {getStr(promoBanner.badge)}
            </span>
            <h3 className="text-2xl sm:text-3xl font-bold font-display text-[#F5EFE4] leading-snug">
              {getStr(promoBanner.headline)}
            </h3>
            <p className="text-sm text-[#F5EFE4]/80 leading-relaxed mb-6 font-sans">
              {getStr(promoBanner.sub)}
            </p>
            <RouterLink
              to={promoBanner.link || '/category/home-living'}
              className="inline-flex items-center px-5 py-2.5 bg-green hover:bg-green-soft text-[#F5EFE4] text-xs font-bold rounded-[4px] transition-colors"
            >
              <span>{getStr(promoBanner.buttonText)}</span>
              <ArrowRight className="w-3.5 h-3.5 ml-2 rtl:mr-2 rtl:ml-0 rtl-flip" />
            </RouterLink>
          </div>
        </div>
      </section>

      {/* 7. Testimonials */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div className="text-center mb-6">
          <h2 className="text-2xl font-bold font-display text-text-primary uppercase tracking-wide">
            {t('home.testimonials')}
          </h2>
          <SectionDivider />
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
          {testimonialsList.map((test) => (
            <div
              key={test.id}
              className="bg-surface border border-border p-6 rounded-[8px] transition-all duration-200 flex flex-col justify-between"
              style={{ boxShadow: 'var(--shadow)' }}
            >
              <p className="text-sm leading-relaxed text-text-secondary italic">
                "{getStr(test.quote)}"
              </p>
              <div className="mt-6 flex justify-between items-center border-t border-border pt-4">
                <span className="font-bold text-sm text-text-primary">{test.name}</span>
                <span className="text-xs text-brass font-extrabold flex">
                  {Array.from({ length: test.rating || 5 }).map((_, i) => (
                    <span key={i}>★</span>
                  ))}
                </span>
              </div>
            </div>
          ))}
        </div>
      </section>

    </div>
  );
};
