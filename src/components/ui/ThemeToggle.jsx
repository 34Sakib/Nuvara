import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Sun, Moon } from 'lucide-react';
import { useThemeStore } from '../../store/themeStore';

export const ThemeToggle = () => {
  const { theme, toggleTheme } = useThemeStore();

  return (
    <button
      onClick={toggleTheme}
      className="flex items-center px-2.5 py-2 sm:px-4 sm:py-2 rounded-full border border-border bg-surface hover:bg-surface-2 transition-colors focus:outline-none focus:ring-2 focus:ring-green/50 text-xs font-extrabold text-text-primary select-none"
      aria-label="Toggle theme"
    >
      <AnimatePresence mode="wait" initial={false}>
        <motion.div
          key={theme}
          initial={{ y: 8, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          exit={{ y: -8, opacity: 0 }}
          transition={{ duration: 0.15 }}
          className="flex items-center space-x-1 rtl:space-x-reverse"
        >
          {theme === 'light' ? (
            <>
              <Moon className="w-3.5 h-3.5 text-text-primary" />
              <span className="hidden sm:inline">Dark</span>
            </>
          ) : (
            <>
              <Sun className="w-3.5 h-3.5 text-brass" />
              <span className="hidden sm:inline">Light</span>
            </>
          )}
        </motion.div>
      </AnimatePresence>
    </button>
  );
};
