/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  darkMode: ['selector', '[data-theme="dark"]'],
  theme: {
    extend: {
      colors: {
        bg: 'var(--bg)',
        'bg-primary': 'var(--bg)',
        'bg-secondary': 'var(--surface-2)',
        surface: 'var(--surface)',
        'surface-2': 'var(--surface-2)',
        'text-primary': 'var(--text-primary)',
        'text-secondary': 'var(--text-secondary)',
        green: 'var(--green)',
        'green-soft': 'var(--green-soft)',
        accent: 'var(--green)',
        'accent-hover': 'var(--green-soft)',
        brass: 'var(--brass)',
        'brass-bright': 'var(--brass-bright)',
        wine: 'var(--wine)',
        danger: 'var(--wine)',
        border: 'var(--border)',
      },
      fontFamily: {
        serif: ['Fraunces', 'serif'],
        sans: ['Inter', 'sans-serif'],
        // Fallbacks for specific locales if loaded
        bengali: ['"Noto Sans Bengali"', '"Hind Siliguri"', 'sans-serif'],
        arabic: ['"Noto Kufi Arabic"', 'Cairo', '"IBM Plex Sans Arabic"', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
