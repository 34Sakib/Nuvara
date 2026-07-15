import React, { useEffect, useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { CheckCircle2, ArrowRight, Package } from 'lucide-react';
import { getLocalized } from '../utils/mockData';
import { useLocaleStore } from '../store/localeStore';
import { Button } from '../components/ui/Button';

export const OrderSuccess = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { locale } = useLocaleStore();
  const [order, setOrder] = useState(null);

  useEffect(() => {
    // Read from session storage
    const storedOrder = sessionStorage.getItem('last_order');
    if (storedOrder) {
      setOrder(JSON.parse(storedOrder));
    }
  }, []);

  const handleTrackOrder = () => {
    navigate('/dashboard?tab=orders');
  };

  const name = order?.name || 'Customer';
  const orderId = order?.id || 'NVR-847291';
  const items = order?.items || [];
  const totals = order?.totals || { total: 199.99, subtotal: 199.99, shipping: 0, discount: 0 };

  return (
    <div className="max-w-2xl mx-auto px-4 py-16 text-center animate-fade-in">
      {/* Success Animation Ring */}
      <div className="w-24 h-24 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-900 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce text-green-500">
        <CheckCircle2 className="w-12 h-12" />
      </div>

      <h1 className="text-3xl font-black text-text-primary uppercase tracking-wide mb-3">
        {t('success.title')}
      </h1>
      
      <p className="text-base text-text-secondary max-w-md mx-auto mb-6">
        {t('success.thank_you', { name })}
      </p>

      {/* Order Card Info */}
      <div className="bg-bg-secondary border border-border rounded-2xl p-6 mb-8 text-left rtl:text-right shadow-sm transition-colors">
        <div className="flex justify-between items-center border-b border-border pb-4 mb-4">
          <div>
            <div className="text-[10px] uppercase font-bold text-text-secondary">Order ID</div>
            <div className="text-sm font-black text-text-primary mt-0.5">#{orderId}</div>
          </div>
          <div className="text-right rtl:text-left">
            <div className="text-[10px] uppercase font-bold text-text-secondary">Estimated Delivery</div>
            <div className="text-sm font-black text-accent mt-0.5">2-4 Business Days</div>
          </div>
        </div>

        {/* Short list of items */}
        {items.length > 0 && (
          <div className="space-y-3.5 mb-6">
            {items.map((item) => (
              <div key={item.id} className="flex justify-between items-center text-xs text-text-secondary font-semibold">
                <span className="line-clamp-1">{getLocalized(item.product.name, locale)} x {item.quantity}</span>
                <span className="text-text-primary font-bold">${(item.product.price * item.quantity).toFixed(2)}</span>
              </div>
            ))}
          </div>
        )}

        <div className="border-t border-border pt-4 flex justify-between items-center text-sm font-black text-text-primary">
          <span>Amount Paid</span>
          <span className="text-accent">${totals.total.toFixed(2)}</span>
        </div>
      </div>

      <p className="text-xs text-text-secondary max-w-sm mx-auto mb-8 leading-relaxed">
        {t('success.sub')}
      </p>

      <div className="flex flex-col sm:flex-row justify-center gap-4">
        <Button
          variant="secondary"
          onClick={handleTrackOrder}
          className="px-6 py-3.5"
          icon={Package}
        >
          {t('success.track_order')}
        </Button>
        <Link to="/category/all">
          <Button
            variant="primary"
            className="w-full sm:w-auto px-6 py-3.5"
            icon={ArrowRight}
          >
            {t('success.go_home')}
          </Button>
        </Link>
      </div>

    </div>
  );
};
