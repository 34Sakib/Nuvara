import React, { useEffect } from 'react';
import { HashRouter as Router, Routes, Route, Navigate, useLocation } from 'react-router-dom';
import { useLocaleStore } from './store/localeStore';
import { useThemeStore } from './store/themeStore';
import { Header } from './components/layout/Header';
import { Footer } from './components/layout/Footer';
import { ToastContainer } from './components/ui/Toast';

// Pages
import { Home } from './pages/Home';
import { Category } from './pages/Category';
import { ProductDetail } from './pages/ProductDetail';
import { Cart } from './pages/Cart';
import { Checkout } from './pages/Checkout';
import { OrderSuccess } from './pages/OrderSuccess';
import { Dashboard } from './pages/Dashboard';
import { Auth } from './pages/Auth';
import { About, Contact, FAQ } from './pages/StaticPages';

// Scroll to top on page navigation
function ScrollToTop() {
  const { pathname } = useLocation();

  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);

  return null;
}

function App() {
  const { locale } = useLocaleStore();
  const { theme } = useThemeStore();

  // Apply initial states on load
  useEffect(() => {
    // Theme setup
    document.documentElement.setAttribute('data-theme', theme);

    // Dynamic favicon swap
    const favicon = document.querySelector('link[rel="icon"]');
    if (favicon) {
      favicon.setAttribute('href', theme === 'dark' ? '/favicon-dark.svg' : '/favicon-light.svg');
    }

    // Direction and language setup
    const isRtl = locale === 'ar';
    document.documentElement.dir = isRtl ? 'rtl' : 'ltr';
    document.documentElement.lang = locale;

    // Apply locale font-class on body
    const fontMap = {
      en: 'font-sans',
      es: 'font-sans',
      bn: 'font-bengali',
      ar: 'font-arabic'
    };
    if (document.body) {
      Object.values(fontMap).forEach(cls => document.body.classList.remove(cls));
      document.body.classList.add(fontMap[locale] || 'font-sans');
    }
  }, [locale, theme]);

  return (
    <Router>
      <ScrollToTop />
      <div className="flex flex-col min-h-screen transition-colors duration-200">
        <Header />
        
        {/* Main Content Area */}
        <main className="flex-grow">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/category/:slug" element={<Category />} />
            <Route path="/product/:slug" element={<ProductDetail />} />
            <Route path="/cart" element={<Cart />} />
            <Route path="/checkout" element={<Checkout />} />
            <Route path="/order/:id/success" element={<OrderSuccess />} />
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/auth" element={<Auth />} />
            <Route path="/about" element={<About />} />
            <Route path="/contact" element={<Contact />} />
            <Route path="/faq" element={<FAQ />} />
            {/* Fallback route */}
            <Route path="*" element={<Navigate to="/" replace />} />
          </Routes>
        </main>
        
        <Footer />
        
        {/* Toast Notifications */}
        <ToastContainer />
      </div>
    </Router>
  );
}

export default App;
