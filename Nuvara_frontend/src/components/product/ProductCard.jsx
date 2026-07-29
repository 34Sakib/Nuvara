import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { motion } from 'framer-motion';
import { Heart, ShoppingBag, Sparkles } from 'lucide-react';
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

  if (!product) return null;

  const isLiked = isInWishlist(product.id);
  const discountPercent = product.compare_price 
    ? Math.round(((product.compare_price - product.price) / product.compare_price) * 100)
    : 0;

  let mainImage = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80';
  if (Array.isArray(product.images) && product.images.length > 0) {
    mainImage = typeof product.images[0] === 'object' ? (product.images[0].path || product.images[0].url || mainImage) : product.images[0];
  } else if (typeof product.images === 'string' && product.images.trim().length > 0) {
    mainImage = product.images.split(' ')[0];
  } else if (product.image) {
    mainImage = product.image;
  }

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
    <motion.div
      whileHover={{ y: -5, scale: 1.015 }}
      whileTap={{ scale: 0.985 }}
      transition={{ duration: 0.25, ease: 'easeOut' }}
      onClick={handleCardClick}
      className="group relative flex flex-col bg-surface border border-border/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer hover:border-accent/40"
    >
      {/* Top Floating Badges */}
      <div className="absolute top-2.5 left-2.5 rtl:right-2.5 rtl:left-auto z-10 flex flex-col space-y-1">
        {discountPercent > 0 && (
          <span className="bg-wine text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-md backdrop-blur-sm">
            -{discountPercent}%
          </span>
        )}
        {(product.isNew || product.is_new) && (
          <span className="bg-emerald-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-md backdrop-blur-sm flex items-center gap-1">
            <Sparkles className="w-2.5 h-2.5" />
            <span>NEW</span>
          </span>
        )}
      </div>

      {/* Wishlist Button */}
      <button
        onClick={handleWishlistClick}
        className="absolute top-2.5 right-2.5 rtl:left-2.5 rtl:right-auto z-10 p-1.5 rounded-full bg-surface/90 hover:bg-surface border border-border/60 shadow-md hover:scale-110 active:scale-90 transition-all duration-200 text-text-primary"
        aria-label="Toggle wishlist"
      >
        <Heart 
          className={`w-3.5 h-3.5 transition-colors ${isLiked ? 'fill-wine text-wine' : 'text-text-secondary hover:text-wine'}`} 
        />
      </button>

      {/* Product Image Wrapper - Compact Height & Uncropped Full Visibility */}
      <div className="relative h-36 sm:h-44 w-full p-2 bg-surface-2/40 flex items-center justify-center overflow-hidden">
        <img
          src={mainImage}
          alt={getLocalized(product.name, locale)}
          className="w-full h-full object-contain group-hover:scale-108 transition-transform duration-500 ease-out"
          loading="lazy"
        />
        <div className="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" />
      </div>

      {/* Body Details - Compact spacing */}
      <div className="p-3.5 flex-1 flex flex-col justify-between text-left rtl:text-right">
        <div>
          <span className="text-[9px] text-brass uppercase tracking-widest font-extrabold block">
            {typeof product.brand === 'object' ? product.brand.name : (product.brand || 'Nuvara')}
          </span>
          
          <h3 className="text-xs sm:text-sm font-bold text-text-primary mt-0.5 line-clamp-1 group-hover:text-accent transition-colors">
            {getLocalized(product.name, locale)}
          </h3>

          <div className="mt-1 flex items-center">
            <RatingStars value={product.rating || product.avg_rating || 5} count={product.reviewCount || product.review_count || 0} size="xs" />
          </div>
        </div>

        {/* Pricing Block & Add To Cart Button */}
        <div className="mt-3 space-y-2.5">
          <div className="flex items-baseline justify-between space-x-1 rtl:space-x-reverse">
            <div className="flex items-baseline space-x-1.5 rtl:space-x-reverse">
              <span className="text-sm sm:text-base font-extrabold font-display text-green-soft dark:text-brass-bright">
                ${product.price}
              </span>
              {product.compare_price && (
                <span className="text-[11px] text-text-secondary line-through font-sans">
                  ${product.compare_price}
                </span>
              )}
            </div>
            {product.stock === 0 && (
              <span className="text-[9px] text-wine font-extrabold uppercase">
                {t('product.out_of_stock')}
              </span>
            )}
          </div>

          {/* Action Button */}
          {product.stock > 0 && (
            <button
              onClick={handleQuickAdd}
              className="w-full py-1.5 px-3 border border-border/90 text-text-primary text-xs font-bold rounded-xl bg-surface-2/80 hover:bg-accent hover:border-accent hover:text-white transition-all duration-200 flex items-center justify-center space-x-1.5 rtl:space-x-reverse shadow-2xs group-hover:shadow-sm"
            >
              <ShoppingBag className="w-3.5 h-3.5" />
              <span className="text-[11px] uppercase tracking-wider">{t('product.add_to_cart')}</span>
            </button>
          )}
        </div>
      </div>
    </motion.div>
  );
};
