import React from 'react';
import './VariantSwatch.css';

export const VariantSwatch = ({
  type = 'color',
  options = [],
  selected,
  onChange
}) => {
  if (options.length === 0) return null;

  return (
    <div className={`variant-options ${type === 'color' ? 'variant-colors' : 'variant-sizes'}`}>
      {type === 'color' ? (
        options.map((opt) => {
          const isSelected = selected === opt.name;
          return (
            <button
              key={opt.name}
              onClick={() => onChange(opt.name)}
              className={`variant-color ${isSelected ? 'is-selected' : ''}`}
              title={opt.name}
              style={{ backgroundColor: opt.value }}
              aria-label={`Select color ${opt.name}`}
            >
              {isSelected && (
                <span className={`w-2.5 h-2.5 rounded-full ${
                  // Make sure checker contrast matches color luminance (simple check)
                  opt.value.toLowerCase() === '#ffffff' || opt.value.toLowerCase() === '#e5e7eb'
                    ? 'bg-black' 
                    : 'bg-white'
                }`} />
              )}
            </button>
          );
        })
      ) : (
        options.map((opt) => {
          const isSelected = selected === opt;
          return (
            <button
              key={opt}
              onClick={() => onChange(opt)}
              className={`variant-size ${isSelected ? 'is-selected' : ''}`}
            >
              {opt}
            </button>
          );
        })
      )}
    </div>
  );
};
