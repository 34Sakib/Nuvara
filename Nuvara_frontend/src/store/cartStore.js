import { create } from 'zustand';
import api from '../services/api';

export const useCartStore = create((set, get) => {
  const getInitialState = () => {
    if (typeof window === 'undefined') return { cart: [], wishlist: [], activeCoupon: null };
    try {
      const cart = localStorage.getItem('nuvara_cart');
      const wishlist = localStorage.getItem('nuvara_wishlist');
      const activeCoupon = localStorage.getItem('nuvara_coupon');
      return {
        cart: cart ? JSON.parse(cart) : [],
        wishlist: wishlist ? JSON.parse(wishlist) : [],
        activeCoupon: activeCoupon ? JSON.parse(activeCoupon) : null,
      };
    } catch (e) {
      return { cart: [], wishlist: [], activeCoupon: null };
    }
  };

  const syncStorage = (key, data) => {
    if (typeof window !== 'undefined') {
      localStorage.setItem(key, JSON.stringify(data));
    }
  };

  const initial = getInitialState();

  return {
    cart: initial.cart,
    wishlist: initial.wishlist,
    activeCoupon: initial.activeCoupon,

    addToCart: (product, variant, quantity = 1) => {
      const currentCart = get().cart;
      const variantKey = JSON.stringify(variant);
      
      const existingIndex = currentCart.findIndex(
        (item) => item.product.id === product.id && JSON.stringify(item.variant) === variantKey
      );

      let newCart;
      if (existingIndex > -1) {
        newCart = [...currentCart];
        newCart[existingIndex].quantity += quantity;
      } else {
        newCart = [...currentCart, { 
          id: `${product.id}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`, 
          product, 
          variant, 
          quantity 
        }];
      }

      set({ cart: newCart });
      syncStorage('nuvara_cart', newCart);
    },

    removeFromCart: (cartItemId) => {
      const newCart = get().cart.filter((item) => item.id !== cartItemId);
      set({ cart: newCart });
      syncStorage('nuvara_cart', newCart);
    },

    updateQuantity: (cartItemId, quantity) => {
      if (quantity <= 0) {
        get().removeFromCart(cartItemId);
        return;
      }
      const newCart = get().cart.map((item) => 
        item.id === cartItemId ? { ...item, quantity } : item
      );
      set({ cart: newCart });
      syncStorage('nuvara_cart', newCart);
    },

    clearCart: () => {
      set({ cart: [], activeCoupon: null });
      syncStorage('nuvara_cart', []);
      syncStorage('nuvara_coupon', null);
    },

    toggleWishlist: (product) => {
      const currentWishlist = get().wishlist;
      const isAlreadyWish = currentWishlist.some((item) => item.id === product.id);
      
      let newWishlist;
      if (isAlreadyWish) {
        newWishlist = currentWishlist.filter((item) => item.id !== product.id);
      } else {
        newWishlist = [...currentWishlist, product];
      }

      set({ wishlist: newWishlist });
      syncStorage('nuvara_wishlist', newWishlist);
      return !isAlreadyWish; // returns true if added, false if removed
    },

    isInWishlist: (productId) => {
      return get().wishlist.some((item) => item.id === productId);
    },

    applyCoupon: async (code) => {
      try {
        const response = await api.post('/coupons/validate', { code });
        const coupon = response.data.coupon;
        set({ activeCoupon: coupon });
        syncStorage('nuvara_coupon', coupon);
        return { success: true, coupon };
      } catch (error) {
        return { 
          success: false, 
          message: error.response?.data?.message || 'Invalid coupon code' 
        };
      }
    },

    removeCoupon: () => {
      set({ activeCoupon: null });
      syncStorage('nuvara_coupon', null);
    },

    // Cart calculations helper
    getCartTotals: () => {
      const cart = get().cart;
      const coupon = get().activeCoupon;

      const subtotal = cart.reduce((acc, item) => acc + (item.product.price * item.quantity), 0);
      
      let discount = 0;
      if (coupon) {
        if (coupon.type === 'percent') {
          discount = subtotal * (coupon.value / 100);
        } else if (coupon.type === 'flat') {
          discount = Math.min(coupon.value, subtotal);
        }
      }

      let shipping = subtotal > 0 ? 15 : 0; // standard shipping $15
      if (coupon && coupon.type === 'free_shipping') {
        shipping = 0;
      } else if (subtotal > 150) {
        shipping = 0; // free shipping over $150
      }

      const total = Math.max(0, subtotal - discount + shipping);

      return {
        subtotal,
        discount,
        shipping,
        total,
        count: cart.reduce((acc, item) => acc + item.quantity, 0)
      };
    }
  };
});
