import React, { useRef, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useCartStore } from '../store/cartStore';
import { useLocaleStore } from '../store/localeStore';
import { getLocalized } from '../utils/mockData';
import { Button } from '../components/ui/Button';
import { Card } from '../components/ui/Card';
import { ArrowLeft, ClipboardCheck, ReceiptText } from 'lucide-react';
import api from '../services/api';

const ATTEMPT_KEY = 'nuvara_checkout_attempt';
function readAttempt() {
  try { return JSON.parse(sessionStorage.getItem(ATTEMPT_KEY) || 'null'); }
  catch { return null; }
}
const fields = [
  ['fullName', 'Full name', 'name'], ['email', 'Email', 'email'],
  ['address', 'Street address', 'street-address'], ['city', 'City', 'address-level2'],
  ['state', 'State / region (optional)', 'address-level1'], ['zip', 'Postal code', 'postal-code'],
  ['country', 'Country', 'country-name'],
];
const errorText = error => Object.values(error.response?.data?.errors || {}).flat().join(' ') ||
  error.response?.data?.message || 'The store could not be reached. Your cart is saved. Please try again.';

export const Checkout = () => {
  const navigate = useNavigate();
  const { locale } = useLocaleStore();
  const { cart, activeCoupon, clearCart } = useCartStore();
  const [attempt, setAttempt] = useState(readAttempt);
  const [form, setForm] = useState(() => readAttempt() || Object.fromEntries(fields.map(([name]) => [name, ''])));
  const [quote, setQuote] = useState(null);
  const [error, setError] = useState('');
  const [busy, setBusy] = useState(false);
  const pending = useRef(false);
  const cartPayload = {
    cart: cart.map(item => ({ product: { id: item.product.id }, quantity: item.quantity, variant: item.variant || {} })),
    coupon: activeCoupon?.code || null,
  };
  const fingerprint = JSON.stringify(cartPayload);
  const quoteIsCurrent = quote?.fingerprint === fingerprint;
  const money = value => new Intl.NumberFormat(locale, { style: 'currency', currency: 'USD' }).format(Number(value));

  async function review(event) {
    event.preventDefault();
    if (pending.current) return;
    pending.current = true; setBusy(true); setError('');
    try {
      const response = await api.post('/checkout/quote', cartPayload);
      setQuote({ ...response.data, fingerprint });
    } catch (failure) { setError(errorText(failure)); setQuote(null); }
    finally { pending.current = false; setBusy(false); }
  }

  async function confirm() {
    if (pending.current || (!attempt && !quoteIsCurrent)) return;
    pending.current = true; setBusy(true); setError('');
    let payload = attempt;
    try {
      if (!payload) {
        payload = {
          ...Object.fromEntries(fields.map(([name]) => [name, form[name].trim()])),
          ...cartPayload, expected_total_minor: quote.total_minor, checkout_key: crypto.randomUUID(),
        };
        // Persist before sending: every uncertain retry must use the identical request.
        sessionStorage.setItem(ATTEMPT_KEY, JSON.stringify(payload));
        setAttempt(payload);
      }
      const { data } = await api.post('/checkout', payload);
      sessionStorage.setItem('last_order', JSON.stringify({
        id: data.order_id, name: payload.fullName, status: data.status,
        payment_status: data.payment_status, receipt: data.receipt,
      }));
      sessionStorage.removeItem(ATTEMPT_KEY);
      setAttempt(null);
      clearCart();
      navigate('/order/' + data.order_id + '/success', { replace: true });
    } catch (failure) {
      if (failure.response?.status === 422) {
        // Validation is definitive: the transaction did not commit.
        sessionStorage.removeItem(ATTEMPT_KEY); setAttempt(null); setQuote(null);
      }
      setError(failure.response ? errorText(failure) :
        'Confirmation was interrupted. Use Retry confirmation to recover the same order safely. Your cart is saved.');
    } finally { pending.current = false; setBusy(false); }
  }

  if (!cart.length && !attempt) return (
    <div className="max-w-lg mx-auto px-6 py-20 text-center">
      <ReceiptText className="w-12 h-12 mx-auto mb-5 text-accent" />
      <h1 className="text-2xl font-display mb-3">Your cart is empty</h1>
      <p className="text-text-secondary mb-6">Choose a product to start your order.</p>
      <Link to="/category/all" className="text-accent underline">Browse products</Link>
    </div>
  );

  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 py-10 text-left rtl:text-right">
      <Link to="/cart" className="inline-flex items-center gap-2 text-sm text-text-secondary mb-6"><ArrowLeft size={16} /> Back to cart</Link>
      <p className="text-xs uppercase tracking-widest text-accent font-bold mb-2">Nuvara · Checkout</p>
      <h1 className="text-3xl sm:text-4xl font-display mb-3">Review your order</h1>
      <p className="text-text-secondary mb-8">Confirm your delivery details and the latest store prices.</p>
      {error && <div role="alert" className="border border-red-300 bg-red-50 text-red-900 rounded-xl p-4 mb-6">{error}</div>}
      {attempt ? (
        <Card className="max-w-2xl space-y-5">
          <h2 className="text-xl font-semibold">Recover your order confirmation</h2>
          <p className="text-text-secondary">A confirmation was started in this tab. Retry the same request to check its result before placing another order.</p>
          <Button onClick={confirm} loading={busy}>Retry confirmation</Button>
        </Card>
      ) : (
        <div className="grid lg:grid-cols-[1.25fr_1fr] gap-8 items-start">
          <form onSubmit={review}>
            <Card>
              <h2 className="text-xl font-semibold mb-6">Delivery details</h2>
              <fieldset disabled={busy || quoteIsCurrent} className="grid sm:grid-cols-2 gap-5 disabled:opacity-70">
                {fields.map(([name, label, autoComplete]) => (
                  <div key={name} className={name === 'address' ? 'sm:col-span-2' : ''}>
                    <label htmlFor={name} className="block text-sm font-medium mb-2">{label}</label>
                    <input id={name} name={name} autoComplete={autoComplete} type={name === 'email' ? 'email' : 'text'}
                      required={name !== 'state'} maxLength={255} value={form[name]}
                      onChange={event => setForm({ ...form, [name]: event.target.value })}
                      className="w-full min-h-12 rounded-lg border border-border bg-bg-primary px-3 text-text-primary focus:outline-none focus:ring-2 focus:ring-accent" />
                  </div>
                ))}
              </fieldset>
              <div className="mt-6">
                {quoteIsCurrent ?
                  <Button type="button" variant="secondary" disabled={busy} onClick={() => setQuote(null)}>Edit details</Button> :
                  <Button type="submit" loading={busy}>Review latest total</Button>}
              </div>
            </Card>
          </form>
          <aside className="space-y-5">
            <Card className="space-y-5">
              <div className="flex items-center gap-3"><ClipboardCheck size={22} className="text-accent" /><h2 className="text-xl font-semibold">Order summary</h2></div>
              {quoteIsCurrent ? (
                <>
                  <ul className="divide-y divide-border">
                    {quote.lines.map((line, index) => <li key={index} className="py-3 flex justify-between gap-4 text-sm">
                      <span>{getLocalized(line.name, locale)}<span className="block text-text-secondary">{line.sku} · Qty {line.quantity}</span></span>
                      <span className="whitespace-nowrap font-semibold">{money(line.total)}</span>
                    </li>)}
                  </ul>
                  <dl className="space-y-3 text-sm border-t border-border pt-4">
                    {[['Subtotal', quote.subtotal], ['Discount', '-' + quote.discount], ['Shipping', quote.shipping]].map(([label, value]) =>
                      <div key={label} className="flex justify-between"><dt>{label}</dt><dd>{money(value)}</dd></div>)}
                    <div className="flex justify-between text-xl font-bold border-t border-border pt-4"><dt>Total</dt><dd>{money(quote.total)}</dd></div>
                  </dl>
                </>
              ) : <p className="text-text-secondary text-sm">Your cart has {cart.reduce((sum, item) => sum + item.quantity, 0)} items. Review the latest total to check prices, stock, and any coupon with the store.</p>}
            </Card>
            <Card className="space-y-3">
              <h2 className="font-semibold">Payment pending</h2>
              <p className="text-sm text-text-secondary leading-relaxed">Submitting creates an unpaid order. Online payment is not available yet. No card details are requested and no payment is collected here.</p>
              {quoteIsCurrent && <Button className="w-full min-h-12" onClick={confirm} loading={busy}>Confirm unpaid order · {money(quote.total)}</Button>}
            </Card>
          </aside>
        </div>
      )}
    </div>
  );
};
