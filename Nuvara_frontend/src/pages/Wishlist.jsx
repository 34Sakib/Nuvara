import React from 'react';
import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { Heart, ShoppingBag, Trash2, ArrowLeft } from 'lucide-react';
import { useCartStore } from '../store/cartStore';
import { useLocaleStore } from '../store/localeStore';
import { useToastStore } from '../store/toastStore';
import { useAuthStore } from '../store/authStore';
import { getLocalized } from '../utils/mockData';
import { Button } from '../components/ui/Button';

export const Wishlist = () => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();
  const { addToast } = useToastStore();
  const { wishlist, toggleWishlist, addToCart } = useCartStore();
  const { user } = useAuthStore();

  const activeWishlist = wishlist;

  const handleWishlistAddCart = (prod) => {
    addToCart(prod, {}, 1);
    addToast(`${getLocalized(prod.name, locale)} ${t('product.cart_added')}`, 'success');
  };

  const handleWishlistRemove = (prod) => {
    toggleWishlist(prod);
    addToast(t('product.wishlist_removed'), 'info');
  };

  if (!activeWishlist || activeWishlist.length === 0) {
    return (
      <div className="max-w-md mx-auto px-4 py-24 text-center animate-fade-in">
        <div className="w-20 h-20 bg-accent/5 rounded-full flex items-center justify-center mx-auto mb-6 text-accent">
          <Heart className="w-10 h-10" />
        </div>
        <h2 className="text-2xl font-black text-text-primary mb-2">
          Your Wishlist is Empty
        </h2>
        <p className="text-sm text-text-secondary mb-8">
          Explore our collection and add your favorite items to your wishlist.
        </p>
        <Link to="/category/all">
          <Button variant="primary" icon={ArrowLeft} className="rtl-flip">
            {t('cart.continue_shopping')}
          </Button>
        </Link>
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
      <div className="flex justify-between items-center mb-8 border-b border-border pb-4">
        <div>
          <h1 className="text-2xl font-black text-text-primary uppercase tracking-wide text-left rtl:text-right">
            {t('dashboard.wishlist')}
          </h1>
          <p className="text-xs text-text-secondary mt-1 text-left rtl:text-right">
            {activeWishlist.length} {activeWishlist.length === 1 ? 'item' : 'items'} saved in your wishlist
          </p>
        </div>
        <Link to="/category/all">
          <Button variant="secondary" size="sm" icon={ArrowLeft} className="rtl-flip">
            {t('cart.continue_shopping')}
          </Button>
        </Link>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        {activeWishlist.map((prod) => (
          <div 
            key={prod.id} 
            className="border border-border rounded-2xl overflow-hidden bg-bg-secondary flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow"
          >
            <div>
              <div className="relative aspect-square overflow-hidden bg-bg-primary">
                <img 
                  src={prod.images?.[0] || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'} 
                  alt={getLocalized(prod.name, locale)} 
                  className="w-full h-full object-cover"
                />
                <button
                  onClick={() => handleWishlistRemove(prod)}
                  className="absolute top-3 right-3 rtl:left-3 rtl:right-auto p-2 rounded-full bg-black/40 text-white hover:bg-red-500 transition-colors backdrop-blur-sm"
                  aria-label="Remove from wishlist"
                  title="Remove"
                >
                  <Trash2 className="w-4 h-4" />
                </button>
              </div>

              <div className="p-4 text-left rtl:text-right">
                <span className="text-[10px] text-brass font-bold uppercase tracking-wider">
                  {prod.brand}
                </span>
                <Link to={`/product/${prod.slug}`}>
                  <h3 className="font-bold text-sm text-text-primary hover:text-accent transition-colors line-clamp-1 mt-0.5">
                    {getLocalized(prod.name, locale)}
                  </h3>
                </Link>
                <div className="mt-2 text-base font-extrabold text-green-soft dark:text-brass-bright font-display">
                  ${prod.price}
                </div>
              </div>
            </div>

            <div className="p-4 pt-0">
              <Button
                variant="primary"
                size="sm"
                className="w-full justify-center flex items-center gap-2"
                onClick={() => handleWishlistAddCart(prod)}
              >
                <ShoppingBag className="w-4 h-4" />
                <span>{t('product.add_to_cart')}</span>
              </Button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};
