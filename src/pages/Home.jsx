import React, { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { Link as RouterLink } from 'react-router-dom';
import { motion } from 'framer-motion';
import { Truck, ShieldCheck, RefreshCw, Headphones, ArrowRight, Flame } from 'lucide-react';
import { mockCategories, mockProducts, getLocalized } from '../utils/mockData';
import { useLocaleStore } from '../store/localeStore';
import { ProductCard } from '../components/product/ProductCard';
import { CategoryCard } from '../components/category/CategoryCard';
import { SectionDivider } from '../components/ui/SectionDivider';
import { Button } from '../components/ui/Button';

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
  
  // Tab State (Best Sellers vs New Arrivals)
  const [activeTab, setActiveTab] = useState('best'); // 'best' | 'new'

  // Countdown timer state for Flash Deals (simulates 4 hours countdown)
  const [timeLeft, setTimeLeft] = useState({ hours: 4, minutes: 34, seconds: 12 });

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
          return { hours: 4, minutes: 0, seconds: 0 }; // reset
        }
      });
    }, 1000);
    return () => clearInterval(timer);
  }, []);

  const bestSellers = mockProducts.filter((p) => p.isBestSeller);
  const newArrivals = mockProducts.filter((p) => p.isNew);
  const activeProducts = activeTab === 'best' ? bestSellers : newArrivals;

  const padZero = (num) => String(num).padStart(2, '0');

  // Choose a premium Still-Life hero product from our database (e.g. AeroSound headphones)
  const heroProduct = mockProducts[0];

  return (
    <div className="space-y-16 animate-fade-in">
      
      {/* 1. Still-Life Hero (Fades & Rises with Staggered Entrance) */}
      <motion.section 
        variants={containerVariants}
        initial="hidden"
        animate="visible"
        className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 md:pt-16 pb-8 grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-center"
      >
        {/* Left Column: Overlapping Typography Block (Cols 1-6) */}
        <motion.div 
          variants={textVariants}
          className="lg:col-span-6 z-10 text-left rtl:text-right lg:-mr-12 rtl:lg:-ml-12 rtl:lg:-mr-0"
        >
          <span className="text-[10px] text-brass uppercase font-bold tracking-widest bg-brass/5 border border-brass/15 px-3.5 py-1.5 rounded-full select-none">
            Boutique Collection
          </span>
          <h1 className="text-4xl md:text-5xl lg:text-6xl font-serif font-medium tracking-tight leading-[1.05] text-text-primary uppercase mt-5">
            {getLocalized(heroProduct.name, locale)}
          </h1>
          
          {/* Left Aligned Divider */}
          <div className="flex items-center justify-start my-5 select-none">
            <div className="w-14 h-[1px] bg-brass/65"></div>
            <span className="mx-2.5 text-[8px] text-brass leading-none">◆</span>
            <div className="w-14 h-[1px] bg-brass/65"></div>
          </div>

          <p className="text-sm md:text-base text-text-secondary max-w-lg leading-relaxed font-sans mb-8">
            {getLocalized(heroProduct.description, locale)}
          </p>

          {/* Outlined Secondary Button CTA - quiet until PDP/actions */}
          <RouterLink to={`/product/${heroProduct.slug}`}>
            <Button 
              variant="secondary" 
              className="px-6 py-3 uppercase text-xs tracking-wider"
              icon={ArrowRight}
            >
              View Product
            </Button>
          </RouterLink>
        </motion.div>

        {/* Right Column: Still-Life Photo on a --surface-2 field (Cols 7-12) */}
        <motion.div 
          variants={imageVariants}
          className="lg:col-span-6 flex justify-center items-center"
        >
          <div className="bg-surface-2 rounded-[8px] p-6 sm:p-12 w-full max-w-lg aspect-square flex items-center justify-center shadow-sm overflow-hidden relative">
            <img
              src={heroProduct.images[0]}
              alt={getLocalized(heroProduct.name, locale)}
              className="w-full h-full object-cover rounded-[4px] shadow-sm transform hover:scale-[1.02] transition-transform duration-500"
            />
          </div>
        </motion.div>
      </motion.section>

      {/* 2. Trust Bar */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-6 bg-surface border border-border rounded-[8px] p-6 md:p-8 transition-colors" style={{ boxShadow: 'var(--shadow)' }}>
          <div className="flex items-center space-x-3.5 rtl:space-x-reverse">
            <div className="p-3 rounded-lg bg-green/5 text-green">
              <Truck className="w-6 h-6" />
            </div>
            <div>
              <h4 className="text-sm font-bold text-text-primary">{t('home.free_shipping')}</h4>
              <p className="text-xs text-text-secondary mt-0.5">{t('home.free_shipping_sub')}</p>
            </div>
          </div>
          <div className="flex items-center space-x-3.5 rtl:space-x-reverse">
            <div className="p-3 rounded-lg bg-green/5 text-green">
              <ShieldCheck className="w-6 h-6" />
            </div>
            <div>
              <h4 className="text-sm font-bold text-text-primary">{t('home.secure_payment')}</h4>
              <p className="text-xs text-text-secondary mt-0.5">{t('home.secure_payment_sub')}</p>
            </div>
          </div>
          <div className="flex items-center space-x-3.5 rtl:space-x-reverse">
            <div className="p-3 rounded-lg bg-green/5 text-green">
              <RefreshCw className="w-6 h-6" />
            </div>
            <div>
              <h4 className="text-sm font-bold text-text-primary">{t('home.easy_returns')}</h4>
              <p className="text-xs text-text-secondary mt-0.5">{t('home.easy_returns_sub')}</p>
            </div>
          </div>
          <div className="flex items-center space-x-3.5 rtl:space-x-reverse">
            <div className="p-3 rounded-lg bg-green/5 text-green">
              <Headphones className="w-6 h-6" />
            </div>
            <div>
              <h4 className="text-sm font-bold text-text-primary">{t('home.support')}</h4>
              <p className="text-xs text-text-secondary mt-0.5">{t('home.support_sub')}</p>
            </div>
          </div>
        </div>
      </section>

      {/* 3. Shop by Category */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-6">
          <h2 className="text-2xl font-bold font-display text-text-primary uppercase tracking-wide">
            {t('home.shop_category')}
          </h2>
          <SectionDivider />
        </div>
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-6">
          {mockCategories.map((cat) => (
            <CategoryCard key={cat.id} category={cat} />
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
          {mockProducts.slice(0, 4).map((prod) => (
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
