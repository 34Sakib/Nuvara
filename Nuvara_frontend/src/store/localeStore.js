import { create } from 'zustand';
import i18n from 'i18next';

// Helper to set cookie
const setCookie = (name, value, days = 365) => {
  const date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  const expires = "; expires=" + date.toUTCString();
  document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
};

// Font mapping for locales
const fontMap = {
  en: 'font-sans',
  es: 'font-sans',
  bn: 'font-bengali',
  ar: 'font-arabic'
};

export const useLocaleStore = create((set) => {
  const getInitialLocale = () => {
    if (typeof window === 'undefined') return 'en';
    const stored = localStorage.getItem('app_locale');
    if (stored) return stored;
    
    // Auto-detect browser language
    const browserLang = navigator.language.split('-')[0];
    const supported = ['en', 'bn', 'ar', 'es'];
    return supported.includes(browserLang) ? browserLang : 'en';
  };

  const initialLocale = getInitialLocale();

  // Apply updates to DOM
  const applyLocaleConfig = (lang) => {
    const isRtl = lang === 'ar';
    document.documentElement.dir = isRtl ? 'rtl' : 'ltr';
    document.documentElement.lang = lang;
    
    // Update font class on body
    if (document.body) {
      Object.values(fontMap).forEach(cls => document.body.classList.remove(cls));
      document.body.classList.add(fontMap[lang] || 'font-sans');
    }
    
    // Update i18n instance
    i18n.changeLanguage?.(lang);
  };

  // Run on load
  setTimeout(() => applyLocaleConfig(initialLocale), 10);

  return {
    locale: initialLocale,
    setLocale: (newLocale) => {
      localStorage.setItem('app_locale', newLocale);
      setCookie('app_locale', newLocale);
      applyLocaleConfig(newLocale);
      set({ locale: newLocale });
    }
  };
});
