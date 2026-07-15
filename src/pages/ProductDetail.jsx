import React, { useState, useEffect } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { 
  Heart, ShoppingCart, ShieldCheck, RefreshCw, 
  Truck, ArrowLeft, ChevronRight 
} from 'lucide-react';
import { mockProducts, mockCategories, getLocalized } from '../utils/mockData';
import { useLocaleStore } from '../store/localeStore';
import { useCartStore } from '../store/cartStore';
import { useToastStore } from '../store/toastStore';
import { RatingStars } from '../components/ui/RatingStars';
import { Button } from '../components/ui/Button';
import { QuantityStepper } from '../components/ui/QuantityStepper';
import { VariantSwatch } from '../components/product/VariantSwatch';
import { ImageGallery } from '../components/product/ImageGallery';
import { ProductTabs } from '../components/product/ProductTabs';
import { ProductCard } from '../components/product/ProductCard';
import { SectionDivider } from '../components/ui/SectionDivider';

export const ProductDetail = () => {
  const { t } = useTranslation();
  const { slug } = useParams();
  const navigate = useNavigate();
  const { locale } = useLocaleStore();
  
  const { addToCart, toggleWishlist, isInWishlist } = useCartStore();
  const { addToast } = useToastStore();

  const product = mockProducts.find((p) => p.slug === slug);

  // If product not found, show error or fallback
  if (!product) {
    return (
      <div className="max-w-7xl mx-auto px-4 py-20 text-center">
        <h2 className="text-2xl font-black text-text-primary mb-4">Product Not Found</h2>
        <Link to="/category/all">
          <Button variant="primary">Back to Shop</Button>
        </Link>
      </div>
    );
  }

  // Variant States
  const [selectedColor, setSelectedColor] = useState(
    product.variants?.colors?.[0]?.name || ''
  );
  const [selectedSize, setSelectedSize] = useState(
    product.variants?.sizes?.[0] || ''
  );
  const [quantity, setQuantity] = useState(1);
  const [isStickyBarVisible, setIsStickyBarVisible] = useState(false);

  useEffect(() => {
    // Reset variants when product changes
    setSelectedColor(product.variants?.colors?.[0]?.name || '');
    setSelectedSize(product.variants?.sizes?.[0] || '');
    setQuantity(1);
  }, [slug]);

  // Monitor scroll for sticky mobile bar
  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 400) {
        setIsStickyBarVisible(true);
      } else {
        setIsStickyBarVisible(false);
      }
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const isLiked = isInWishlist(product.id);
  const discountPercent = product.compare_price 
    ? Math.round(((product.compare_price - product.price) / product.compare_price) * 100)
    : 0;

  const handleAddToCart = () => {
    const variant = {};
    if (selectedColor) variant.color = selectedColor;
    if (selectedSize) variant.size = selectedSize;

    addToCart(product, variant, quantity);
    addToast(`${getLocalized(product.name, locale)} ${t('product.cart_added')}`, 'success');
  };

  const handleBuyNow = () => {
    const variant = {};
    if (selectedColor) variant.color = selectedColor;
    if (selectedSize) variant.size = selectedSize;

    addToCart(product, variant, quantity);
    navigate('/cart');
  };

  const handleWishlistToggle = () => {
    const added = toggleWishlist(product);
    addToast(
      added ? t('product.wishlist_added') : t('product.wishlist_removed'),
      'info'
    );
  };

  // Find related products (same category, excluding current product)
  const relatedProducts = mockProducts
    .filter((p) => p.category_id === product.category_id && p.id !== product.id)
    .slice(0, 4);

  const activeCategory = mockCategories.find((c) => c.id === product.category_id);

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in relative">
      
      {/* Breadcrumbs */}
      <nav className="flex items-center space-x-2 rtl:space-x-reverse text-xs font-semibold text-text-secondary mb-8">
        <Link to="/" className="hover:text-accent">Home</Link>
        <ChevronRight className="w-3.5 h-3.5 rtl-flip" />
        {activeCategory && (
          <>
            <Link to={`/category/${activeCategory.slug}`} className="hover:text-accent">
              {getLocalized(activeCategory.name, locale)}
            </Link>
            <ChevronRight className="w-3.5 h-3.5 rtl-flip" />
          </>
        )}
        <span className="text-text-primary font-bold">{getLocalized(product.name, locale)}</span>
      </nav>

      {/* Main Details Section */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start mb-16">
        {/* Left Side: Gallery */}
        <div>
          <ImageGallery images={product.images} />
        </div>

        {/* Right Side: Product Details */}
        <div className="space-y-6 text-left rtl:text-right">
          <div>
            <span className="text-xs uppercase font-extrabold tracking-widest text-brass bg-brass/5 border border-brass/15 px-3 py-1.5 rounded-full">
              {product.brand}
            </span>
            <h1 className="text-2xl md:text-3xl font-serif font-medium text-text-primary mt-4 leading-tight">
              {getLocalized(product.name, locale)}
            </h1>
            
            {/* Rating summary */}
            <div className="flex items-center space-x-3 rtl:space-x-reverse mt-3">
              <RatingStars value={product.rating} size="md" />
              <span className="text-xs text-text-secondary font-bold hover:underline cursor-pointer">
                {product.reviewCount} Ratings
              </span>
            </div>
          </div>

          {/* Pricing Block */}
          <div className="p-5 rounded-2xl bg-bg-secondary border border-border transition-colors">
            <div className="flex items-center space-x-3.5 rtl:space-x-reverse">
              <span className="text-3xl font-bold font-display text-green-soft dark:text-brass-bright">${product.price}</span>
              {product.compare_price && (
                <>
                  <span className="text-sm text-text-secondary line-through">${product.compare_price}</span>
                  <span className="text-xs font-bold text-white bg-wine px-2 py-0.5 rounded-md">
                    Save {discountPercent}%
                  </span>
                </>
              )}
            </div>
            
            <div className="mt-3 text-xs font-bold">
              {product.stock > 5 ? (
                <span className="text-green-600 dark:text-green-400">{t('product.in_stock')}</span>
              ) : product.stock > 0 ? (
                <span className="text-warning">{t('product.low_stock', { count: product.stock })}</span>
              ) : (
                <span className="text-danger">{t('product.out_of_stock')}</span>
              )}
            </div>
          </div>

          {/* Variant Swatches */}
          {product.variants?.colors?.length > 0 && (
            <div>
              <h4 className="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2.5">
                Select Color: <span className="text-text-primary font-extrabold">{selectedColor}</span>
              </h4>
              <VariantSwatch
                type="color"
                options={product.variants.colors}
                selected={selectedColor}
                onChange={setSelectedColor}
              />
            </div>
          )}

          {product.variants?.sizes?.length > 0 && (
            <div>
              <h4 className="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2.5">
                Select Size: <span className="text-text-primary font-extrabold">{selectedSize}</span>
              </h4>
              <VariantSwatch
                type="size"
                options={product.variants.sizes}
                selected={selectedSize}
                onChange={setSelectedSize}
              />
            </div>
          )}

          {/* Action Row: Stepper and Add/Buy buttons */}
          {product.stock > 0 && (
            <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 border-t border-b border-border py-6">
              <div>
                <h4 className="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2.5 hidden sm:block">QTY</h4>
                <QuantityStepper
                  value={quantity}
                  min={1}
                  max={product.stock}
                  onChange={setQuantity}
                />
              </div>

              <div className="flex-1 flex gap-3">
                <Button
                  variant="primary"
                  onClick={handleAddToCart}
                  className="flex-1 py-3 text-sm font-extrabold uppercase tracking-wide"
                  icon={ShoppingCart}
                >
                  {t('product.add_to_cart')}
                </Button>
                
                <Button
                  variant="secondary"
                  onClick={handleBuyNow}
                  className="flex-1 py-3 text-sm font-extrabold uppercase tracking-wide bg-surface border border-text-primary text-text-primary hover:bg-text-primary/5"
                >
                  {t('product.buy_now')}
                </Button>
                
                <Button
                  variant="ghost"
                  onClick={handleWishlistToggle}
                  className={`px-3 border border-border rounded-xl ${isLiked ? 'text-red-500 bg-red-50 dark:bg-red-950/20' : ''}`}
                  aria-label="Toggle wishlist"
                >
                  <Heart className={`w-5 h-5 ${isLiked ? 'fill-red-500' : ''}`} />
                </Button>
              </div>
            </div>
          )}

          {/* Trust Badges */}
          <div className="grid grid-cols-3 gap-4 pt-2 text-center text-xs text-text-secondary font-bold">
            <div className="flex flex-col items-center">
              <ShieldCheck className="w-5 h-5 text-brass mb-1.5" />
              <span>Secure Checkout</span>
            </div>
            <div className="flex flex-col items-center">
              <RefreshCw className="w-5 h-5 text-brass mb-1.5" />
              <span>30-Day Returns</span>
            </div>
            <div className="flex flex-col items-center">
              <Truck className="w-5 h-5 text-brass mb-1.5" />
              <span>Fast Delivery</span>
            </div>
          </div>

        </div>
      </div>

      {/* Tabs Section */}
      <div className="mb-16">
        <ProductTabs product={product} />
      </div>

      {/* Related Products Section */}
      {relatedProducts.length > 0 && (
        <div>
          <div className="text-center mb-6">
            <h2 className="text-2xl font-bold font-display text-text-primary uppercase tracking-wide">
              {t('product.related_products')}
            </h2>
            <SectionDivider />
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {relatedProducts.map((prod) => (
              <ProductCard key={prod.id} product={prod} />
            ))}
          </div>
        </div>
      )}

      {/* Sticky Mobile Bar (pinned to bottom while scrolling) */}
      {product.stock > 0 && isStickyBarVisible && (
        <div className="fixed bottom-0 left-0 right-0 z-30 lg:hidden bg-bg-secondary/95 backdrop-blur border-t border-border p-4 flex items-center justify-between shadow-2xl animate-slide-up">
          <div className="text-left rtl:text-right">
            <div className="text-xs text-text-secondary font-semibold line-clamp-1">{getLocalized(product.name, locale)}</div>
            <div className="text-base font-bold font-display text-green-soft dark:text-brass-bright">${product.price}</div>
          </div>
          <Button
            variant="primary"
            size="sm"
            onClick={handleAddToCart}
            className="px-6 py-2.5 font-bold uppercase text-xs"
            icon={ShoppingCart}
          >
            {t('product.add_to_cart')}
          </Button>
        </div>
      )}

    </div>
  );
};
