import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import enTranslation from '../locales/en.json';
import esTranslation from '../locales/es.json';
import arTranslation from '../locales/ar.json';
import bnTranslation from '../locales/bn.json';

const resources = {
  en: { translation: enTranslation },
  es: { translation: esTranslation },
  ar: { translation: arTranslation },
  bn: { translation: bnTranslation }
};

const getStoredLocale = () => {
  if (typeof window !== 'undefined') {
    return localStorage.getItem('app_locale') || 'en';
  }
  return 'en';
};

i18n
  .use(initReactI18next)
  .init({
    resources,
    lng: getStoredLocale(),
    fallbackLng: 'en',
    interpolation: {
      escapeValue: false // react already safes from xss
    }
  });

export default i18n;
