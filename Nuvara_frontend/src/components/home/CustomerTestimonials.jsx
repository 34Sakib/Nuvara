import { useState, useEffect, useCallback, useMemo, useRef } from 'react';
import { useTranslation } from 'react-i18next';
import { motion, AnimatePresence, useReducedMotion } from 'framer-motion';
import { Star } from 'lucide-react';
import { useLocaleStore } from '../../store/localeStore';
import { getLocalized } from '../../utils/mockData';
import defaultTestimonials from '../../utils/homeTestimonials';
import './CustomerTestimonials.css';

// Brand theme palettes for dynamic cards (Forest Green, Warm Brass, Muted Wine, Slate, Amber, Teal)
const THEMES = ['green', 'brass', 'wine', 'slate', 'amber', 'teal'];

const THEME_STYLES = {
  green: {
    accentColor: 'var(--green, #1F3A2E)',
    gradient: 'linear-gradient(135deg, #1F3A2E 0%, #2C4B3C 100%)',
    borderColor: 'rgba(31, 58, 46, 0.25)'
  },
  brass: {
    accentColor: 'var(--brass, #B8863B)',
    gradient: 'linear-gradient(135deg, #B8863B 0%, #8C6226 100%)',
    borderColor: 'rgba(184, 134, 59, 0.32)'
  },
  wine: {
    accentColor: 'var(--wine, #6B2737)',
    gradient: 'linear-gradient(135deg, #6B2737 0%, #441721 100%)',
    borderColor: 'rgba(107, 39, 55, 0.28)'
  },
  slate: {
    accentColor: '#334155',
    gradient: 'linear-gradient(135deg, #334155 0%, #1E293B 100%)',
    borderColor: 'rgba(51, 65, 85, 0.25)'
  },
  amber: {
    accentColor: '#D97706',
    gradient: 'linear-gradient(135deg, #D97706 0%, #92400E 100%)',
    borderColor: 'rgba(217, 119, 6, 0.28)'
  },
  teal: {
    accentColor: '#0F766E',
    gradient: 'linear-gradient(135deg, #0F766E 0%, #115E59 100%)',
    borderColor: 'rgba(15, 118, 110, 0.25)'
  }
};

function getInitials(name) {
  if (!name || typeof name !== 'string') return '★';
  const parts = name.trim().split(/\s+/);
  if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

export const CustomerTestimonials = ({ testimonials: dynamicData }) => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();
  const isReducedMotion = useReducedMotion();

  // Helper for localized string extraction (handles both raw strings and i18n objects)
  const str = useCallback((val) => {
    if (!val) return '';
    return typeof val === 'string' ? val : getLocalized(val, locale);
  }, [locale]);

  // Process dynamic or fallback data
  const list = useMemo(() => {
    const raw = (Array.isArray(dynamicData) && dynamicData.length > 0) 
      ? dynamicData 
      : defaultTestimonials;

    return raw.map((item, index) => {
      const themeKey = item.theme || THEMES[index % THEMES.length];
      const styles = THEME_STYLES[themeKey] || THEME_STYLES.brass;
      const initials = item.initials || getInitials(item.name);
      
      return {
        id: item.id || `testimonial-${index + 1}`,
        name: item.name || 'Nuvara Customer',
        role: item.role ? str(item.role) : t('home.verified_buyer', { defaultValue: 'Verified Buyer' }),
        rating: Math.min(5, Math.max(1, Number(item.rating) || 5)),
        quote: str(item.quote),
        initials,
        theme: themeKey,
        accentColor: item.accentColor || styles.accentColor,
        gradient: item.gradient || styles.gradient,
        borderColor: styles.borderColor
      };
    });
  }, [dynamicData, str, t]);

  // Responsive card count detection
  const [windowWidth, setWindowWidth] = useState(typeof window !== 'undefined' ? window.innerWidth : 1200);

  useEffect(() => {
    const handleResize = () => setWindowWidth(window.innerWidth);
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const maxVisibleForScreen = useMemo(() => {
    if (windowWidth <= 640) return 1;
    if (windowWidth <= 1024) return 2;
    return 3;
  }, [windowWidth]);

  const total = list.length;
  const visibleCount = Math.min(total, maxVisibleForScreen);

  const [startIndex, setStartIndex] = useState(0);
  const [isPaused, setIsPaused] = useState(false);
  const autoPlayTimerRef = useRef(null);

  // Auto-advance by 1 card
  const nextSlide = useCallback(() => {
    setStartIndex(prev => (prev + 1) % total);
  }, [total]);

  // Continuous auto-slider (every 3.8s)
  useEffect(() => {
    if (total <= visibleCount || isPaused) return;

    autoPlayTimerRef.current = setInterval(() => {
      nextSlide();
    }, 3800);

    return () => {
      if (autoPlayTimerRef.current) {
        clearInterval(autoPlayTimerRef.current);
      }
    };
  }, [total, visibleCount, isPaused, nextSlide]);

  // Compute currently visible cards
  const visibleCards = useMemo(() => {
    if (total === 0) return [];
    const items = [];
    for (let i = 0; i < visibleCount; i++) {
      const idx = (startIndex + i) % total;
      items.push({ 
        item: list[idx], 
        key: `${list[idx].id}-${startIndex}-${i}` 
      });
    }
    return items;
  }, [list, startIndex, total, visibleCount]);

  if (!list || list.length === 0) return null;

  return (
    <section 
      className="customer-testimonials-section editorial-container"
      aria-label={t('home.testimonials', { defaultValue: 'What Our Customers Say' })}
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
      onTouchStart={() => setIsPaused(true)}
      onTouchEnd={() => setIsPaused(false)}
    >
      {/* Nuvara Section Header */}
      <div className="testimonials-section-header">
        <span className="eyebrow">
          <span className="edition-line" />
          {t('home.testimonials', { defaultValue: 'What Our Customers Say' })}
        </span>
        <h2>{t('editorial.testimonials_title', { defaultValue: 'Trusted by Discerning Homes' })}</h2>
      </div>

      {/* Dynamic Multi-Card Slider Viewport */}
      <div className="testimonials-viewport">
        <AnimatePresence mode="popLayout" initial={false}>
          <motion.div
            key={startIndex}
            className="testimonials-grid"
            style={{
              gridTemplateColumns: `repeat(${visibleCount}, minmax(0, 1fr))`
            }}
            initial={isReducedMotion ? false : { opacity: 0.85, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            exit={isReducedMotion ? false : { opacity: 0.85, x: -20 }}
            transition={{ duration: 0.4, ease: [0.2, 0, 0, 1] }}
          >
            {visibleCards.map(({ item, key }) => (
              <article
                key={key}
                className="testimonial-card"
                data-theme-card={item.theme}
              >
                {/* Top Quote Icon with dynamic accent */}
                <span
                  className="testimonial-quote-icon"
                  style={{ color: item.accentColor }}
                  aria-hidden="true"
                >
                  “
                </span>

                {/* Dynamic Quote Text */}
                <p className="testimonial-body-text">
                  {item.quote}
                </p>

                {/* Bottom Author Row with CSS Monogram, Stars & Role */}
                <div className="testimonial-author-row">
                  <div
                    className="testimonial-monogram-avatar"
                    style={{ background: item.gradient }}
                    aria-hidden="true"
                  >
                    {item.initials}
                  </div>
                  <div className="testimonial-author-info">
                    <strong>{item.name}</strong>
                    <small>{item.role}</small>
                  </div>
                  {item.rating > 0 && (
                    <div 
                      className="testimonial-rating-stars-mini" 
                      aria-label={`${item.rating} / 5 stars`}
                      style={{ marginInlineStart: 'auto', display: 'flex', gap: '2px', color: item.accentColor }}
                    >
                      {Array.from({ length: item.rating }).map((_, i) => (
                        <Star key={i} size={12} fill="currentColor" stroke="none" />
                      ))}
                    </div>
                  )}
                </div>
              </article>
            ))}
          </motion.div>
        </AnimatePresence>
      </div>

      {/* Dynamic Pagination Dots */}
      {total > visibleCount && (
        <div className="testimonials-dots-nav" role="tablist" aria-label="Testimonial slider pages">
          {list.map((_, dotIdx) => (
            <button
              key={dotIdx}
              type="button"
              role="tab"
              aria-selected={startIndex === dotIdx}
              className={`testimonial-dot-btn ${startIndex === dotIdx ? 'active' : ''}`}
              onClick={() => setStartIndex(dotIdx)}
              aria-label={`Go to slide ${dotIdx + 1}`}
            />
          ))}
        </div>
      )}
    </section>
  );
};

export default CustomerTestimonials;
