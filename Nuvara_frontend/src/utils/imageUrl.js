const BACKEND_URL = import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000';
export const DEFAULT_FALLBACK_IMAGE = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80';

export const formatImageUrl = (image, fallback = DEFAULT_FALLBACK_IMAGE) => {
  if (!image) return fallback;
  const rawPath = typeof image === 'object' ? image?.path || image?.url : image;
  if (!rawPath || typeof rawPath !== 'string') return fallback;

  if (
    rawPath.startsWith('http://') ||
    rawPath.startsWith('https://') ||
    rawPath.startsWith('data:') ||
    rawPath.startsWith('blob:')
  ) {
    return rawPath;
  }

  const cleanPath = rawPath.startsWith('/') ? rawPath : `/${rawPath}`;
  return `${BACKEND_URL}${cleanPath}`;
};

export default formatImageUrl;
