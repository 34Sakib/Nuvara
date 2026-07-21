import React, { useState, useRef, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Globe, ChevronDown } from 'lucide-react';
import { useLocaleStore } from '../../store/localeStore';

const locales = [
  { code: 'en', label: 'English', flag: '🇺🇸', native: 'English' },
  { code: 'bn', label: 'বাংলা', flag: '🇧🇩', native: 'বাংলা' },
  { code: 'ar', label: 'العربية', flag: '🇸🇦', native: 'العربية' },
  { code: 'es', label: 'Español', flag: '🇪🇸', native: 'Español' }
];

export const LanguageSwitcher = () => {
  const { locale, setLocale } = useLocaleStore();
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef(null);

  // Close dropdown on click outside
  useEffect(() => {
    const handleClickOutside = (e) => {
      if (dropdownRef.current && !dropdownRef.current.contains(e.target)) {
        setIsOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const currentLocale = locales.find((l) => l.code === locale) || locales[0];

  return (
    <div className="relative inline-block text-left select-none" ref={dropdownRef}>
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="flex items-center space-x-1 sm:space-x-1.5 rtl:space-x-reverse px-2.5 py-2 sm:px-3 sm:py-2 rounded-full border border-border bg-surface hover:bg-surface-2 transition-all duration-200 text-sm font-medium text-text-primary focus:outline-none focus:ring-2 focus:ring-green/50"
      >
        <Globe className="w-3.5 h-3.5 text-text-secondary" />
        <span className="text-xs leading-none">{currentLocale.flag}</span>
        <span className="hidden sm:inline text-xs font-bold">{currentLocale.native}</span>
        <ChevronDown className={`w-3.5 h-3.5 text-text-secondary transition-transform duration-200 hidden sm:inline ${isOpen ? 'rotate-180' : ''}`} />
      </button>

      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0, y: 8, scale: 0.95 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 8, scale: 0.95 }}
            transition={{ duration: 0.15 }}
            className="absolute right-0 rtl:left-0 rtl:right-auto mt-2 w-40 rounded-[8px] bg-surface border border-border shadow-lg z-50 overflow-hidden"
            style={{ boxShadow: 'var(--shadow)' }}
          >
            <div className="py-1">
              {locales.map((loc) => (
                <button
                  key={loc.code}
                  onClick={() => {
                    setLocale(loc.code);
                    setIsOpen(false);
                  }}
                  className={`flex items-center justify-between w-full px-4 py-2.5 text-xs font-bold text-left rtl:text-right hover:bg-surface-2 transition-colors ${
                    locale === loc.code ? 'text-green font-extrabold bg-green/5' : 'text-text-primary'
                  }`}
                >
                  <span className="flex items-center space-x-2 rtl:space-x-reverse">
                    <span>{loc.flag}</span>
                    <span>{loc.native}</span>
                  </span>
                  {locale === loc.code && (
                    <span className="w-1.5 h-1.5 bg-green rounded-full"></span>
                  )}
                </button>
              ))}
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};
