import React from 'react';

export const VariantSwatch = ({
  type = 'color',
  options = [],
  selected,
  onChange
}) => {
  if (options.length === 0) return null;

  return (
    <div className="flex flex-wrap gap-2.5">
      {type === 'color' ? (
        options.map((opt) => {
          const isSelected = selected === opt.name;
          return (
            <button
              key={opt.name}
              onClick={() => onChange(opt.name)}
              className={`
                w-8 h-8 rounded-full border-2 focus:outline-none transition-all hover:scale-105 active:scale-95 flex items-center justify-center
                ${isSelected ? 'border-accent scale-110 shadow-md' : 'border-border'}
              `}
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
              className={`
                px-4 py-2 border rounded-lg text-xs font-bold transition-all focus:outline-none hover:bg-bg-primary
                ${isSelected 
                  ? 'border-accent bg-accent/5 text-accent font-extrabold shadow-sm' 
                  : 'border-border text-text-primary'
                }
              `}
            >
              {opt}
            </button>
          );
        })
      )}
    </div>
  );
};
