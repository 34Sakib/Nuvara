import React, { useState } from 'react';

export const ImageGallery = ({ images = [] }) => {
  const [activeIndex, setActiveIndex] = useState(0);

  if (images.length === 0) {
    return (
      <div className="aspect-square w-full rounded-2xl bg-bg-secondary border border-border animate-shimmer flex items-center justify-center">
        <span className="text-xs text-text-secondary">No images</span>
      </div>
    );
  }

  const activeImage = images[activeIndex];

  return (
    <div className="flex flex-col gap-4">
      {/* Main Image Viewer */}
      <div className="relative aspect-square w-full rounded-2xl border border-border bg-bg-secondary overflow-hidden group">
        <img
          src={activeImage}
          alt="Product detail main image"
          className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 cursor-zoom-in"
        />
      </div>

      {/* Thumbnail Bar */}
      {images.length > 1 && (
        <div className="flex gap-3 overflow-x-auto pb-1 scroll-thin">
          {images.map((img, idx) => {
            const isActive = idx === activeIndex;
            return (
              <button
                key={idx}
                onClick={() => setActiveIndex(idx)}
                className={`
                  relative w-20 h-20 rounded-xl overflow-hidden border-2 bg-bg-secondary transition-all flex-shrink-0
                  ${isActive ? 'border-accent scale-95 shadow-sm' : 'border-border opacity-70 hover:opacity-100'}
                `}
              >
                <img
                  src={img}
                  alt={`Thumbnail ${idx + 1}`}
                  className="w-full h-full object-cover"
                />
              </button>
            );
          })}
        </div>
      )}
    </div>
  );
};
