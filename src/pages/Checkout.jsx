import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { useCartStore } from '../store/cartStore';
import { useLocaleStore } from '../store/localeStore';
import { useToastStore } from '../store/toastStore';
import { getLocalized } from '../utils/mockData';
import { Button } from '../components/ui/Button';
import { Card } from '../components/ui/Card';
import { ShieldCheck, CreditCard, ChevronRight } from 'lucide-react';

export const Checkout = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const { locale } = useLocaleStore();
  const { cart, getCartTotals, clearCart } = useCartStore();
  const { addToast } = useToastStore();

  const totals = getCartTotals();

  // Form States
  const [formData, setFormData] = useState({
    fullName: '',
    email: '',
    address: '',
    city: '',
    country: '',
    zip: '',
    cardHolder: '',
    cardNumber: '',
    expiry: '',
    cvv: ''
  });

  const [errors, setErrors] = useState({});
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [activeStep, setActiveStep] = useState(1); // 1 = Shipping, 2 = Payment, 3 = Review

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
    // clear error
    if (errors[name]) {
      setErrors({ ...errors, [name]: '' });
    }
  };

  const validateStep = (step) => {
    const newErrors = {};
    if (step === 1) {
      if (!formData.fullName.trim()) newErrors.fullName = 'Full name is required';
      if (!formData.email.trim() || !formData.email.includes('@')) newErrors.email = 'Valid email is required';
      if (!formData.address.trim()) newErrors.address = 'Street address is required';
      if (!formData.city.trim()) newErrors.city = 'City is required';
      if (!formData.zip.trim()) newErrors.zip = 'ZIP / Postal code is required';
    } else if (step === 2) {
      if (!formData.cardHolder.trim()) newErrors.cardHolder = 'Cardholder name is required';
      if (!formData.cardNumber.trim() || formData.cardNumber.replace(/\s/g, '').length < 16) {
        newErrors.cardNumber = 'Valid 16-digit card number is required';
      }
      if (!formData.expiry.trim() || !/^\d{2}\/\d{2}$/.test(formData.expiry)) {
        newErrors.expiry = 'Expiration date is required (MM/YY)';
      }
      if (!formData.cvv.trim() || formData.cvv.length < 3) newErrors.cvv = 'CVV is required';
    }
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleNextStep = (step) => {
    if (validateStep(step)) {
      setActiveStep(step + 1);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!validateStep(1) || !validateStep(2)) {
      addToast('Please complete all form steps correctly', 'danger');
      return;
    }

    setIsSubmitting(true);
    
    // Simulate API checkout call
    setTimeout(() => {
      setIsSubmitting(false);
      const mockOrderId = `NVR-${Math.floor(100000 + Math.random() * 900000)}`;
      addToast('Order placed successfully!', 'success');
      
      // Store details briefly in sessionStorage to display on success page
      sessionStorage.setItem('last_order', JSON.stringify({
        id: mockOrderId,
        name: formData.fullName,
        totals: totals,
        items: cart
      }));

      clearCart();
      navigate(`/order/${mockOrderId}/success`);
    }, 2000);
  };

  if (cart.length === 0) {
    return (
      <div className="max-w-md mx-auto py-20 text-center">
        <h2 className="text-xl font-bold text-text-primary mb-4">No items in your cart</h2>
        <Button variant="primary" onClick={() => navigate('/category/all')}>
          Go to Shop
        </Button>
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in text-left rtl:text-right">
      <h1 className="text-2xl font-black text-text-primary uppercase tracking-wide mb-8 border-b border-border pb-3">
        {t('checkout.title')}
      </h1>

      {/* Progress Steps Indicators */}
      <div className="flex items-center justify-start mb-8 text-xs md:text-sm font-bold text-text-secondary gap-4 md:gap-8">
        <button 
          onClick={() => activeStep > 1 && setActiveStep(1)}
          className={`flex items-center space-x-1.5 rtl:space-x-reverse ${activeStep === 1 ? 'text-accent font-extrabold' : ''}`}
        >
          <span className={`w-5 h-5 rounded-full flex items-center justify-center border text-[10px] ${activeStep >= 1 ? 'bg-accent/10 border-accent text-accent' : 'border-border'}`}>1</span>
          <span>{t('checkout.step_shipping')}</span>
        </button>
        <ChevronRight className="w-4 h-4 text-border rtl-flip" />
        <button 
          onClick={() => activeStep > 2 && setActiveStep(2)}
          className={`flex items-center space-x-1.5 rtl:space-x-reverse ${activeStep === 2 ? 'text-accent font-extrabold' : ''}`}
        >
          <span className={`w-5 h-5 rounded-full flex items-center justify-center border text-[10px] ${activeStep >= 2 ? 'bg-accent/10 border-accent text-accent' : 'border-border'}`}>2</span>
          <span>{t('checkout.step_payment')}</span>
        </button>
        <ChevronRight className="w-4 h-4 text-border rtl-flip" />
        <div className={`flex items-center space-x-1.5 rtl:space-x-reverse ${activeStep === 3 ? 'text-accent font-extrabold' : ''}`}>
          <span className={`w-5 h-5 rounded-full flex items-center justify-center border text-[10px] ${activeStep === 3 ? 'bg-accent/10 border-accent text-accent' : 'border-border'}`}>3</span>
          <span>{t('checkout.step_review')}</span>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        {/* Left Side: Form Sections (2 cols) */}
        <form onSubmit={handleSubmit} className="lg:col-span-2 space-y-6">
          
          {/* Section 1: Shipping Address */}
          <Card className={activeStep !== 1 ? 'opacity-60 pointer-events-none' : ''}>
            <h3 className="text-base font-bold text-text-primary uppercase tracking-wider mb-6 flex items-center justify-between">
              <span>1. {t('checkout.shipping_address')}</span>
              {activeStep > 1 && (
                <button 
                  type="button" 
                  onClick={() => setActiveStep(1)} 
                  className="text-xs font-semibold text-accent hover:underline pointer-events-auto"
                >
                  Edit
                </button>
              )}
            </h3>

            {activeStep === 1 && (
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="sm:col-span-2">
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.full_name')}</label>
                  <input
                    type="text"
                    name="fullName"
                    value={formData.fullName}
                    onChange={handleInputChange}
                    className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.fullName ? 'border-danger' : 'border-border'}`}
                  />
                  {errors.fullName && <p className="text-xs text-danger mt-1">{errors.fullName}</p>}
                </div>

                <div className="sm:col-span-2">
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.email')}</label>
                  <input
                    type="email"
                    name="email"
                    value={formData.email}
                    onChange={handleInputChange}
                    className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.email ? 'border-danger' : 'border-border'}`}
                  />
                  {errors.email && <p className="text-xs text-danger mt-1">{errors.email}</p>}
                </div>

                <div className="sm:col-span-2">
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.address')}</label>
                  <input
                    type="text"
                    name="address"
                    value={formData.address}
                    onChange={handleInputChange}
                    className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.address ? 'border-danger' : 'border-border'}`}
                  />
                  {errors.address && <p className="text-xs text-danger mt-1">{errors.address}</p>}
                </div>

                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.city')}</label>
                  <input
                    type="text"
                    name="city"
                    value={formData.city}
                    onChange={handleInputChange}
                    className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.city ? 'border-danger' : 'border-border'}`}
                  />
                  {errors.city && <p className="text-xs text-danger mt-1">{errors.city}</p>}
                </div>

                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.zip')}</label>
                  <input
                    type="text"
                    name="zip"
                    value={formData.zip}
                    onChange={handleInputChange}
                    className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.zip ? 'border-danger' : 'border-border'}`}
                  />
                  {errors.zip && <p className="text-xs text-danger mt-1">{errors.zip}</p>}
                </div>

                <div className="sm:col-span-2 mt-2">
                  <Button
                    type="button"
                    variant="primary"
                    onClick={() => handleNextStep(1)}
                    className="w-full py-3"
                  >
                    Continue to Payment
                  </Button>
                </div>
              </div>
            )}
          </Card>

          {/* Section 2: Payment Details */}
          <Card className={activeStep !== 2 ? 'opacity-60 pointer-events-none' : ''}>
            <h3 className="text-base font-bold text-text-primary uppercase tracking-wider mb-6 flex items-center justify-between">
              <span>2. {t('checkout.payment_method')}</span>
              {activeStep > 2 && (
                <button 
                  type="button" 
                  onClick={() => setActiveStep(2)} 
                  className="text-xs font-semibold text-accent hover:underline pointer-events-auto"
                >
                  Edit
                </button>
              )}
            </h3>

            {activeStep === 2 && (
              <div className="space-y-4">
                {/* Simulated Card form */}
                <div className="p-4 rounded-xl border border-accent bg-accent/5 flex items-center mb-4">
                  <CreditCard className="w-5 h-5 text-accent mr-3 rtl:ml-3 rtl:mr-0" />
                  <div>
                    <div className="text-sm font-bold text-text-primary">Credit or Debit Card</div>
                    <div className="text-xs text-text-secondary">Safe & protected payment gateway</div>
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.card_holder')}</label>
                  <input
                    type="text"
                    name="cardHolder"
                    placeholder="John Doe"
                    value={formData.cardHolder}
                    onChange={handleInputChange}
                    className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.cardHolder ? 'border-danger' : 'border-border'}`}
                  />
                  {errors.cardHolder && <p className="text-xs text-danger mt-1">{errors.cardHolder}</p>}
                </div>

                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.card_number')}</label>
                  <input
                    type="text"
                    name="cardNumber"
                    placeholder="1234 5678 9101 1121"
                    value={formData.cardNumber}
                    onChange={handleInputChange}
                    className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.cardNumber ? 'border-danger' : 'border-border'}`}
                  />
                  {errors.cardNumber && <p className="text-xs text-danger mt-1">{errors.cardNumber}</p>}
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.expiry')}</label>
                    <input
                      type="text"
                      name="expiry"
                      placeholder="MM/YY"
                      value={formData.expiry}
                      onChange={handleInputChange}
                      className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.expiry ? 'border-danger' : 'border-border'}`}
                    />
                    {errors.expiry && <p className="text-xs text-danger mt-1">{errors.expiry}</p>}
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t('checkout.cvv')}</label>
                    <input
                      type="text"
                      name="cvv"
                      placeholder="123"
                      value={formData.cvv}
                      onChange={handleInputChange}
                      className={`w-full px-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${errors.cvv ? 'border-danger' : 'border-border'}`}
                    />
                    {errors.cvv && <p className="text-xs text-danger mt-1">{errors.cvv}</p>}
                  </div>
                </div>

                <div className="mt-6 flex space-x-3 rtl:space-x-reverse">
                  <Button
                    type="button"
                    variant="ghost"
                    onClick={() => setActiveStep(1)}
                    className="flex-1 py-3 text-xs"
                  >
                    Back to Shipping
                  </Button>
                  <Button
                    type="button"
                    variant="primary"
                    onClick={() => handleNextStep(2)}
                    className="flex-1 py-3 text-xs"
                  >
                    Continue to Review
                  </Button>
                </div>
              </div>
            )}
          </Card>

          {/* Section 3: Review & Confirm */}
          <Card className={activeStep !== 3 ? 'opacity-60 pointer-events-none' : ''}>
            <h3 className="text-base font-bold text-text-primary uppercase tracking-wider mb-6">
              3. {t('checkout.order_review')}
            </h3>

            {activeStep === 3 && (
              <div className="space-y-6">
                <div className="p-4 rounded-xl bg-bg-primary border border-border space-y-3 font-semibold text-xs text-text-secondary">
                  <div>
                    <span className="font-bold text-text-primary">Deliver To:</span> {formData.fullName}
                  </div>
                  <div>
                    <span className="font-bold text-text-primary">Address:</span> {formData.address}, {formData.city}, {formData.zip}
                  </div>
                  <div>
                    <span className="font-bold text-text-primary">Email:</span> {formData.email}
                  </div>
                  <div>
                    <span className="font-bold text-text-primary">Card:</span> ending in {formData.cardNumber.slice(-4)}
                  </div>
                </div>

                <div className="flex space-x-3 rtl:space-x-reverse">
                  <Button
                    type="button"
                    variant="ghost"
                    onClick={() => setActiveStep(2)}
                    className="flex-1 py-3"
                  >
                    Back to Payment
                  </Button>
                  <Button
                    type="submit"
                    variant="primary"
                    loading={isSubmitting}
                    className="flex-1 py-3 text-sm font-extrabold uppercase"
                  >
                    {t('checkout.place_order')}
                  </Button>
                </div>
              </div>
            )}
          </Card>

        </form>

        {/* Right Side: Order Items Panel (1 col) */}
        <aside className="lg:col-span-1">
          <Card className="space-y-6">
            <h3 className="text-base font-bold text-text-primary uppercase tracking-wider border-b border-border pb-3">
              Order Items
            </h3>

            <div className="space-y-4 max-h-60 overflow-y-auto pr-1 scroll-thin">
              {cart.map((item) => (
                <div key={item.id} className="flex justify-between items-center text-xs">
                  <div className="flex items-center space-x-3.5 rtl:space-x-reverse text-left rtl:text-right">
                    <img 
                      src={item.product.images[0]} 
                      alt="" 
                      className="w-10 h-10 object-cover rounded-lg border border-border"
                    />
                    <div>
                      <div className="font-bold text-text-primary line-clamp-1">{getLocalized(item.product.name, locale)}</div>
                      <div className="text-text-secondary mt-0.5">
                        Qty: {item.quantity} {item.variant.size ? `· Size: ${item.variant.size}` : ''}
                      </div>
                    </div>
                  </div>
                  <span className="font-extrabold text-text-primary">${(item.product.price * item.quantity).toFixed(2)}</span>
                </div>
              ))}
            </div>

            <div className="border-t border-border pt-4 space-y-2.5 text-xs font-semibold text-text-secondary">
              <div className="flex justify-between">
                <span>Subtotal</span>
                <span className="text-text-primary">${totals.subtotal.toFixed(2)}</span>
              </div>
              {totals.discount > 0 && (
                <div className="flex justify-between text-green-600 dark:text-green-400">
                  <span>Discount</span>
                  <span>-${totals.discount.toFixed(2)}</span>
                </div>
              )}
              <div className="flex justify-between">
                <span>Shipping</span>
                <span className="text-text-primary">
                  {totals.shipping === 0 ? 'Free' : `$${totals.shipping.toFixed(2)}`}
                </span>
              </div>
              <div className="flex justify-between text-sm font-black text-text-primary border-t border-border pt-3">
                <span>Order Total</span>
                <span className="text-accent">${totals.total.toFixed(2)}</span>
              </div>
            </div>

            <div className="p-4 rounded-xl border border-dashed border-border flex items-center bg-bg-primary/50 text-[10px] text-text-secondary">
              <ShieldCheck className="w-5 h-5 text-green-500 mr-2.5 rtl:ml-2.5 rtl:mr-0 flex-shrink-0" />
              Your transaction is encrypted. We never store credit card numbers.
            </div>
          </Card>
        </aside>
      </div>

    </div>
  );
};
