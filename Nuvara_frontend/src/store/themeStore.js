import { create } from 'zustand';

export const useThemeStore = create((set) => {
  // Initialize from localStorage or fallback to system preference
  const getInitialTheme = () => {
    if (typeof window === 'undefined') return 'light';
    const stored = localStorage.getItem('theme');
    if (stored) return stored;
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    return systemPrefersDark ? 'dark' : 'light';
  };

  const initialTheme = getInitialTheme();

  return {
    theme: initialTheme,
    toggleTheme: () => set((state) => {
      const nextTheme = state.theme === 'light' ? 'dark' : 'light';
      localStorage.setItem('theme', nextTheme);
      
      // Update HTML attribute
      document.documentElement.setAttribute('data-theme', nextTheme);
      
      return { theme: nextTheme };
    }),
    setTheme: (theme) => {
      localStorage.setItem('theme', theme);
      document.documentElement.setAttribute('data-theme', theme);
      set({ theme });
    }
  };
});
