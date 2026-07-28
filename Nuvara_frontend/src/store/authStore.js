import { create } from 'zustand';
import api from '../services/api';
import { useCartStore } from './cartStore';

export const useAuthStore = create((set, get) => {
  const getInitialState = () => {
    if (typeof window === 'undefined') return { user: null, token: null, isAuthenticated: false };
    try {
      const token = localStorage.getItem('nuvara_token');
      const user = localStorage.getItem('nuvara_user');
      return {
        token: token || null,
        user: user ? JSON.parse(user) : null,
        isAuthenticated: !!token,
      };
    } catch (e) {
      return { user: null, token: null, isAuthenticated: false };
    }
  };

  if (typeof window !== 'undefined') {
    window.addEventListener('auth-unauthorized', () => {
      set({ user: null, token: null, isAuthenticated: false });
      useCartStore.getState().clearWishlist();
      useCartStore.getState().clearCart();
    });
  }

  const initial = getInitialState();

  return {
    user: initial.user,
    token: initial.token,
    isAuthenticated: initial.isAuthenticated,
    loading: false,

    login: async (email, password) => {
      set({ loading: true });
      try {
        const response = await api.post('/auth/login', { email, password });
        const { user, token } = response.data;
        
        localStorage.setItem('nuvara_token', token);
        localStorage.setItem('nuvara_user', JSON.stringify(user));
        
        set({ user, token, isAuthenticated: true, loading: false });

        // Sync user wishlist with cartStore
        if (user && user.wishlist !== undefined) {
          useCartStore.getState().setWishlist(user.wishlist || []);
        }

        return user;
      } catch (error) {
        set({ loading: false });
        throw error;
      }
    },

    register: async (name, email, password, phone = '') => {
      set({ loading: true });
      try {
        const response = await api.post('/auth/register', { name, email, password, phone });
        const { user, token } = response.data;

        localStorage.setItem('nuvara_token', token);
        localStorage.setItem('nuvara_user', JSON.stringify(user));

        set({ user, token, isAuthenticated: true, loading: false });

        // Sync wishlist
        useCartStore.getState().setWishlist(user.wishlist || []);

        return user;
      } catch (error) {
        set({ loading: false });
        throw error;
      }
    },

    logout: async () => {
      try {
        await api.post('/auth/logout');
      } catch (e) {
        // Ignore
      } finally {
        localStorage.removeItem('nuvara_token');
        localStorage.removeItem('nuvara_user');
        set({ user: null, token: null, isAuthenticated: false });

        // Reset cart and wishlist on logout
        useCartStore.getState().clearWishlist();
        useCartStore.getState().clearCart();
      }
    },

    updateProfile: async (profileData) => {
      set({ loading: true });
      try {
        const response = await api.put('/me', profileData);
        const updatedUser = response.data;
        localStorage.setItem('nuvara_user', JSON.stringify(updatedUser));
        set({ user: updatedUser, loading: false });
        return updatedUser;
      } catch (error) {
        set({ loading: false });
        throw error;
      }
    },

    loadProfile: async () => {
      if (!get().isAuthenticated) return null;
      try {
        const response = await api.get('/me');
        const userProfile = response.data;
        localStorage.setItem('nuvara_user', JSON.stringify(userProfile));
        set({ user: userProfile });

        // Sync user wishlist with cartStore
        if (userProfile && userProfile.wishlist !== undefined) {
          useCartStore.getState().setWishlist(userProfile.wishlist || []);
        }

        return userProfile;
      } catch (error) {
        if (error.response && error.response.status === 401) {
          get().logout();
        }
        return null;
      }
    },

    addAddress: async (addressData) => {
      set({ loading: true });
      try {
        if (get().isAuthenticated) {
          const response = await api.post('/addresses', addressData);
          const newAddresses = response.data.addresses;
          const currentUser = get().user || {};
          const updatedUser = { ...currentUser, addresses: newAddresses };
          localStorage.setItem('nuvara_user', JSON.stringify(updatedUser));
          set({ user: updatedUser, loading: false });
          return response.data;
        } else {
          // Local fallback for offline/demo
          const currentUser = get().user || { addresses: [] };
          const newAddr = { ...addressData, id: Date.now() };
          const updatedAddresses = [...(currentUser.addresses || []), newAddr];
          const updatedUser = { ...currentUser, addresses: updatedAddresses };
          localStorage.setItem('nuvara_user', JSON.stringify(updatedUser));
          set({ user: updatedUser, loading: false });
          return { address: newAddr, addresses: updatedAddresses };
        }
      } catch (error) {
        set({ loading: false });
        throw error;
      }
    },

    updateAddress: async (id, addressData) => {
      set({ loading: true });
      try {
        if (get().isAuthenticated) {
          const response = await api.put(`/addresses/${id}`, addressData);
          const newAddresses = response.data.addresses;
          const currentUser = get().user || {};
          const updatedUser = { ...currentUser, addresses: newAddresses };
          localStorage.setItem('nuvara_user', JSON.stringify(updatedUser));
          set({ user: updatedUser, loading: false });
          return response.data;
        } else {
          // Local fallback
          const currentUser = get().user || { addresses: [] };
          const updatedAddresses = (currentUser.addresses || []).map(a => 
            a.id === id ? { ...a, ...addressData } : a
          );
          const updatedUser = { ...currentUser, addresses: updatedAddresses };
          localStorage.setItem('nuvara_user', JSON.stringify(updatedUser));
          set({ user: updatedUser, loading: false });
          return { addresses: updatedAddresses };
        }
      } catch (error) {
        set({ loading: false });
        throw error;
      }
    },

    deleteAddress: async (id) => {
      set({ loading: true });
      try {
        if (get().isAuthenticated) {
          const response = await api.delete(`/addresses/${id}`);
          const newAddresses = response.data.addresses;
          const currentUser = get().user || {};
          const updatedUser = { ...currentUser, addresses: newAddresses };
          localStorage.setItem('nuvara_user', JSON.stringify(updatedUser));
          set({ user: updatedUser, loading: false });
          return response.data;
        } else {
          // Local fallback
          const currentUser = get().user || { addresses: [] };
          const updatedAddresses = (currentUser.addresses || []).filter(a => a.id !== id);
          const updatedUser = { ...currentUser, addresses: updatedAddresses };
          localStorage.setItem('nuvara_user', JSON.stringify(updatedUser));
          set({ user: updatedUser, loading: false });
          return { addresses: updatedAddresses };
        }
      } catch (error) {
        set({ loading: false });
        throw error;
      }
    }
  };
});
