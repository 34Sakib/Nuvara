import React, { useMemo, useState } from 'react';
import { motion, AnimatePresence, MotionConfig } from 'framer-motion';
import { Barcode, Banknote, Check, ChevronRight, CreditCard, Grid2X2, Minus, Plus, Receipt, Search, ShoppingCart, Trash2, UserRound, X } from 'lucide-react';
import { mockProducts, getLocalized } from '../utils/mockData';

const categories = ['All', 'Electronics', 'Fashion', 'Home & Living', 'Fitness & Outdoors'];
const money = value => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);

export const POS = () => {
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState('All');
  const [sale, setSale] = useState([]);
  const [tender, setTender] = useState('cash');
  const [cashReceived, setCashReceived] = useState('');
  const [message, setMessage] = useState('');
  const [mobileCart, setMobileCart] = useState(false);

  const products = useMemo(() => mockProducts.filter(product => {
    const name = getLocalized(product.name, 'en').toLowerCase();
    const brand = String(product.brand || '').toLowerCase();
    const matchesQuery = !query.trim() || name.includes(query.toLowerCase()) || brand.includes(query.toLowerCase()) || product.sku.toLowerCase().includes(query.toLowerCase());
    const matchesCategory = category === 'All' || String(product.category || '').toLowerCase().includes(category.toLowerCase().split(' ')[0]);
    return matchesQuery && matchesCategory;
  }), [query, category]);
  const subtotal = sale.reduce((sum, line) => sum + line.product.price * line.quantity, 0);
  const change = Math.max(0, Number(cashReceived || 0) - subtotal);

  const addProduct = product => {
    if (!product.stock) return;
    setSale(current => {
      const existing = current.find(line => line.product.id === product.id);
      if (existing) return current.map(line => line.product.id === product.id ? { ...line, quantity: Math.min(line.quantity + 1, product.stock) } : line);
      return [...current, { product, quantity: 1 }];
    });
    setMessage(`${getLocalized(product.name, 'en')} added`);
    setTimeout(() => setMessage(''), 1400);
  };
  const update = (id, delta) => setSale(current => current.map(line => line.product.id === id ? { ...line, quantity: Math.max(0, Math.min(line.quantity + delta, line.product.stock)) } : line).filter(line => line.quantity));
  const charge = () => {
    if (!sale.length) return;
    if (tender === 'cash' && Number(cashReceived) < subtotal) return setMessage(`Enter at least ${money(subtotal)}`);
    setMessage('Sale ready for server confirmation');
  };

  return <MotionConfig reducedMotion="user"><div className="pos-shell min-h-screen bg-bg-primary text-text-primary">
    <header className="pos-topbar h-16 px-4 lg:px-6 flex items-center justify-between border-b border-border bg-surface">
      <div className="flex items-center gap-3"><div className="pos-logo">N</div><div><p className="font-display text-xl leading-none">NUVARA</p><p className="text-[10px] uppercase tracking-widest text-text-secondary mt-1">Retail workspace</p></div></div>
      <div className="hidden md:flex items-center gap-3 text-xs"><span className="pos-status-dot" /> Online <span className="text-border">|</span> Main store · Register 01</div>
      <div className="flex items-center gap-2 text-sm"><span className="hidden sm:inline text-text-secondary">Cashier</span><div className="w-8 h-8 rounded-full bg-green text-white grid place-items-center font-bold">S</div></div>
    </header>
    <div className="pos-body flex">
      <aside className="hidden lg:flex w-20 border-r border-border bg-surface flex-col items-center py-5 gap-5"><div className="pos-rail-active"><ShoppingCart size={19}/><span>Sell</span></div><div className="pos-rail-item"><Receipt size={19}/><span>Sales</span></div><div className="pos-rail-item"><Grid2X2 size={19}/><span>Stock</span></div><div className="pos-rail-item"><UserRound size={19}/><span>People</span></div></aside>
      <main className="flex-1 p-4 lg:p-6 min-w-0"><div className="flex items-center justify-between mb-5"><div><p className="text-xs text-accent uppercase tracking-widest font-bold">Point of sale</p><h1 className="font-display text-3xl">New sale</h1></div><button className="lg:hidden pos-outline-btn" onClick={() => setMobileCart(true)}><ShoppingCart size={17}/> Sale ({sale.length})</button></div>
        <div className="flex gap-3 mb-5"><label className="pos-search flex-1"><Search size={18}/><input autoFocus value={query} onChange={e => setQuery(e.target.value)} placeholder="Search product, brand, or SKU"/><kbd>⌘ K</kbd></label><button className="pos-scan"><Barcode size={19}/><span className="hidden sm:inline">Scan</span></button></div>
        <div className="flex gap-2 overflow-x-auto pb-2 mb-5">{categories.map(item => <button key={item} onClick={() => setCategory(item)} className={`pos-chip ${category === item ? 'active' : ''}`}>{item}</button>)}</div>
        <div className="grid grid-cols-2 xl:grid-cols-3 gap-3 lg:gap-4">{products.map(product => <motion.button key={product.id} whileHover={{ y: -2 }} whileTap={{ scale: .98 }} onClick={() => addProduct(product)} disabled={!product.stock} className="pos-product text-left disabled:opacity-50"><div className="pos-product-image"><img src={product.images?.[0]} alt=""/><span>{product.stock ? `${product.stock} in stock` : 'Sold out'}</span></div><p className="text-[10px] uppercase tracking-widest text-accent font-bold mt-3">{product.brand}</p><h2 className="font-semibold text-sm mt-1 line-clamp-2">{getLocalized(product.name, 'en')}</h2><p className="font-bold mt-2">{money(product.price)}</p></motion.button>)}</div>
      </main>
      <aside className={`pos-receipt-panel ${mobileCart ? 'mobile-open' : ''}`}><div className="flex items-center justify-between mb-5"><div><p className="text-xs text-accent uppercase tracking-widest font-bold">Current sale</p><h2 className="font-display text-2xl">Receipt <span className="text-text-secondary text-sm font-sans">· Draft</span></h2></div><button className="lg:hidden" onClick={() => setMobileCart(false)}><X size={20}/></button></div><button className="pos-customer w-full mb-4"><UserRound size={17}/><span className="flex-1 text-left">Walk-in customer</span><ChevronRight size={16}/></button><div className="pos-lines">{sale.length ? <AnimatePresence initial={false}>{sale.map(line => <motion.div layout initial={{ opacity: 0, y: -4 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0 }} key={line.product.id} className="pos-line"><div className="flex-1 min-w-0"><p className="font-semibold text-sm truncate">{getLocalized(line.product.name, 'en')}</p><p className="text-xs text-text-secondary">{money(line.product.price)} · {line.product.sku}</p></div><div className="pos-stepper"><button onClick={() => update(line.product.id, -1)}><Minus size={13}/></button><span>{line.quantity}</span><button onClick={() => update(line.product.id, 1)}><Plus size={13}/></button></div><p className="font-semibold text-sm w-16 text-right">{money(line.product.price * line.quantity)}</p><button onClick={() => setSale(current => current.filter(item => item.product.id !== line.product.id))} className="text-text-secondary hover:text-danger"><Trash2 size={15}/></button></motion.div>)}</AnimatePresence> : <div className="pos-empty"><ShoppingCart size={28}/><p>Your sale is empty</p><span>Select a product to begin</span></div>}</div><div className="mt-auto pt-5"><div className="space-y-2 text-sm border-t border-border pt-4"><div className="flex justify-between text-text-secondary"><span>Subtotal</span><span>{money(subtotal)}</span></div><div className="flex justify-between text-text-secondary"><span>Discount</span><button className="text-accent text-xs font-bold">Add discount</button></div><div className="flex justify-between text-xl font-bold border-t border-border pt-4"><span>Total</span><span>{money(subtotal)}</span></div></div><div className="mt-4 grid grid-cols-2 gap-2"><button onClick={() => setTender('cash')} className={`pos-tender ${tender === 'cash' ? 'active' : ''}`}><Banknote size={17}/> Cash</button><button onClick={() => setTender('card')} className={`pos-tender ${tender === 'card' ? 'active' : ''}`}><CreditCard size={17}/> Card</button></div>{tender === 'cash' && <div className="mt-3"><label className="text-xs font-bold uppercase tracking-wider text-text-secondary">Cash received</label><input value={cashReceived} onChange={e => setCashReceived(e.target.value)} inputMode="decimal" placeholder={money(subtotal)} className="pos-cash-input"/><p className="flex justify-between text-sm mt-2"><span className="text-text-secondary">Change due</span><strong>{money(change)}</strong></p></div>}<button onClick={charge} disabled={!sale.length} className="pos-charge mt-4"><Check size={18}/> Charge {money(subtotal)}</button>{message && <p role="status" className="text-xs text-center text-accent mt-3">{message}</p>}</div></aside>
    </div>
  </div></MotionConfig>;
};
