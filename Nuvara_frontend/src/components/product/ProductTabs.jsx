import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useLocaleStore } from '../../store/localeStore';
import { getLocalized } from '../../utils/mockData';
import { RatingStars } from '../ui/RatingStars';
import { Star, MessageSquare } from 'lucide-react';

export const ProductTabs = ({ product }) => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();
  const [activeTab, setActiveTab] = useState('description');

  const tabs = [
    { id: 'description', label: t('product.tab_description') },
    { id: 'specs', label: t('product.tab_specs') },
    { id: 'reviews', label: t('product.tab_reviews') },
    { id: 'shipping', label: t('product.tab_shipping') }
  ];

  const specsList = product.specs?.[locale] || product.specs?.en || [];
  const reviews = product.reviews || [];

  // Calculate rating breakdown
  const ratingDistribution = [0, 0, 0, 0, 0]; // 5, 4, 3, 2, 1
  reviews.forEach((r) => {
    const floorRating = Math.min(5, Math.max(1, Math.floor(r.rating)));
    ratingDistribution[5 - floorRating]++;
  });

  const totalRatings = reviews.length;

  return (
    <div className="w-full bg-bg-secondary border border-border rounded-2xl p-6 md:p-8 transition-colors shadow-sm">
      {/* Tab Headers */}
      <div className="flex border-b border-border overflow-x-auto pb-1 scroll-thin mb-6 gap-6">
        {tabs.map((tab) => {
          const isActive = activeTab === tab.id;
          return (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`
                py-3 px-1 text-sm font-bold border-b-2 whitespace-nowrap focus:outline-none transition-colors
                ${isActive 
                  ? 'border-accent text-accent' 
                  : 'border-transparent text-text-secondary hover:text-text-primary'
                }
              `}
            >
              {tab.label}
              {tab.id === 'reviews' && reviews.length > 0 && (
                <span className="ms-1.5 px-1.5 py-0.5 rounded-full bg-bg-primary border border-border text-xs">
                  {reviews.length}
                </span>
              )}
            </button>
          );
        })}
      </div>

      {/* Tab Body */}
      <div className="min-h-48 text-sm leading-relaxed text-text-primary">
        
        {/* Description Tab */}
        {activeTab === 'description' && (
          <div className="space-y-4 animate-fade-in">
            <p className="text-base text-text-secondary">
              {getLocalized(product.description, locale)}
            </p>
          </div>
        )}

        {/* Specifications Tab */}
        {activeTab === 'specs' && (
          <div className="animate-fade-in">
            {specsList.length > 0 ? (
              <div className="border border-border rounded-xl overflow-hidden">
                <table className="w-full text-left rtl:text-right border-collapse">
                  <thead>
                    <tr className="bg-bg-primary text-text-secondary text-xs uppercase font-bold border-b border-border">
                      <th className="p-4 w-1/3">Feature</th>
                      <th className="p-4">Specification</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-border">
                    {specsList.map((item, idx) => (
                      <tr key={idx} className="hover:bg-bg-primary/50 transition-colors">
                        <td className="p-4 font-bold text-text-secondary">{item.key}</td>
                        <td className="p-4 font-medium text-text-primary">{item.value}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            ) : (
              <p className="text-text-secondary italic">No technical specifications listed for this product.</p>
            )}
          </div>
        )}

        {/* Reviews Tab */}
        {activeTab === 'reviews' && (
          <div className="animate-fade-in space-y-8">
            {/* Reviews Breakdown Header */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 items-center bg-bg-primary/40 border border-border rounded-xl p-6">
              {/* Total Stars Block */}
              <div className="text-center md:border-r rtl:md:border-r-0 rtl:md:border-l border-border py-4">
                <div className="text-4xl font-extrabold text-text-primary mb-1">{product.rating}</div>
                <div className="flex justify-center mb-2">
                  <RatingStars value={product.rating} size="lg" />
                </div>
                <p className="text-xs text-text-secondary font-medium">
                  {t('product.reviews_based', { count: product.reviewCount })}
                </p>
              </div>

              {/* Progress Bars Block */}
              <div className="md:col-span-2 space-y-2">
                {[5, 4, 3, 2, 1].map((stars, idx) => {
                  const count = ratingDistribution[idx];
                  const percent = totalRatings > 0 ? (count / totalRatings) * 100 : 0;
                  
                  return (
                    <div key={stars} className="flex items-center text-xs text-text-secondary">
                      <button className="flex items-center space-x-1 rtl:space-x-reverse hover:text-accent font-bold w-12 text-left rtl:text-right">
                        <span>{stars}</span>
                        <Star className="w-3 h-3 fill-amber-400 text-amber-400" />
                      </button>
                      <div className="flex-1 h-2 bg-border rounded-full mx-3 overflow-hidden">
                        <div 
                          className="h-full bg-amber-400 rounded-full transition-all duration-500" 
                          style={{ width: `${percent}%` }}
                        ></div>
                      </div>
                      <span className="w-8 text-right rtl:text-left font-bold">{Math.round(percent)}%</span>
                    </div>
                  );
                })}
              </div>
            </div>

            {/* Reviews List */}
            {reviews.length > 0 ? (
              <div className="divide-y divide-border space-y-6">
                {reviews.map((rev) => (
                  <div key={rev.id} className="pt-6 first:pt-0">
                    <div className="flex justify-between items-start">
                      <div>
                        <h4 className="font-bold text-text-primary text-sm">{rev.name}</h4>
                        <div className="mt-1">
                          <RatingStars value={rev.rating} size="sm" />
                        </div>
                      </div>
                      <span className="text-xs text-text-secondary font-medium">{rev.date}</span>
                    </div>
                    <p className="text-sm text-text-secondary mt-3">
                      {getLocalized(rev.comment, locale)}
                    </p>
                  </div>
                ))}
              </div>
            ) : (
              <div className="text-center py-12 text-text-secondary border border-dashed border-border rounded-xl">
                <MessageSquare className="w-10 h-10 mx-auto text-text-secondary/50 mb-3" />
                <p className="font-semibold text-sm">No reviews yet for this product.</p>
                <p className="text-xs mt-1">Be the first to share your thoughts!</p>
              </div>
            )}
          </div>
        )}

        {/* Shipping & Returns Tab */}
        {activeTab === 'shipping' && (
          <div className="space-y-4 animate-fade-in text-text-secondary text-base">
            <p className="font-bold text-text-primary">Shipping Information</p>
            <p>
              We ship internationally using express carriers (DHL/FedEx). Domestic shipments are delivered within 2-4 business days. Free shipping is automatically applied to orders over $150.
            </p>
            <p className="font-bold text-text-primary mt-6">Easy Returns Policy</p>
            <p>
              If you are not completely satisfied with your purchase, you may return the item within 30 days of receipt for a full refund or exchange. Items must be returned in their original packaging and unused condition.
            </p>
          </div>
        )}

      </div>
    </div>
  );
};
