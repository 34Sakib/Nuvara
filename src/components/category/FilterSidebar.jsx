import React from 'react';
import { useTranslation } from 'react-i18next';
import { X, Star } from 'lucide-react';

export const FilterSidebar = ({
  filters,
  onChange,
  allBrands = [],
  maxPrice = 500,
  onCloseMobile // Callback to close drawer on mobile
}) => {
  const { t } = useTranslation();

  const handleBrandChange = (brand) => {
    const activeBrands = [...filters.brands];
    const index = activeBrands.indexOf(brand);
    
    if (index > -1) {
      activeBrands.splice(index, 1);
    } else {
      activeBrands.push(brand);
    }
    onChange({ ...filters, brands: activeBrands });
  };

  const handlePriceChange = (e) => {
    const val = parseFloat(e.target.value);
    onChange({ ...filters, maxPrice: val });
  };

  const handleRatingChange = (ratingVal) => {
    // toggle rating
    const nextRating = filters.rating === ratingVal ? null : ratingVal;
    onChange({ ...filters, rating: nextRating });
  };

  const handleClear = () => {
    onChange({
      brands: [],
      maxPrice: maxPrice,
      rating: null,
      search: ''
    });
  };

  return (
    <div className="w-full bg-bg-secondary border border-border rounded-2xl p-6 transition-colors shadow-sm">
      <div className="flex justify-between items-center mb-6 border-b border-border pb-4">
        <h3 className="text-base font-bold text-text-primary uppercase tracking-wider">
          {t('category.filters')}
        </h3>
        
        {onCloseMobile && (
          <button 
            onClick={onCloseMobile} 
            className="lg:hidden p-1 text-text-secondary hover:text-text-primary"
            aria-label="Close filters"
          >
            <X className="w-5 h-5" />
          </button>
        )}

        <button
          onClick={handleClear}
          className="text-xs font-semibold text-accent hover:underline focus:outline-none"
        >
          {t('category.clear_filters')}
        </button>
      </div>

      {/* Price Slider */}
      <div className="mb-8">
        <h4 className="text-sm font-bold text-text-primary mb-3">
          {t('category.price_range')}
        </h4>
        <input
          type="range"
          min="0"
          max={maxPrice}
          step="10"
          value={filters.maxPrice}
          onChange={handlePriceChange}
          className="w-full h-1.5 bg-border rounded-lg appearance-none cursor-pointer accent-accent"
        />
        <div className="flex justify-between items-center mt-2 text-xs font-bold text-text-secondary">
          <span>$0</span>
          <span className="text-accent">${filters.maxPrice}</span>
        </div>
      </div>

      {/* Brands Checkboxes */}
      <div className="mb-8">
        <h4 className="text-sm font-bold text-text-primary mb-3">
          {t('category.brands')}
        </h4>
        <div className="space-y-2">
          {allBrands.map((brand) => {
            const isChecked = filters.brands.includes(brand);
            return (
              <label key={brand} className="flex items-center space-x-2.5 rtl:space-x-reverse text-sm text-text-primary cursor-pointer hover:text-accent transition-colors">
                <input
                  type="checkbox"
                  checked={isChecked}
                  onChange={() => handleBrandChange(brand)}
                  className="rounded border-border text-accent focus:ring-accent/50 w-4 h-4"
                />
                <span>{brand}</span>
              </label>
            );
          })}
        </div>
      </div>

      {/* Rating Filters */}
      <div>
        <h4 className="text-sm font-bold text-text-primary mb-3">
          {t('category.rating')}
        </h4>
        <div className="space-y-2">
          {[5, 4, 3].map((stars) => {
            const isSelected = filters.rating === stars;
            return (
              <button
                key={stars}
                onClick={() => handleRatingChange(stars)}
                className={`
                  flex items-center space-x-2 rtl:space-x-reverse text-sm w-full p-2 rounded-lg border text-left rtl:text-right hover:border-accent transition-all
                  ${isSelected ? 'border-accent bg-accent/5 font-semibold text-accent' : 'border-border text-text-primary'}
                `}
              >
                <span className="flex items-center text-amber-400">
                  {Array.from({ length: 5 }).map((_, i) => (
                    <Star 
                      key={i} 
                      className={`w-3.5 h-3.5 ${i < stars ? 'fill-current' : 'text-gray-300 dark:text-gray-600'}`} 
                    />
                  ))}
                </span>
                <span className="text-xs text-text-secondary">
                  {stars === 5 ? '' : '& Up'}
                </span>
              </button>
            );
          })}
        </div>
      </div>
    </div>
  );
};
