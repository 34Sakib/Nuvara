import React, { useState, useEffect } from 'react';
import { useParams, useSearchParams, Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { Filter, SlidersHorizontal, Grid3X3, ArrowUpDown, ChevronRight } from 'lucide-react';
import { mockCategories, mockProducts, getLocalized } from '../utils/mockData';
import { useLocaleStore } from '../store/localeStore';
import { ProductCard } from '../components/product/ProductCard';
import { FilterSidebar } from '../components/category/FilterSidebar';
import { Button } from '../components/ui/Button';
import { SectionDivider } from '../components/ui/SectionDivider';

export const Category = () => {
  const { t } = useTranslation();
  const { slug } = useParams();
  const [searchParams] = useSearchParams();
  const { locale } = useLocaleStore();

  const searchQuery = searchParams.get('search') || '';

  // Get active category
  const activeCategory = mockCategories.find((c) => c.slug === slug);
  const isAll = slug === 'all' || !activeCategory;

  // Max price for filters
  const maxCategoryPrice = 500;

  // Filter States
  const [filters, setFilters] = useState({
    brands: [],
    maxPrice: maxCategoryPrice,
    rating: null,
    search: searchQuery
  });

  const [sortBy, setSortBy] = useState('popularity');
  const [showMobileFilters, setShowMobileFilters] = useState(false);
  const [visibleCount, setVisibleCount] = useState(4); // pagination simulation

  // Reset filters when category changes
  useEffect(() => {
    setFilters({
      brands: [],
      maxPrice: maxCategoryPrice,
      rating: null,
      search: searchQuery
    });
    setVisibleCount(4);
  }, [slug, searchQuery]);

  // Find all brands in this specific category for filters
  const categoryProducts = mockProducts.filter((p) => {
    if (isAll) return true;
    return p.category_id === activeCategory.id;
  });

  const allBrands = Array.from(new Set(categoryProducts.map((p) => p.brand)));

  // Filter Products
  const filteredProducts = categoryProducts.filter((product) => {
    // Brand filter
    if (filters.brands.length > 0 && !filters.brands.includes(product.brand)) {
      return false;
    }
    // Price filter
    if (product.price > filters.maxPrice) {
      return false;
    }
    // Rating filter
    if (filters.rating && product.rating < filters.rating) {
      return false;
    }
    // Search query
    if (filters.search) {
      const name = getLocalized(product.name, locale).toLowerCase();
      const brand = product.brand.toLowerCase();
      const q = filters.search.toLowerCase();
      if (!name.includes(q) && !brand.includes(q)) {
        return false;
      }
    }
    return true;
  });

  // Sort Products
  const sortedProducts = [...filteredProducts].sort((a, b) => {
    if (sortBy === 'popularity') {
      return b.reviewCount - a.reviewCount; // mock popularity by review count
    } else if (sortBy === 'price_asc') {
      return a.price - b.price;
    } else if (sortBy === 'price_desc') {
      return b.price - a.price;
    } else if (sortBy === 'newest') {
      return b.isNew ? 1 : -1;
    } else if (sortBy === 'rating') {
      return b.rating - a.rating;
    }
    return 0;
  });

  const paginatedProducts = sortedProducts.slice(0, visibleCount);

  const categoryTitle = isAll 
    ? (searchQuery ? `Search: "${searchQuery}"` : 'All Products')
    : getLocalized(activeCategory.name, locale);

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
      
      {/* Breadcrumbs */}
      <nav className="flex items-center space-x-2 rtl:space-x-reverse text-xs font-semibold text-text-secondary mb-6">
        <Link to="/" className="hover:text-accent">Home</Link>
        <ChevronRight className="w-3.5 h-3.5 rtl-flip" />
        <Link to="/category/all" className="hover:text-accent">Shop</Link>
        <ChevronRight className="w-3.5 h-3.5 rtl-flip" />
        <span className="text-text-primary font-bold">{categoryTitle}</span>
      </nav>

      {/* Category Header Title with Signature Divider */}
      <div className="text-center mb-8">
        <h1 className="text-3xl font-bold font-display text-text-primary uppercase tracking-wide">
          {categoryTitle}
        </h1>
        <SectionDivider />
      </div>

      {/* Main Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {/* Left Filters - Desktop */}
        <aside className="hidden lg:block lg:col-span-1">
          <FilterSidebar
            filters={filters}
            onChange={setFilters}
            allBrands={allBrands}
            maxPrice={maxCategoryPrice}
          />
        </aside>

        {/* Right Product Listings */}
        <main className="lg:col-span-3">
          {/* Toolbar */}
          <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-bg-secondary border border-border p-4 rounded-2xl mb-6 gap-4 shadow-sm transition-colors">
            <div className="text-sm font-bold text-text-secondary">
              {t('category.results_count', { count: filteredProducts.length })}
            </div>

            <div className="flex items-center space-x-3 rtl:space-x-reverse w-full sm:w-auto justify-between sm:justify-end">
              {/* Mobile Filter Toggle */}
              <Button
                variant="secondary"
                size="sm"
                className="lg:hidden"
                onClick={() => setShowMobileFilters(true)}
                icon={SlidersHorizontal}
              >
                Filters
              </Button>

              {/* Sort selector */}
              <div className="flex items-center space-x-2 rtl:space-x-reverse">
                <ArrowUpDown className="w-4 h-4 text-text-secondary" />
                <span className="text-xs font-bold text-text-secondary hidden md:inline">
                  {t('category.sort_by')}
                </span>
                <select
                  value={sortBy}
                  onChange={(e) => setSortBy(e.target.value)}
                  className="bg-bg-primary text-text-primary border border-border rounded-lg text-xs font-bold px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-accent/50"
                >
                  <option value="popularity">{t('category.sort_popularity')}</option>
                  <option value="price_asc">{t('category.sort_price_asc')}</option>
                  <option value="price_desc">{t('category.sort_price_desc')}</option>
                  <option value="newest">{t('category.sort_newest')}</option>
                  <option value="rating">{t('category.sort_rating')}</option>
                </select>
              </div>
            </div>
          </div>

          {/* Product Grid */}
          {paginatedProducts.length > 0 ? (
            <div className="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-6">
              {paginatedProducts.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          ) : (
            <div className="text-center py-20 bg-bg-secondary border border-border rounded-3xl p-8">
              <div className="text-5xl mb-4">🔍</div>
              <h3 className="text-lg font-bold text-text-primary mb-2">
                {t('category.no_results')}
              </h3>
              <button
                onClick={() => setFilters({ brands: [], maxPrice: maxCategoryPrice, rating: null, search: '' })}
                className="mt-2 text-xs font-bold text-accent hover:underline"
              >
                {t('category.clear_filters')}
              </button>
            </div>
          )}

          {/* Load More Button */}
          {visibleCount < filteredProducts.length && (
            <div className="flex justify-center mt-12">
              <Button
                variant="secondary"
                onClick={() => setVisibleCount((prev) => prev + 4)}
              >
                {t('category.load_more')}
              </Button>
            </div>
          )}
        </main>
      </div>

      {/* Mobile Filters Drawer */}
      {showMobileFilters && (
        <div className="fixed inset-0 z-50 lg:hidden flex">
          {/* Overlay */}
          <div 
            onClick={() => setShowMobileFilters(false)}
            className="fixed inset-0 bg-black/50 backdrop-blur-sm"
          ></div>
          
          {/* Drawer Body */}
          <div className="relative max-w-xs w-full bg-bg-secondary border-r rtl:border-l rtl:border-r-0 border-border z-10 p-4 overflow-y-auto animate-slide-in">
            <FilterSidebar
              filters={filters}
              onChange={setFilters}
              allBrands={allBrands}
              maxPrice={maxCategoryPrice}
              onCloseMobile={() => setShowMobileFilters(false)}
            />
          </div>
        </div>
      )}

    </div>
  );
};
