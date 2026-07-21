import React from 'react';
import { Loader2 } from 'lucide-react';

export const Button = ({
  children,
  variant = 'primary',
  size = 'md',
  loading = false,
  icon: Icon,
  className = '',
  disabled,
  ...props
}) => {
  const baseStyle = 'inline-flex items-center justify-center font-bold rounded-[4px] font-sans transition-all focus:outline-none focus:ring-2 focus:ring-green/50 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none disabled:active:scale-100';
  
  const variants = {
    primary: 'bg-green hover:bg-green-soft text-[#F5EFE4]',
    secondary: 'border border-text-primary text-text-primary hover:bg-text-primary/5 bg-transparent',
    ghost: 'bg-transparent text-text-primary hover:bg-surface-2 border-transparent',
    danger: 'bg-wine text-white hover:opacity-90'
  };

  const sizes = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base'
  };

  return (
    <button
      disabled={disabled || loading}
      className={`${baseStyle} ${variants[variant]} ${sizes[size]} ${className}`}
      {...props}
    >
      {loading ? (
        <Loader2 className="w-4 h-4 mr-2 animate-spin rtl:ml-2 rtl:mr-0" />
      ) : Icon ? (
        <Icon className="w-4 h-4 mr-2 rtl:ml-2 rtl:mr-0" />
      ) : null}
      {children}
    </button>
  );
};
