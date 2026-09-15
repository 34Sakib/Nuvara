import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { motion, useReducedMotion } from 'framer-motion';
import { Heart, Plus, Check } from 'lucide-react';
import { useCartStore } from '../../store/cartStore';
import { useToastStore } from '../../store/toastStore';
import { useLocaleStore } from '../../store/localeStore';
import { getLocalized } from '../../utils/mockData';
import './ProductCard.css';

const imageUrl = image => typeof image === 'object' ? image?.path || image?.url : image;
export const ProductCard = ({ product }) => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();
  const reduce = useReducedMotion();
  const { addToCart, toggleWishlist, isInWishlist } = useCartStore();
  const { addToast } = useToastStore();
  const [added, setAdded] = useState(false);
  const [showOptions, setShowOptions] = useState(false);
  const [selectedColor, setSelectedColor] = useState(product.variants?.colors?.[0]?.name || '');
  const [selectedSize, setSelectedSize] = useState(product.variants?.sizes?.[0] || '');
  const timer = useRef();
  useEffect(() => () => clearTimeout(timer.current), []);
  if (!product) return null;
  const liked = isInWishlist(product.id);
  const name = typeof product.name === 'string' ? product.name : getLocalized(product.name, locale);
  const images = Array.isArray(product.images) ? product.images.map(imageUrl) : [product.images || product.image];
  const price = new Intl.NumberFormat(locale, { style: 'currency', currency: 'USD' }).format(Number(product.price));
  const discount = product.compare_price > product.price ? Math.round((1 - product.price / product.compare_price) * 100) : 0;
  const quickAdd = () => {
    const hasVariants = product.variants?.colors?.length > 0 || product.variants?.sizes?.length > 0;
    if (hasVariants && !showOptions) { setShowOptions(true); return; }
    const variant = {};
    if (selectedColor) variant.color = selectedColor;
    if (selectedSize) variant.size = selectedSize;
    addToCart(product, variant, 1);
    addToast(`${name} — ${t('product.cart_added')}`, 'success');
    setShowOptions(false); setAdded(true);
    clearTimeout(timer.current);
    timer.current = setTimeout(() => setAdded(false), 1800);
  };
  return <article className="retail-card">
    <div className="retail-image">
      <Link to={`/product/${product.slug}`} aria-label={name} className="retail-image-link">
        <img src={images[0] || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'} alt={name} loading="lazy" />
        {images[1] && <img className="retail-alternate" src={images[1]} alt="" loading="lazy" />}
      </Link>
      {(discount > 0 || product.isNew || product.is_new) && <span className="retail-label">{discount > 0 ? `−${discount}%` : t('editorial.new')}</span>}
      <motion.button whileTap={reduce ? undefined : { scale: .92 }} className="retail-wishlist" aria-label={`${t('nav.wishlist')}: ${name}`} aria-pressed={liked} onClick={() => { const result = toggleWishlist(product); addToast(t(result ? 'product.wishlist_added' : 'product.wishlist_removed'), 'info'); }}><Heart size={17} strokeWidth={1.5} className={liked ? 'liked' : ''} /></motion.button>
    </div>
    <div className="retail-details"><span className="retail-brand">{typeof product.brand === 'object' ? product.brand?.name : product.brand || 'Nuvara'}</span><h3><Link to={`/product/${product.slug}`}>{name}</Link></h3><div className="retail-price"><span>{price}</span>{discount > 0 && <del>{new Intl.NumberFormat(locale, { style: 'currency', currency: 'USD' }).format(Number(product.compare_price))}</del>}<span className="retail-rating">{Number(product.rating || product.avg_rating) > 0 && <>★ {product.rating || product.avg_rating} <small>({product.reviewCount || product.review_count || 0})</small></>}</span></div>
    {showOptions && <div className="retail-options" onClick={event => event.stopPropagation()}>{product.variants?.colors?.length > 0 && <div><span>{t('product.color', { defaultValue: 'Color' })}</span><div>{product.variants.colors.map(color => <button type="button" key={color.name} aria-label={color.name} aria-pressed={selectedColor === color.name} className={selectedColor === color.name ? 'is-selected' : ''} style={{ backgroundColor: color.value }} onClick={() => setSelectedColor(color.name)} />)}</div></div>}{product.variants?.sizes?.length > 0 && <div><span>{t('product.size', { defaultValue: 'Size' })}</span><div>{product.variants.sizes.map(size => <button type="button" key={size} aria-pressed={selectedSize === size} className={selectedSize === size ? 'is-selected' : ''} onClick={() => setSelectedSize(size)}>{size}</button>)}</div></div>}<button type="button" className="retail-options-add" onClick={quickAdd}>{t('product.add_to_cart')}<Plus size={14}/></button></div>}
    <button className="retail-add" disabled={!(product.stock > 0)} onClick={quickAdd}>{product.stock > 0 ? added ? t('product.cart_added') : t('product.add_to_cart') : t('product.out_of_stock')}{added ? <Check size={16}/> : <Plus size={16}/>}</button></div>
  </article>;
};
