import React from 'react';
import { Plus, Minus } from 'lucide-react';

export const QuantityStepper = ({
  value,
  min = 1,
  max = 99,
  onChange,
  className = ''
}) => {
  const handleDecrement = () => {
    if (value > min) {
      onChange(value - 1);
    }
  };

  const handleIncrement = () => {
    if (value < max) {
      onChange(value + 1);
    }
  };

  const handleInputChange = (e) => {
    const val = parseInt(e.target.value, 10);
    if (!isNaN(val)) {
      if (val >= min && val <= max) {
        onChange(val);
      }
    }
  };

  return (
    <div className={`inline-flex items-center rounded-lg border border-border bg-bg-secondary p-1 ${className}`}>
      <button
        onClick={handleDecrement}
        disabled={value <= min}
        className="p-1 text-text-secondary hover:text-text-primary disabled:opacity-30 rounded hover:bg-bg-primary transition-colors focus:outline-none"
        aria-label="Decrease quantity"
      >
        <Minus className="w-4 h-4" />
      </button>
      
      <input
        type="text"
        value={value}
        onChange={handleInputChange}
        className="w-10 text-center bg-transparent text-sm font-semibold text-text-primary focus:outline-none"
        aria-label="Product quantity"
      />
      
      <button
        onClick={handleIncrement}
        disabled={value >= max}
        className="p-1 text-text-secondary hover:text-text-primary disabled:opacity-30 rounded hover:bg-bg-primary transition-colors focus:outline-none"
        aria-label="Increase quantity"
      >
        <Plus className="w-4 h-4" />
      </button>
    </div>
  );
};
