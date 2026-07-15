import React from 'react';
import { Link } from 'react-router-dom';
import { useLocaleStore } from '../../store/localeStore';
import { getLocalized } from '../../utils/mockData';

export const CategoryCard = ({ category }) => {
  const { locale } = useLocaleStore();

  return (
    <Link
      to={`/category/${category.slug}`}
      className="group relative block aspect-[3/4] rounded-[6px] overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-border"
    >
      {/* Background Image Wrapper for scaling */}
      <div className="w-full h-full overflow-hidden bg-surface-2">
        <img
          src={category.image}
          alt={getLocalized(category.name, locale)}
          className="w-full h-full object-cover group-hover:scale-[1.08] transition-transform duration-500"
          loading="lazy"
        />
      </div>

      {/* Gradient Overlay (transparent starting ~55% up) */}
      <div className="absolute inset-0 transition-opacity duration-300" style={{ backgroundImage: 'linear-gradient(to top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.3) 30%, rgba(0,0,0,0) 55%)' }}></div>

      {/* Category Info - bottom-left, Fraunces serif label */}
      <div className="absolute bottom-4 left-4 right-4 rtl:text-right text-left">
        <h3 className="font-serif font-medium text-[#F5EFE4] text-base leading-tight tracking-wide drop-shadow">
          {getLocalized(category.name, locale)}
        </h3>
        <p className="text-white/60 text-[10px] font-sans font-bold uppercase tracking-wider mt-1 hidden sm:block">
          {category.itemCount} Products
        </p>
      </div>
    </Link>
  );
};
