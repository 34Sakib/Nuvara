import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { Trash2, Tag, ShoppingBag, ArrowRight, ArrowLeft } from 'lucide-react';
import { useCartStore } from '../store/cartStore';
import { useLocaleStore } from '../store/localeStore';
import { useToastStore } from '../store/toastStore';
import { getLocalized } from '../utils/mockData';
import { Button } from '../components/ui/Button';
import { QuantityStepper } from '../components/ui/QuantityStepper';
import { Card } from '../components/ui/Card';

export const Cart = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { locale } = useLocaleStore();
  const { addToast } = useToastStore();
  
  const { 
    cart, 
    updateQuantity, 
    removeFromCart, 
    applyCoupon, 
    activeCoupon, 
    removeCoupon,
    getCartTotals 
  } = useCartStore();

  const [couponInput, setCouponInput] = useState('');

  const totals = getCartTotals();

  const handleApplyCoupon = async (e) => {
    e.preventDefault();
    if (!couponInput.trim()) return;

    const res = await applyCoupon(couponInput);
    if (res.success) {
      addToast(t('cart.coupon_applied'), 'success');
      setCouponInput('');
    } else {
      addToast(res.message, 'danger');
    }
  };

  const handleRemoveCoupon = () => {
    removeCoupon();
    addToast(t('product.wishlist_removed'), 'info'); // general action
  };

  if (cart.length === 0) {
    return (
      <div className="max-w-md mx-auto px-4 py-24 text-center animate-fade-in">
        <div className="w-20 h-20 bg-accent/5 rounded-full flex items-center justify-center mx-auto mb-6 text-accent">
          <ShoppingBag className="w-10 h-10" />
        </div>
        <h2 className="text-2xl font-black text-text-primary mb-2">
          {t('cart.empty')}
        </h2>
        <p className="text-sm text-text-secondary mb-8">
          Looks like you haven't added any items to your cart yet.
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
      <h1 className="text-2xl font-black text-text-primary uppercase tracking-wide mb-8 text-left rtl:text-right border-b border-border pb-3">
        {t('cart.title')}
      </h1>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        {/* Cart Item list (Left 2 cols) */}
        <div className="lg:col-span-2 space-y-4">
          <div className="hidden md:grid grid-cols-12 gap-4 text-xs font-bold text-text-secondary uppercase px-4 border-b border-border pb-3">
            <div className="col-span-6">{t('cart.item')}</div>
            <div className="col-span-2 text-center">{t('cart.price')}</div>
            <div className="col-span-2 text-center">{t('cart.quantity')}</div>
            <div className="col-span-2 text-right rtl:text-left">{t('cart.total')}</div>
          </div>

          <div className="space-y-4 divide-y divide-border">
            {cart.map((item) => {
              const p = item.product;
              const hasVariant = Object.keys(item.variant).length > 0;
              
              return (
                <div key={item.id} className="grid grid-cols-1 md:grid-cols-12 gap-4 items-center pt-4 first:pt-0 px-2 md:px-4">
                  {/* Info block */}
                  <div className="col-span-12 md:col-span-6 flex items-center space-x-4 rtl:space-x-reverse text-left rtl:text-right">
                    <img 
                      src={p.images[0]} 
                      alt={getLocalized(p.name, locale)} 
                      className="w-16 h-16 rounded-xl object-cover border border-border bg-bg-primary flex-shrink-0"
                    />
                    <div>
                      <Link to={`/product/${p.slug}`} className="font-bold text-sm text-text-primary hover:text-accent transition-colors">
                        {getLocalized(p.name, locale)}
                      </Link>
                      {hasVariant && (
                        <div className="flex flex-wrap gap-1.5 mt-1.5">
                          {item.variant.color && (
                            <span className="text-[10px] font-bold text-text-secondary bg-bg-primary border border-border px-2 py-0.5 rounded">
                              Color: {item.variant.color}
                            </span>
                          )}
                          {item.variant.size && (
                            <span className="text-[10px] font-bold text-text-secondary bg-bg-primary border border-border px-2 py-0.5 rounded">
                              Size: {item.variant.size}
                            </span>
                          )}
                        </div>
                      )}
                    </div>
                  </div>

                  {/* Unit price */}
                  <div className="col-span-4 md:col-span-2 text-center font-bold text-sm text-text-primary md:block flex justify-between items-center bg-bg-primary md:bg-transparent p-2 md:p-0 rounded">
                    <span className="md:hidden text-xs text-text-secondary font-medium">Unit Price</span>
                    <span>${p.price}</span>
                  </div>

                  {/* Quantity Stepper */}
                  <div className="col-span-4 md:col-span-2 flex justify-center items-center md:block flex justify-between bg-bg-primary md:bg-transparent p-2 md:p-0 rounded">
                    <span className="md:hidden text-xs text-text-secondary font-medium">Quantity</span>
                    <QuantityStepper
                      value={item.quantity}
                      min={1}
                      max={p.stock}
                      onChange={(val) => updateQuantity(item.id, val)}
                    />
                  </div>

                  {/* Item total price & action */}
                  <div className="col-span-4 md:col-span-2 flex justify-between md:justify-end items-center bg-bg-primary md:bg-transparent p-2 md:p-0 rounded">
                    <span className="md:hidden text-xs text-text-secondary font-medium">Total</span>
                    <div className="flex items-center space-x-3.5 rtl:space-x-reverse">
                      <span className="font-extrabold text-sm text-accent">
                        ${(p.price * item.quantity).toFixed(2)}
                      </span>
                      <button
                        onClick={() => removeFromCart(item.id)}
                        className="text-text-secondary hover:text-danger transition-colors p-1"
                        aria-label="Remove item"
                      >
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </div>

                </div>
              );
            })}
          </div>
        </div>

        {/* Order Summary Sidebar (Right 1 col) */}
        <aside className="lg:col-span-1">
          <Card className="space-y-6">
            <h3 className="text-base font-bold text-text-primary uppercase tracking-wider border-b border-border pb-3">
              {t('cart.summary')}
            </h3>

            <div className="space-y-3.5 text-sm font-semibold text-text-secondary">
              <div className="flex justify-between">
                <span>{t('cart.subtotal')}</span>
                <span className="text-text-primary">${totals.subtotal.toFixed(2)}</span>
              </div>
              
              {totals.discount > 0 && (
                <div className="flex justify-between text-green-600 dark:text-green-400">
                  <span>{t('cart.discount')}</span>
                  <span>-${totals.discount.toFixed(2)}</span>
                </div>
              )}
              
              <div className="flex justify-between">
                <span>{t('cart.shipping')}</span>
                <span className="text-text-primary">
                  {totals.shipping === 0 ? t('cart.free') : `$${totals.shipping.toFixed(2)}`}
                </span>
              </div>
              
              <div className="flex justify-between text-base font-black text-text-primary border-t border-border pt-4">
                <span>{t('cart.total')}</span>
                <span className="text-accent">${totals.total.toFixed(2)}</span>
              </div>
            </div>

            {/* Promo Code Form */}
            <form onSubmit={handleApplyCoupon} className="border-t border-border pt-4">
              <label className="block text-xs font-bold text-text-secondary uppercase mb-2">
                {t('cart.promo_code')}
              </label>
              
              {activeCoupon ? (
                <div className="flex items-center justify-between p-2.5 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-900 rounded-lg">
                  <span className="text-xs font-bold text-green-700 dark:text-green-400 flex items-center">
                    <Tag className="w-3.5 h-3.5 mr-1.5 rtl:ml-1.5 rtl:mr-0" />
                    {activeCoupon.code} Applied
                  </span>
                  <button
                    type="button"
                    onClick={handleRemoveCoupon}
                    className="text-xs font-bold text-red-600 hover:underline"
                  >
                    Remove
                  </button>
                </div>
              ) : (
                <div className="flex space-x-2 rtl:space-x-reverse">
                  <input
                    type="text"
                    placeholder="e.g. NUVARA20"
                    value={couponInput}
                    onChange={(e) => setCouponInput(e.target.value)}
                    className="flex-1 px-3 py-2 bg-bg-primary border border-border rounded-lg text-xs font-bold focus:outline-none focus:ring-2 focus:ring-accent/50"
                  />
                  <Button type="submit" variant="secondary" size="sm">
                    {t('cart.apply')}
                  </Button>
                </div>
              )}
              <div className="mt-1.5 text-[10px] text-text-secondary italic">
                Try "NUVARA20" (20% off), "FREESHIP" (free shipping), or "WELCOME10" ($10 off)
              </div>
            </form>

            <Button
              variant="primary"
              onClick={() => navigate('/checkout')}
              className="w-full py-3.5 text-sm font-extrabold uppercase tracking-wide"
              icon={ArrowRight}
            >
              {t('cart.checkout')}
            </Button>
          </Card>
        </aside>

      </div>
    </div>
  );
};
