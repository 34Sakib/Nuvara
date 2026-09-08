import React from 'react';
import { Link, useParams } from 'react-router-dom';
import { ReceiptText } from 'lucide-react';
import { useLocaleStore } from '../store/localeStore';
import { getLocalized } from '../utils/mockData';

export const OrderSuccess = () => {
  const { id } = useParams();
  const { locale } = useLocaleStore();
  let order;
  try { order = JSON.parse(sessionStorage.getItem('last_order') || 'null'); } catch { order = null; }
  if (order?.id !== id || !order?.receipt) return (
    <div className="max-w-xl mx-auto px-6 py-20 text-center">
      <h1 className="text-2xl mb-4">Order receipt unavailable</h1>
      <p className="text-text-secondary mb-6">This tab does not have a confirmed receipt for this order.</p>
      <Link to="/dashboard?tab=orders" className="text-accent underline">View your account orders</Link>
    </div>
  );
  const money = value => new Intl.NumberFormat(locale, { style: 'currency', currency: order.receipt.currency }).format(Number(value));
  return (
    <div className="max-w-2xl mx-auto px-5 py-14">
      <ReceiptText className="w-12 h-12 text-accent mb-5" />
      <h1 className="text-3xl font-display mb-3">Order received</h1>
      <p className="text-text-secondary mb-7">Thank you, {order.name}. Your order is pending. Payment has not been collected.</p>
      <div className="bg-surface border border-border rounded-2xl p-6 space-y-5">
        <div className="break-all"><span className="text-xs uppercase text-text-secondary">Order reference</span><p className="font-semibold">{order.id}</p></div>
        <p className="text-sm text-text-secondary">Payment: {order.payment_status} · Fulfillment: {order.status}</p>
        <ul className="divide-y divide-border">{order.receipt.lines.map((line, index) =>
          <li key={index} className="py-3 flex justify-between gap-4 text-sm"><span>{getLocalized(line.name, locale)} × {line.quantity}<span className="block text-text-secondary">{line.sku}</span></span><span>{money(line.total)}</span></li>)}</ul>
        <dl className="space-y-3">{[['Subtotal', order.receipt.subtotal], ['Discount', '-' + order.receipt.discount], ['Shipping', order.receipt.shipping], ['Order total', order.receipt.total]].map(([label, value]) =>
          <div key={label} className="flex justify-between"><dt>{label}</dt><dd className="font-semibold">{money(value)}</dd></div>)}</dl>
      </div>
      <div className="flex flex-wrap gap-6 mt-7"><Link to="/category/all" className="text-accent underline">Continue shopping</Link><Link to="/dashboard?tab=orders" className="text-accent underline">Account orders</Link></div>
    </div>
  );
};
