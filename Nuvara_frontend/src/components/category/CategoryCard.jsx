import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { ArrowRight, Sparkles } from 'lucide-react';
import { useLocaleStore } from '../../store/localeStore';
import { getLocalized } from '../../utils/mockData';

export const CategoryCard = ({ category, index = 0 }) => {
  const { locale } = useLocaleStore();

  return (
    <motion.div
      initial={{ opacity: 0, y: 25, scale: 0.95 }}
      whileInView={{ opacity: 1, y: 0, scale: 1 }}
      viewport={{ once: true, margin: "-30px" }}
      transition={{ duration: 0.45, delay: index * 0.08, type: 'spring', stiffness: 220, damping: 20 }}
      whileHover={{ y: -6, scale: 1.02 }}
      whileTap={{ scale: 0.98 }}
      className="group relative"
    >
      <Link
        to={`/category/${category.slug}`}
        className="block bg-surface border border-border/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 relative group-hover:border-accent/50"
      >
        {/* Compact Aspect Ratio [16/10] (Images are not too big, clean & compact) */}
        <div className="relative aspect-[16/10] w-full overflow-hidden bg-surface-2">
          <img
            src={category.image}
            alt={getLocalized(category.name, locale)}
            className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
            loading="lazy"
          />
          
          {/* Subtle Gradient Overlay */}
          <div className="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent transition-opacity duration-300" />
          
          {/* Top Floating Badge */}
          <div className="absolute top-3 left-3 rtl:right-3 rtl:left-auto">
            <span className="text-[10px] font-extrabold uppercase tracking-wider bg-black/50 text-white backdrop-blur-md px-2.5 py-1 rounded-full border border-white/20 flex items-center gap-1.5 shadow-sm">
              <Sparkles className="w-3 h-3 text-amber-400" />
              <span>{category.itemCount || 12} Items</span>
            </span>
          </div>

          {/* Bottom Title & Interactive Action Button */}
          <div className="absolute bottom-3.5 left-4 right-4 flex justify-between items-end text-left rtl:text-right">
            <div>
              <h3 className="font-bold text-white text-base sm:text-lg leading-tight group-hover:text-amber-300 transition-colors drop-shadow">
                {getLocalized(category.name, locale)}
              </h3>
            </div>
            
            <div className="w-8 h-8 rounded-full bg-white/20 group-hover:bg-accent group-hover:text-white backdrop-blur-md flex items-center justify-center text-white transition-all duration-300 group-hover:scale-110 shadow-sm flex-shrink-0">
              <ArrowRight className="w-4 h-4 rtl-flip transform group-hover:translate-x-0.5 rtl:group-hover:-translate-x-0.5 transition-transform" />
            </div>
          </div>
        </div>
      </Link>
    </motion.div>
  );
};
