import React, { useState, useEffect, useRef } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { 
  Search, Heart, User, ShoppingBag, Menu, X, 
  ChevronDown, HelpCircle, PhoneCall 
} from 'lucide-react';
import { useCartStore } from '../../store/cartStore';
import { useLocaleStore } from '../../store/localeStore';
import { LanguageSwitcher } from '../ui/LanguageSwitcher';
import { ThemeToggle } from '../ui/ThemeToggle';
import { mockProducts, getLocalized, mockCategories } from '../../utils/mockData';

export const Header = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { locale } = useLocaleStore();
  const { getCartTotals, wishlist } = useCartStore();
  const { count: cartCount } = getCartTotals();

  const [showPromo, setShowPromo] = useState(true);
  const [isScrolled, setIsScrolled] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [suggestions, setSuggestions] = useState([]);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  
  const searchRef = useRef(null);

  // Monitor Scroll to trigger sticky shrinking
  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 40) {
        setIsScrolled(true);
      } else {
        setIsScrolled(false);
      }
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Suggestions search listener
  useEffect(() => {
    if (searchQuery.trim().length < 2) {
      setSuggestions([]);
      return;
    }
    const query = searchQuery.toLowerCase();
    const filtered = mockProducts.filter((product) => {
      const name = getLocalized(product.name, locale).toLowerCase();
      const brand = product.brand.toLowerCase();
      return name.includes(query) || brand.includes(query);
    }).slice(0, 4);
    setSuggestions(filtered);
  }, [searchQuery, locale]);

  // Click outside search listener to close suggestions
  useEffect(() => {
    const handleClickOutside = (e) => {
      if (searchRef.current && !searchRef.current.contains(e.target)) {
        setShowSuggestions(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      setShowSuggestions(false);
      navigate(`/category/all?search=${encodeURIComponent(searchQuery)}`);
    }
  };

  const handleSuggestionClick = (slug) => {
    setSearchQuery('');
    setShowSuggestions(false);
    navigate(`/product/${slug}`);
  };

  return (
    <header className="w-full z-40 transition-all duration-300">
      {/* Row 1: Thin Top Bar */}
      {showPromo && (
        <div className="bg-accent text-white px-4 py-2.5 text-xs flex justify-between items-center transition-colors relative">
          <div className="flex items-center space-x-4 rtl:space-x-reverse mx-auto md:mx-0 md:pl-0 pr-8 rtl:pr-0 rtl:pl-8 md:pr-0 select-none">
            <span className="font-semibold">{t('home.free_shipping_sub')}!</span>
            <span className="hidden md:inline-block text-white/40">|</span>
            <span className="hidden md:inline-flex items-center gap-1.5">
              <PhoneCall className="w-3.5 h-3.5" />
              +1 (800) NUVARA
            </span>
          </div>
          <div className="hidden md:flex items-center space-x-4 rtl:space-x-reverse pr-8 rtl:pr-0 rtl:pl-8">
            <Link to="/faq" className="hover:underline inline-flex items-center gap-1.5">
              <HelpCircle className="w-3.5 h-3.5" />
              {t('nav.faq')}
            </Link>
          </div>
          <button 
            onClick={() => setShowPromo(false)} 
            className="absolute right-3 rtl:left-3 rtl:right-auto text-white/80 hover:text-white focus:outline-none flex items-center justify-center p-1 rounded hover:bg-white/10"
            aria-label="Dismiss banner"
          >
            <X className="w-3.5 h-3.5" />
          </button>
        </div>
      )}

      {/* Main Header Area (Sticky) */}
      <div className={`
        w-full transition-all duration-300 bg-bg-secondary/95 backdrop-blur-md border-b border-border
        ${isScrolled ? 'fixed top-0 shadow-md py-2' : 'relative py-4'}
      `}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between gap-4">
            
            {/* Mobile Menu Icon */}
            <button 
              onClick={() => setIsMobileMenuOpen(true)}
              className="lg:hidden p-2 rounded-lg text-text-primary hover:bg-bg-primary"
              aria-label="Open menu"
            >
              <Menu className="w-6 h-6" />
            </button>

            {/* Logo */}
            <Link to="/" className="text-2xl font-black text-accent tracking-wider hover:opacity-90">
              NUVARA
            </Link>

            {/* Search Bar (Center, expands on focus) */}
            <div className="hidden lg:block flex-1 max-w-lg relative" ref={searchRef}>
              <form onSubmit={handleSearchSubmit} className="relative">
                <input
                  type="text"
                  placeholder={t('nav.search_placeholder')}
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  onFocus={() => setShowSuggestions(true)}
                  className="w-full pl-10 pr-4 rtl:pr-10 rtl:pl-4 py-2 rounded-full border border-border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-transparent transition-all duration-200"
                />
                <Search className="absolute left-3.5 rtl:right-3.5 rtl:left-auto top-2.5 w-4 h-4 text-text-secondary" />
              </form>

              {/* Live Search Suggestions Dropdown */}
              {showSuggestions && suggestions.length > 0 && (
                <div className="absolute top-full left-0 right-0 mt-2 rounded-[8px] bg-surface border border-border shadow-lg z-50 overflow-hidden" style={{ boxShadow: 'var(--shadow)' }}>
                  <div className="p-3 text-[10px] uppercase tracking-wider font-extrabold text-text-secondary border-b border-border bg-surface-2 select-none">
                    {t('nav.search_suggestions')}
                  </div>
                  <div className="py-1">
                    {suggestions.map((product) => (
                      <button
                        key={product.id}
                        onClick={() => handleSuggestionClick(product.slug)}
                        className="w-full flex items-center px-4 py-2.5 hover:bg-surface-2 text-start rtl:text-right transition-colors"
                      >
                        <img 
                          src={product.images[0]} 
                          alt="" 
                          className="w-8 h-8 rounded-[4px] object-cover mr-3 rtl:ml-3 rtl:mr-0 border border-border" 
                        />
                        <div>
                          <div className="text-sm font-semibold text-text-primary line-clamp-1">
                            {getLocalized(product.name, locale)}
                          </div>
                          <div className="text-xs text-text-secondary mt-0.5">
                            {product.brand} · ${product.price}
                          </div>
                        </div>
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Control Panel: Language, Theme, Wishlist, User, Cart */}
            <div className="flex items-center space-x-2 md:space-x-4 rtl:space-x-reverse">
              <LanguageSwitcher />
              
              <ThemeToggle />

              {/* Wishlist Link */}
              <Link 
                to="/dashboard?tab=wishlist" 
                className="relative p-2 text-text-primary hover:text-accent rounded-full hover:bg-bg-primary transition-colors hidden sm:block"
                aria-label="View wishlist"
              >
                <Heart className="w-5 h-5" />
                {wishlist.length > 0 && (
                  <span className="absolute -top-0.5 -right-0.5 bg-red-500 text-white font-bold text-[9px] w-4.5 h-4.5 rounded-full flex items-center justify-center">
                    {wishlist.length}
                  </span>
                )}
              </Link>

              {/* Account Link */}
              <Link 
                to="/dashboard" 
                className="p-2 text-text-primary hover:text-accent rounded-full hover:bg-bg-primary transition-colors"
                aria-label="View account"
              >
                <User className="w-5 h-5" />
              </Link>

              {/* Cart Link */}
              <Link 
                to="/cart" 
                className="relative p-2 text-text-primary hover:text-accent rounded-full hover:bg-bg-primary transition-colors"
                aria-label="View cart"
              >
                <ShoppingBag className="w-5 h-5" />
                {cartCount > 0 && (
                  <span className="absolute -top-0.5 -right-0.5 bg-accent text-white font-bold text-[9px] w-4.5 h-4.5 rounded-full flex items-center justify-center">
                    {cartCount}
                  </span>
                )}
              </Link>
            </div>
          </div>
        </div>

        {/* Row 3: Mega Menu Horizontal Nav (Desktop) */}
        <div className="hidden lg:block border-t border-border mt-3 pt-3">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav className="flex space-x-8 rtl:space-x-reverse text-sm font-semibold text-text-primary">
              <Link to="/" className="hover:text-accent transition-colors">{t('nav.home')}</Link>
              {mockCategories.map((cat) => (
                <Link 
                  key={cat.id} 
                  to={`/category/${cat.slug}`} 
                  className="hover:text-accent transition-colors"
                >
                  {getLocalized(cat.name, locale)}
                </Link>
              ))}
              <Link to="/about" className="hover:text-accent transition-colors">{t('nav.about')}</Link>
              <Link to="/contact" className="hover:text-accent transition-colors">{t('nav.contact')}</Link>
              <Link to="/faq" className="hover:text-accent transition-colors">{t('nav.faq')}</Link>
            </nav>
          </div>
        </div>
      </div>

      {/* Adjust scroll padding if fixed header is active */}
      {isScrolled && <div className="h-[73px]"></div>}

      {/* Mobile Drawer (Slide-out) */}
      {isMobileMenuOpen && (
        <div className="fixed inset-0 z-50 lg:hidden flex">
          {/* Overlay */}
          <div 
            onClick={() => setIsMobileMenuOpen(false)}
            className="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
          ></div>

          {/* Drawer Panel */}
          <div className="relative flex-1 flex flex-col max-w-xs w-full bg-bg-secondary border-r rtl:border-l rtl:border-r-0 border-border z-10 p-6 animate-slide-in">
            <div className="flex justify-between items-center mb-8">
              <span className="text-xl font-bold text-accent">NUVARA</span>
              <button 
                onClick={() => setIsMobileMenuOpen(false)}
                className="p-2 rounded-lg text-text-primary hover:bg-bg-primary"
                aria-label="Close menu"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            {/* Mobile Search */}
            <form onSubmit={handleSearchSubmit} className="relative mb-6">
              <input
                type="text"
                placeholder={t('nav.search_placeholder')}
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-9 pr-4 rtl:pr-9 rtl:pl-4 py-2 rounded-lg border border-border bg-bg-primary text-text-primary text-xs focus:outline-none focus:ring-2 focus:ring-accent/50"
              />
              <Search className="absolute left-3 rtl:right-3 rtl:left-auto top-2.5 w-3.5 h-3.5 text-text-secondary" />
            </form>

            {/* Mobile Nav Links */}
            <nav className="flex flex-col space-y-4 font-semibold text-sm text-text-primary">
              <Link 
                to="/" 
                onClick={() => setIsMobileMenuOpen(false)} 
                className="hover:text-accent transition-colors py-1.5 border-b border-border"
              >
                {t('nav.home')}
              </Link>
              {mockCategories.map((cat) => (
                <Link 
                  key={cat.id} 
                  to={`/category/${cat.slug}`} 
                  onClick={() => setIsMobileMenuOpen(false)}
                  className="hover:text-accent transition-colors py-1.5 border-b border-border"
                >
                  {getLocalized(cat.name, locale)}
                </Link>
              ))}
              <Link 
                to="/about" 
                onClick={() => setIsMobileMenuOpen(false)} 
                className="hover:text-accent transition-colors py-1.5 border-b border-border"
              >
                {t('nav.about')}
              </Link>
              <Link 
                to="/contact" 
                onClick={() => setIsMobileMenuOpen(false)} 
                className="hover:text-accent transition-colors py-1.5 border-b border-border"
              >
                {t('nav.contact')}
              </Link>
              <Link 
                to="/faq" 
                onClick={() => setIsMobileMenuOpen(false)} 
                className="hover:text-accent transition-colors py-1.5 border-b border-border"
              >
                {t('nav.faq')}
              </Link>
            </nav>
          </div>
        </div>
      )}
    </header>
  );
};
