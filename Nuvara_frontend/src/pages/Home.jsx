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
import { Button } from '../components/ui/Button';
import api from '../services/api';

// Animations variables
const containerVariants = {
  hidden: { opacity: 0 },
  visible: {
    opacity: 1,
    transition: {
      staggerChildren: 0.06 // 60ms stagger spacing
    }
  }
};

const textVariants = {
  hidden: { y: 20, opacity: 0 },
  visible: { 
    y: 0, 
    opacity: 1, 
    transition: { duration: 0.55, ease: 'easeOut' } 
  }
};

const imageVariants = {
  hidden: { y: 30, opacity: 0 },
  visible: { 
    y: 0, 
    opacity: 1, 
    transition: { duration: 0.55, ease: 'easeOut', delay: 0.1 } // 100ms delay behind headline
  }
};

const testimonials = [
  {
    id: 1,
    name: 'Sarah M.',
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
  
  const [categories, setCategories] = useState([]);
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('best');
  const [timeLeft, setTimeLeft] = useState({ hours: 4, minutes: 34, seconds: 12 });
  const [currentSlide, setCurrentSlide] = useState(0);
  const [isHovered, setIsHovered] = useState(false);

  useEffect(() => {
    const fetchHomeData = async () => {
      try {
        const [catRes, prodRes] = await Promise.all([
          api.get('/categories'),
          api.get('/products?limit=24')
        ]);
        setCategories(catRes.data);
        setProducts(prodRes.data.data || []);
      } catch (err) {
        console.error("Failed fetching homepage data", err);
      } finally {
        setLoading(false);
      }
    };
    fetchHomeData();
  }, []);

  useEffect(() => {
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
  }, []);

  useEffect(() => {
    if (isHovered) return;
    const slideTimer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % 4);
    }, 5000);
    return () => clearInterval(slideTimer);
  }, [isHovered]);

  const padZero = (num) => String(num).padStart(2, '0');

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

  const activeCategories = categories.length > 0 ? categories : mockCategories;
  const activeProductList = products.length > 0 ? products : mockProducts;
  
  const bestSellers = activeProductList.filter((p) => p.is_best_seller || p.isBestSeller);
  const newArrivals = activeProductList.filter((p) => p.is_new || p.isNew);
  const activeProducts = activeTab === 'best' ? bestSellers : newArrivals;

  const heroSlides = [
    {
      id: 1,
      badge: 'EXCLUSIVE',
      badgeText: 'Sale',
      headline: 'Happening Now!',
      sub: 'Discover amazing deals and discounts on our eCommerce website! Shop now for the best offers!',
      buttonText: 'SHOP NOW',
      link: '/category/electronics',
      product: activeProductList[0] || mockProducts[0],
      bgGradient: 'from-[#FDE047] via-[#FACC15] to-[#EAB308]',
      textColor: 'text-gray-950',
      subTextColor: 'text-gray-900/90 font-medium',
      badgeBg: 'bg-white text-black font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
      badgeSecondaryText: 'text-gray-950 font-black text-3xl sm:text-4xl md:text-5xl ml-2 rtl:mr-2 rtl:ml-0 inline-block',
      btnStyle: 'bg-black text-white hover:bg-gray-800 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm',
      dotActive: 'bg-black w-9',
      dotInactive: 'bg-black/30 hover:bg-black/60',
      starsColor: 'text-white/90'
    },
    {
      id: 2,
      badge: 'NEW ARRIVAL',
      badgeText: 'Audio Pro',
      headline: 'Next-Gen Wireless Sound',
      sub: 'Immerse yourself in crystal clear studio audio with ultra active noise cancellation.',
      buttonText: 'EXPLORE DEAL',
      link: '/product/aerosound-pro-wireless-headphones',
      product: activeProductList[1] || mockProducts[1],
      bgGradient: 'from-[#10B981] via-[#059669] to-[#047857]',
      textColor: 'text-white',
      subTextColor: 'text-emerald-50/95 font-medium',
      badgeBg: 'bg-emerald-300 text-emerald-950 font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
      badgeSecondaryText: 'text-white font-black text-3xl sm:text-4xl md:text-5xl ml-2 rtl:mr-2 rtl:ml-0 inline-block',
      btnStyle: 'bg-white text-emerald-950 hover:bg-emerald-50 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm',
      dotActive: 'bg-white w-9',
      dotInactive: 'bg-white/30 hover:bg-white/60',
      starsColor: 'text-emerald-200/90'
    },
    {
      id: 3,
      badge: 'HOT DEAL',
      badgeText: 'Style',
      headline: 'Urban Lifestyle Fashion',
      sub: 'Step out in confidence with our premium crafted street footwear & boutique fashion trends.',
      buttonText: 'SHOP FASHION',
      link: '/category/fashion',
      product: activeProductList[2] || mockProducts[2],
      bgGradient: 'from-[#F43F5E] via-[#E11D48] to-[#BE123C]',
      textColor: 'text-white',
      subTextColor: 'text-rose-50/95 font-medium',
      badgeBg: 'bg-white text-rose-950 font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
      badgeSecondaryText: 'text-white font-black text-3xl sm:text-4xl md:text-5xl ml-2 rtl:mr-2 rtl:ml-0 inline-block',
      btnStyle: 'bg-gray-950 text-white hover:bg-gray-900 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm',
      dotActive: 'bg-white w-9',
      dotInactive: 'bg-white/30 hover:bg-white/60',
      starsColor: 'text-rose-200/90'
    },
    {
      id: 4,
      badge: 'TRENDING',
      badgeText: 'Decor',
      headline: 'Modern Living & Home',
      sub: 'Redefine your living space with minimal aesthetic lighting and smart home accessories.',
      buttonText: 'DISCOVER MORE',
      link: '/category/home-living',
      product: activeProductList[3] || mockProducts[3],
      bgGradient: 'from-[#6366F1] via-[#4F46E5] to-[#3730A3]',
      textColor: 'text-white',
      subTextColor: 'text-indigo-50/95 font-medium',
      badgeBg: 'bg-amber-300 text-indigo-950 font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
      badgeSecondaryText: 'text-white font-black text-3xl sm:text-4xl md:text-5xl ml-2 rtl:mr-2 rtl:ml-0 inline-block',
      btnStyle: 'bg-white text-indigo-950 hover:bg-indigo-50 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm',
      dotActive: 'bg-white w-9',
      dotInactive: 'bg-white/30 hover:bg-white/60',
      starsColor: 'text-indigo-200/90'
    }
  ];

  const activeSlide = heroSlides[currentSlide];

  return (
    <div className="space-y-16 animate-fade-in">
      
      {/* 1. Dynamic Color-Changing Full-Width Hero Slider */}
      <section 
        onMouseEnter={() => setIsHovered(true)}
        onMouseLeave={() => setIsHovered(false)}
        className={`w-full relative overflow-hidden transition-all duration-700 ease-in-out bg-gradient-to-r ${activeSlide.bgGradient} py-12 md:py-20 flex items-center shadow-lg`}
      >
        {/* Floating Sparkles & Stars Decoration */}
        <div className={`absolute inset-0 overflow-hidden pointer-events-none select-none ${activeSlide.starsColor}`}>
          <Star className="w-5 h-5 absolute top-10 left-12 opacity-80 animate-pulse" />
          <Sparkles className="w-6 h-6 absolute top-16 right-1/3 opacity-70 animate-bounce" />
          <Star className="w-4 h-4 absolute bottom-20 left-1/4 opacity-60" />
          <Star className="w-6 h-6 absolute bottom-12 right-12 opacity-80 animate-pulse" />
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-center">
            
            {/* Left Content Column (Cols 1-7) */}
            <div className="lg:col-span-7 text-left rtl:text-right">
              <AnimatePresence mode="wait">
                <motion.div
                  key={activeSlide.id}
                  initial={{ opacity: 0, x: -35 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 35 }}
                  transition={{ duration: 0.45, ease: 'easeOut' }}
                >
                  {/* Badge Line: EXCLUSIVE Sale */}
                  <div className="flex items-center space-x-3 rtl:space-x-reverse mb-4 flex-wrap gap-y-2">
                    <span className={activeSlide.badgeBg}>
                      {activeSlide.badge}
                    </span>
                    <span className={activeSlide.badgeSecondaryText}>
                      {activeSlide.badgeText}
                    </span>
                  </div>

                  {/* Headline */}
                  <h1 className={`text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.05] ${activeSlide.textColor} mb-4`}>
                    {activeSlide.headline}
                  </h1>

                  {/* Subtitle */}
                  <p className={`text-sm sm:text-base max-w-lg leading-relaxed ${activeSlide.subTextColor} mb-8`}>
                    {activeSlide.sub}
                  </p>

                  {/* CTA Button */}
                  <RouterLink to={activeSlide.link}>
                    <button className={`${activeSlide.btnStyle} transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center gap-2.5`}>
                      <span>{activeSlide.buttonText}</span>
                      <ArrowRight className="w-4 h-4 rtl-flip" />
                    </button>
                  </RouterLink>
                </motion.div>
              </AnimatePresence>
            </div>

            {/* Right Product Image Column (Cols 8-12) */}
            <div className="lg:col-span-5 flex justify-center items-center relative">
              <AnimatePresence mode="wait">
                <motion.div
                  key={activeSlide.id}
                  initial={{ opacity: 0, scale: 0.85, rotate: -4 }}
                  animate={{ opacity: 1, scale: 1, rotate: 0 }}
                  exit={{ opacity: 0, scale: 0.85, rotate: 4 }}
                  transition={{ duration: 0.5, ease: 'easeOut' }}
                  className="w-full max-w-md aspect-square flex items-center justify-center relative"
                >
                  <div className="w-full h-full rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20 relative group bg-black/10 backdrop-blur-sm">
                    <img
                      src={activeSlide.product?.images?.[0] || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'}
                      alt={getLocalized(activeSlide.product?.name, locale)}
                      className="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                      <span className="text-white text-xs font-bold truncate">
                        {getLocalized(activeSlide.product?.name, locale)} — ${activeSlide.product?.price}
                      </span>
                    </div>
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
              className={`h-2.5 rounded-full transition-all duration-400 ${
                currentSlide === index 
                  ? activeSlide.dotActive 
                  : activeSlide.dotInactive + ' w-2.5'
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
            visible: {
              opacity: 1,
              y: 0,
              transition: {
                staggerChildren: 0.12,
                duration: 0.5
              }
            }
          }}
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-surface/90 backdrop-blur-md border border-border/80 rounded-2xl p-6 md:p-8 shadow-md hover:shadow-xl transition-all duration-300"
        >
          {[
            {
              key: 'free_shipping',
              title: t('home.free_shipping'),
              sub: t('home.free_shipping_sub'),
              icon: Truck,
              iconColor: 'text-emerald-500 dark:text-emerald-400',
              bgColor: 'bg-emerald-500/10 dark:bg-emerald-500/15',
              hoverEffect: { x: [0, 6, -2, 0] }
            },
            {
              key: 'secure_payment',
              title: t('home.secure_payment'),
              sub: t('home.secure_payment_sub'),
              icon: ShieldCheck,
              iconColor: 'text-amber-500 dark:text-amber-400',
              bgColor: 'bg-amber-500/10 dark:bg-amber-500/15',
              hoverEffect: { scale: 1.15, rotate: [0, -10, 10, 0] }
            },
            {
              key: 'easy_returns',
              title: t('home.easy_returns'),
              sub: t('home.easy_returns_sub'),
              icon: RefreshCw,
              iconColor: 'text-blue-500 dark:text-blue-400',
              bgColor: 'bg-blue-500/10 dark:bg-blue-500/15',
              hoverEffect: { rotate: 180 }
            },
            {
              key: 'support',
              title: t('home.support'),
              sub: t('home.support_sub'),
              icon: Headphones,
              iconColor: 'text-purple-500 dark:text-purple-400',
              bgColor: 'bg-purple-500/10 dark:bg-purple-500/15',
              hoverEffect: { y: [-3, 3, -3] }
            }
          ].map((item) => {
            const Icon = item.icon;
            return (
              <motion.div 
                key={item.key}
                variants={{
                  hidden: { opacity: 0, y: 20, scale: 0.95 },
                  visible: { opacity: 1, y: 0, scale: 1, transition: { type: 'spring', stiffness: 260, damping: 20 } }
                }}
                whileHover={{ y: -4, scale: 1.02 }}
                className="group flex items-center space-x-4 rtl:space-x-reverse p-3.5 rounded-xl hover:bg-bg-primary/60 border border-transparent hover:border-border/60 transition-all duration-300 cursor-pointer"
              >
                <motion.div 
                  whileHover={item.hoverEffect}
                  transition={{ duration: 0.4, ease: "easeInOut" }}
                  className={`p-3.5 rounded-xl ${item.bgColor} ${item.iconColor} group-hover:scale-110 transition-transform duration-300 flex-shrink-0 shadow-sm`}
                >
                  <Icon className="w-6 h-6" />
                </motion.div>
                <div>
                  <h4 className="text-sm font-bold text-text-primary group-hover:text-accent transition-colors duration-200">
                    {item.title}
                  </h4>
                  <p className="text-xs text-text-secondary mt-0.5 font-medium leading-relaxed">
                    {item.sub}
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
          {activeCategories.map((cat, idx) => (
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
              {t('home.flash_deals')}
            </h2>
          </div>
          <SectionDivider />
          
          {/* Timer Display - uses Wine for sale details */}
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
          {activeProductList.slice(0, 4).map((prod) => (
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
          style={{ backgroundImage: `linear-gradient(to right, rgba(0,0,0,0.85) 40%, rgba(0,0,0,0.2)), url('https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1200&auto=format&fit=crop&q=80')` }}
        >
          <div className="max-w-md text-white text-left rtl:text-right">
            <span className="text-brass font-bold text-xs uppercase tracking-widest bg-brass/20 px-3 py-1.5 rounded-full">
              Limited Edition
            </span>
            <h2 className="text-2xl md:text-4xl font-serif font-medium tracking-tight uppercase mt-4 mb-3 text-[#F5EFE4]">
              {t('home.promo_heading')}
            </h2>
            <p className="text-sm text-[#F5EFE4]/80 leading-relaxed mb-6 font-sans">
              {t('home.promo_sub')}
            </p>
            <RouterLink 
              to="/category/home-living"
              className="inline-flex items-center px-5 py-2.5 bg-green hover:bg-green-soft text-[#F5EFE4] text-xs font-bold rounded-[4px] transition-colors"
            >
              Explore Collection
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
          {testimonials.map((test) => (
            <div 
              key={test.id} 
              className="bg-surface border border-border p-6 rounded-[8px] transition-all duration-200 flex flex-col justify-between"
              style={{ boxShadow: 'var(--shadow)' }}
            >
              <p className="text-sm leading-relaxed text-text-secondary italic">
                "{getLocalized(test.quote, locale)}"
              </p>
              <div className="mt-6 flex justify-between items-center border-t border-border pt-4">
                <span className="font-bold text-sm text-text-primary">{test.name}</span>
                <span className="text-xs text-brass font-extrabold flex">
                  {Array.from({ length: test.rating }).map((_, i) => (
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
