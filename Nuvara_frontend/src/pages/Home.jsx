import { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { Link } from 'react-router-dom';
import { motion, AnimatePresence, useReducedMotion } from 'framer-motion';
import { ArrowUpRight, ArrowRight, Truck, ShieldCheck, RefreshCw, Headphones, ChevronLeft, ChevronRight } from 'lucide-react';
import { mockCategories, mockProducts, getLocalized } from '../utils/mockData';
import defaultTestimonials from '../utils/homeTestimonials';
import { useLocaleStore } from '../store/localeStore';
import { CustomerTestimonials } from '../components/home/CustomerTestimonials';
import { ProductCard } from '../components/product/ProductCard';
import api from '../services/api';
import './Home.css';

const interior = 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1600&auto=format&fit=crop&q=85';
const icons = { Truck, ShieldCheck, RefreshCw, Headphones };
function Reveal({ children, className = '' }) {
  return <section className={className}>{children}</section>;
}
function HorizontalStory({ products, str, title, note }) {
  return <section className="horizontal-story editorial-container">
    <div className="section-heading"><div><span className="eyebrow">NUVARA / 04</span><h2>{title}</h2></div><span className="story-scroll-note">{note}</span></div>
    <div className="horizontal-track">{products.slice(0, 4).map((product, index) => <Link className="story-product" to={`/product/${product.slug}`} key={product.id}><div><img src={Array.isArray(product.images) ? (typeof product.images[0] === 'object' ? product.images[0].path : product.images[0]) : product.image} alt={str(product.name)} loading="lazy"/><span>{String(index + 1).padStart(2, '0')}</span></div><strong>{str(product.name)}</strong><small>{str(product.brand || 'Nuvara')}</small></Link>)}</div>
  </section>;
}
export const Home = () => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();
  const reduce = useReducedMotion();
  const [data, setData] = useState(null);
  const [activeTab, setActiveTab] = useState('best');
  const [slide, setSlide] = useState(0);
  const [now, setNow] = useState(Date.now());
  useEffect(() => {
    const controller = new AbortController();
    api.get('/home', { signal: controller.signal, headers: { 'Accept-Language': locale } })
      .then(res => { setData(res.data); setSlide(0); })
      .catch(() => { if (!controller.signal.aborted) setData({}); });
    return () => controller.abort();
  }, [locale]);
  useEffect(() => {
    if (!data?.flash_sale?.ends_at) return;
    const timer = setInterval(() => setNow(Date.now()), 1000);
    return () => clearInterval(timer);
  }, [data?.flash_sale?.ends_at]);
  const str = value => typeof value === 'string' ? value : getLocalized(value, locale);
  const categories = data?.categories?.length ? data.categories : mockCategories;
  const products = activeTab === 'best'
    ? (data?.best_sellers?.length ? data.best_sellers : mockProducts.filter(p => p.isBestSeller))
    : (data?.new_arrivals?.length ? data.new_arrivals : mockProducts.filter(p => p.isNew));
  const slides = data?.hero_slides || [];
  const hero = slides[slide % Math.max(1, slides.length)];
  const promo = data?.promo_banner;
  const seconds = Math.max(0, Math.floor((Date.parse(data?.flash_sale?.ends_at) - now) / 1000));
  const saleTime = [Math.floor(seconds / 3600), Math.floor(seconds % 3600 / 60), seconds % 60].map(n => String(n).padStart(2, '0')).join(' : ');
  const trust = data?.trust_features?.length ? data.trust_features : ['free_shipping', 'secure_payment', 'easy_returns', 'support'].map((key, i) => ({ key, title: t(`home.${key}`), sub: t(`home.${key}_sub`), icon: Object.keys(icons)[i] }));
  const testimonials = data?.testimonials?.length ? data.testimonials : defaultTestimonials;
  const discoveryProducts = products.filter(product => product.stock > 0).slice(0, 4);
  return <div className="nuvara-home">
    <section className="edition-hero">
      <div className="hero-copy">
        <span className="eyebrow"><span className="edition-line" />{hero ? str(hero.badge) : t('editorial.eyebrow')}</span>
        <AnimatePresence mode="wait" initial={false}>
          <motion.div key={hero?.id || slide} initial={reduce ? false : { opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }} transition={{ duration: .25 }}>
            <h1>{hero ? str(hero.headline) : <>{t('editorial.hero_start')} <em>{t('editorial.hero_end')}</em></>}</h1>
            <p>{hero ? str(hero.sub) : t('editorial.hero_desc')}</p>
            <Link className="editorial-button" to={hero?.link || '/category/all'}>{hero ? str(hero.buttonText) : t('home.hero_cta')}<ArrowUpRight size={18} className="rtl-flip" /></Link>
          </motion.div>
        </AnimatePresence>
        <div className="hero-footnote"><span>01 — {t('editorial.curated')}</span><Link to="/about">{t('editorial.our_philosophy')}<ArrowRight size={14} className="rtl-flip" /></Link></div>
      </div>
      <div className="hero-photograph">
        <img key={hero?.id || 'interior'} src={hero?.image || hero?.product?.images?.[0] || interior} alt={hero ? str(hero.headline) : t('editorial.interior_alt')} fetchPriority="high" />
        <div className="photograph-caption"><span>{t('editorial.living_well')}</span><span>NUVARA / {String(slide + 1).padStart(2, '0')}</span></div>
        {slides.length > 1 && <div className="hero-controls"><button onClick={() => setSlide((slide + slides.length - 1) % slides.length)} aria-label={t('editorial.previous')}><ChevronLeft size={18} /></button><span>{slide + 1} / {slides.length}</span><button onClick={() => setSlide((slide + 1) % slides.length)} aria-label={t('editorial.next')}><ChevronRight size={18} /></button></div>}
      </div>
    </section>
    <div className="service-strip editorial-container">{trust.map(item => { const Icon = icons[item.icon] || ShieldCheck; return <div key={item.id || item.key}><Icon size={21} strokeWidth={1.3}/><span><strong>{str(item.title)}</strong><small>{str(item.sub)}</small></span></div>; })}</div>
    <Reveal className="brand-statement editorial-container"><span className="eyebrow">NUVARA / 02</span><p>{t('editorial.brand_statement', { defaultValue: 'Objects with a point of view, chosen for the way life actually feels.' })}</p><span className="statement-rule" /></Reveal>
    <Reveal className="editorial-container category-section">
      <div className="section-heading"><div><span className="eyebrow">{t('editorial.find_your_everyday')}</span><h2>{t('editorial.category_title')}</h2></div><Link className="text-link" to="/category/all">{t('editorial.explore_all')}<ArrowUpRight size={17} className="rtl-flip" /></Link></div>
      <div className="curated-categories">{categories.map((category, index) => <Link className="curated-category" key={category.id} to={`/category/${category.slug}`}><div className="category-photograph"><img src={category.image || interior} alt={str(category.name)} loading="lazy" /><span>{String(index + 1).padStart(2, '0')}</span></div><div className="category-caption"><h3>{str(category.name)}</h3><ArrowUpRight size={19} className="rtl-flip" /></div></Link>)}</div>
    </Reveal>
    <Reveal className="editorial-container featured-section">
      <div className="section-heading"><div><span className="eyebrow">{t('editorial.selected_for_you')}</span><h2>{t('editorial.featured_title')}</h2></div><div className="collection-tabs" role="group" aria-label={t('editorial.selected_for_you')}>{['best', 'new'].map(tab => <button key={tab} aria-pressed={activeTab === tab} onClick={() => setActiveTab(tab)}>{t(tab === 'best' ? 'home.best_sellers' : 'home.new_arrivals')}</button>)}</div></div>
      <div className="editorial-products">{products.map(product => <ProductCard key={product.id} product={product} />)}</div>
      <div className="collection-link"><Link className="text-link" to="/category/all">{t('editorial.view_collection')}<ArrowRight size={17} className="rtl-flip" /></Link></div>
    </Reveal>
    <Reveal className="editorial-story">
      <div className="story-image"><img src={promo?.image || interior} alt={t('editorial.interior_alt')} loading="lazy" /></div>
      <div className="story-copy"><span className="eyebrow">{promo ? str(promo.badge) : t('editorial.story_eyebrow')}</span><h2>{promo ? str(promo.headline) : t('editorial.story_title')}</h2><p>{promo ? str(promo.sub) : t('editorial.story_desc')}</p><Link className="editorial-button" to={promo?.link || '/category/home-living'}>{promo ? str(promo.buttonText) : t('editorial.story_cta')}<ArrowUpRight size={18} className="rtl-flip" /></Link><span className="story-signature">{t('editorial.less_better')}</span></div>
    </Reveal>
    <div className="editorial-marquee" aria-hidden="true"><div>NUVARA&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;{t('home.new_arrivals')}&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;{t('home.flash_deals')}&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;NUVARA&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;</div></div>
    <HorizontalStory products={discoveryProducts.length ? discoveryProducts : mockProducts} str={str} title={t('editorial.horizontal_title')} note={t('editorial.horizontal_note')} />
    <Reveal className="discovery-panel editorial-container">
      <div><span className="eyebrow">NUVARA / 05</span><h2>{t('editorial.discovery_title', { defaultValue: 'Find your next favorite.' })}</h2><p>{t('editorial.discovery_desc', { defaultValue: 'Start with a feeling. We will take you to the pieces that fit.' })}</p></div>
      <div className="discovery-actions"><Link to="/category/all?sort=new">{t('home.new_arrivals')}<ArrowUpRight size={17} className="rtl-flip" /></Link><Link to="/category/all?deals=1">{t('home.flash_deals')}<ArrowUpRight size={17} className="rtl-flip" /></Link><Link to="/category/home-living">{t('home.shop_category')}<ArrowUpRight size={17} className="rtl-flip" /></Link></div>
    </Reveal>
    {data?.flash_products?.length > 0 && <Reveal className="editorial-container featured-section"><div className="section-heading"><div><span className="eyebrow">{t('home.flash_deals')}</span><h2>{str(data.flash_sale?.title) || t('editorial.special_finds')}</h2></div>{seconds > 0 && <span className="sale-clock">{t('home.flash_deals_ends')} <b dir="ltr">{saleTime}</b></span>}</div><div className="editorial-products">{data.flash_products.map(product => <ProductCard key={product.id} product={product} />)}</div></Reveal>}
    <CustomerTestimonials testimonials={testimonials} />
    <Reveal className="final-cta"><img src={promo?.image || interior} alt="" loading="lazy"/><div><span className="eyebrow">NUVARA / 06</span><h2>{t('editorial.final_title', { defaultValue: 'Find what feels like you.' })}</h2><Link className="editorial-button" to="/category/all">{t('editorial.final_cta', { defaultValue: 'Explore Nuvara' })}<ArrowUpRight size={18} className="rtl-flip" /></Link></div></Reveal>
  </div>;
};
