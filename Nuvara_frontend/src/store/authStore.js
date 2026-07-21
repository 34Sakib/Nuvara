import { create } from 'zustand';
import api from '../services/api';

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
        return userProfile;
      } catch (error) {
        if (error.response && error.response.status === 401) {
          get().logout();
        }
        return null;
      }
    }
  };
});
