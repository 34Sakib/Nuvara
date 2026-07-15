import React from 'react';

export const SectionDivider = ({ className = '' }) => {
  return (
    <div className={`flex items-center justify-center my-4 select-none ${className}`}>
      <div className="w-14 h-[1px] bg-brass/65"></div>
      <span className="mx-3 text-[10px] text-brass leading-none">◆</span>
      <div className="w-14 h-[1px] bg-brass/65"></div>
    </div>
  );
};
