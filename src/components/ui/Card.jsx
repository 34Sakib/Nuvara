import React from 'react';

export const Card = ({
  children,
  padding = true,
  hover = false,
  className = '',
  ...props
}) => {
  return (
    <div
      className={`
        bg-bg-secondary 
        border border-border 
        rounded-2xl 
        overflow-hidden 
        transition-all duration-300
        ${padding ? 'p-5' : 'p-0'} 
        ${hover ? 'hover:shadow-xl hover:scale-[1.02] hover:border-accent/20' : 'shadow-sm'} 
        ${className}
      `}
      {...props}
    >
      {children}
    </div>
  );
};
