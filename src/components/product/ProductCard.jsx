import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { Heart, ShoppingBag } from 'lucide-react';
import { useCartStore } from '../../store/cartStore';
import { useToastStore } from '../../store/toastStore';
import { useLocaleStore } from '../../store/localeStore';
import { RatingStars } from '../ui/RatingStars';
import { getLocalized } from '../../utils/mockData';

export const ProductCard = ({ product }) => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { locale } = useLocaleStore();
  const { addToCart, toggleWishlist, isInWishlist } = useCartStore();
  const { addToast } = useToastStore();

  const isLiked = isInWishlist(product.id);
  const discountPercent = product.compare_price 
    ? Math.round(((product.compare_price - product.price) / product.compare_price) * 100)
    : 0;

  const handleWishlistClick = (e) => {
    e.preventDefault();
    e.stopPropagation();
    const added = toggleWishlist(product);
    addToast(
      added ? t('product.wishlist_added') : t('product.wishlist_removed'),
      'info'
    );
  };

  const handleQuickAdd = (e) => {
    e.preventDefault();
    e.stopPropagation();
    
    // Choose default variant if any exist
    const selectedVariant = {};
    if (product.variants?.colors?.length > 0) {
      selectedVariant.color = product.variants.colors[0].name;
    }
    if (product.variants?.sizes?.length > 0) {
      selectedVariant.size = product.variants.sizes[0];
    }

    addToCart(product, selectedVariant, 1);
    addToast(`${getLocalized(product.name, locale)} ${t('product.cart_added')}`, 'success');
  };

  const handleCardClick = () => {
    navigate(`/product/${product.slug}`);
  };

  return (
    <div
      onClick={handleCardClick}
      className="group relative flex flex-col bg-surface border border-border rounded-[8px] overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer"
      style={{ boxShadow: 'var(--shadow)' }}
    >
      {/* Top Floating Badges */}
      <div className="absolute top-3 left-3 rtl:right-3 rtl:left-auto z-10 flex flex-col space-y-1">
        {discountPercent > 0 && (
          <span className="bg-wine text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
            -{discountPercent}%
          </span>
        )}
        {product.isNew && (
          <span className="bg-green text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
            NEW
          </span>
        )}
      </div>

      {/* Wishlist Icon - Circular & Semi-Transparent Background */}
      <button
        onClick={handleWishlistClick}
        className="absolute top-3 right-3 rtl:left-3 rtl:right-auto z-10 p-2 rounded-full bg-white/85 shadow-sm hover:scale-110 active:scale-95 transition-all text-[#211D1A] hover:text-wine"
        aria-label="Toggle wishlist"
      >
        <Heart 
          className={`w-3.5 h-3.5 transition-colors ${isLiked ? 'fill-wine text-wine' : 'text-[#211D1A]'}`} 
        />
      </button>

      {/* Product Image Wrapper - Square & Surface-2 placeholder */}
      <div className="relative aspect-square w-full overflow-hidden bg-surface-2">
        <img
          src={product.images[0]}
          alt={getLocalized(product.name, locale)}
          className="w-full h-full object-cover transition-transform duration-500"
          loading="lazy"
        />
      </div>

      {/* Body details */}
      <div className="p-4 flex-1 flex flex-col justify-between">
        <div>
          {/* Brand name - Small Caps */}
          <span className="text-[10px] text-text-secondary uppercase tracking-widest font-extrabold" style={{ fontVariant: 'small-caps' }}>
            {product.brand}
          </span>
          {/* Title - Inter 600 */}
          <h3 className="text-sm font-semibold font-sans text-text-primary mt-1 line-clamp-2 h-10 leading-tight">
            {getLocalized(product.name, locale)}
          </h3>
          <div className="mt-2">
            <RatingStars value={product.rating} count={product.reviewCount} size="sm" />
          </div>
        </div>

        {/* Pricing block */}
        <div className="mt-4 space-y-3">
          <div className="flex items-baseline space-x-1.5 rtl:space-x-reverse justify-between">
            <div className="flex items-baseline space-x-1.5 rtl:space-x-reverse">
              {/* Price - Fraunces Serif, Bold */}
              <span className="text-lg font-bold font-display text-green-soft dark:text-brass-bright">
                ${product.price}
              </span>
              {product.compare_price && (
                <span className="text-xs text-text-secondary line-through font-sans">
                  ${product.compare_price}
                </span>
              )}
            </div>
            {product.stock === 0 && (
              <span className="text-[10px] text-wine font-semibold uppercase">
                {t('product.out_of_stock')}
              </span>
            )}
          </div>

          {/* Add to Cart button - Outlined style, fills solid green on hover */}
          {product.stock > 0 && (
            <button
              onClick={handleQuickAdd}
              className="w-full py-2 border border-text-primary text-text-primary text-xs font-bold rounded-[4px] bg-transparent transition-all duration-300 hover:bg-green hover:border-green hover:text-[#F5EFE4] flex items-center justify-center space-x-1.5 rtl:space-x-reverse"
            >
              <ShoppingBag className="w-3.5 h-3.5" />
              <span>{t('product.add_to_cart')}</span>
            </button>
          )}
        </div>
      </div>
    </div>
  );
};
