import React, { useState } from 'react';
import { Maximize2, X } from 'lucide-react';
import './ImageGallery.css';

export const ImageGallery = ({ images = [] }) => {
  const [activeIndex, setActiveIndex] = useState(0);
  const [isFullscreen, setIsFullscreen] = useState(false);

  if (images.length === 0) {
    return (
      <div className="product-gallery-empty">
        <span className="text-xs text-text-secondary">No images</span>
      </div>
    );
  }

  const activeImage = images[activeIndex];

  return (
    <div className="product-gallery">
      {/* Main Image Viewer */}
      <div className={`product-gallery-main ${isFullscreen ? 'is-fullscreen' : ''}`}>
        <img
          src={activeImage}
          alt="Product detail main image"
          className="product-gallery-image"
          onClick={() => setIsFullscreen(true)}
        />
        <button type="button" className="product-gallery-expand" onClick={() => setIsFullscreen(true)} aria-label="View image fullscreen"><Maximize2 size={17} /></button>
        {isFullscreen && <div className="product-lightbox" role="dialog" aria-modal="true" aria-label="Product image fullscreen" onClick={() => setIsFullscreen(false)}><button type="button" className="product-lightbox-close" onClick={() => setIsFullscreen(false)} aria-label="Close fullscreen image"><X size={22} /></button><img src={activeImage} alt="Product detail fullscreen" /></div>}
      </div>

      {/* Thumbnail Bar */}
      {images.length > 1 && (
        <div className="product-gallery-thumbs">
          {images.map((img, idx) => {
            const isActive = idx === activeIndex;
            return (
              <button
                key={idx}
                onClick={() => setActiveIndex(idx)}
                className={`product-gallery-thumb ${isActive ? 'is-active' : ''}`}
                aria-label={`Select image ${idx + 1}`}
                aria-pressed={isActive}
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
