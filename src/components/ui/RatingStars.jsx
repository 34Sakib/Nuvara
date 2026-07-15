import React from 'react';
import { Star } from 'lucide-react';

export const RatingStars = ({
  value,
  count,
  size = 'md',
  onChange,
  interactive = false
}) => {
  const sizes = {
    sm: 'w-3.5 h-3.5',
    md: 'w-4 h-4',
    lg: 'w-5 h-5'
  };

  const roundedVal = Math.round(value * 2) / 2; // rounds to nearest 0.5
  const stars = [];

  for (let i = 1; i <= 5; i++) {
    if (roundedVal >= i) {
      stars.push('full');
    } else if (roundedVal === i - 0.5) {
      stars.push('half');
    } else {
      stars.push('empty');
    }
  }

  return (
    <div className="flex items-center space-x-1 rtl:space-x-reverse select-none">
      <div className="flex items-center text-brass">
        {stars.map((type, idx) => {
          const starProps = {
            key: idx,
            className: `${sizes[size]} ${interactive ? 'cursor-pointer hover:scale-110 transition-transform' : ''}`,
            onClick: () => interactive && onChange && onChange(idx + 1)
          };

          if (type === 'full') {
            return <Star {...starProps} className={`${starProps.className} fill-current`} />;
          } else if (type === 'half') {
            return (
              <div key={idx} className="relative inline-block">
                <Star className={`${sizes[size]} text-border fill-current`} />
                <div className="absolute top-0 left-0 overflow-hidden w-1/2 rtl:right-0 rtl:left-auto">
                  <Star className={`${sizes[size]} text-brass fill-current`} />
                </div>
              </div>
            );
          } else {
            return <Star {...starProps} className={`${starProps.className} text-border`} />;
          }
        })}
      </div>
      
      {count !== undefined && (
        <span className="text-xs text-text-secondary font-medium ms-1.5">
          ({count})
        </span>
      )}
    </div>
  );
};
