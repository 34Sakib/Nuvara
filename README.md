<img width="1920" height="6309" alt="image" src="https://github.com/user-attachments/assets/9fe0704c-6c3c-4893-a095-f72b66a3493a" />

# 🛍️ Nuvara Storefront

**Nuvara** is a premium, high-fidelity modern e-commerce web application. Designed with sophisticated aesthetics, it features full theme customizability (dark/light modes), multi-language internationalization with complete Right-to-Left (RTL) support, a robust shopping cart system with promo code mechanics, a product wishlist, and interactive checkout workflows.

---

## ✨ Features

- 🌓 **Dynamic Theme Toggle**: Seamless transition between light and dark modes with a curated, high-contrast color palette.
- 🌐 **Full Localization (i18n)**: English (`en`), Spanish (`es`), Bengali (`bn`), and Arabic (`ar`) translation bundles.
- ↩️ **Native RTL Support**: Dynamic document direction switching (`ltr` ↔ `rtl`) optimized for Arabic scripts, with auto-flipping icons and navigation layouts.
- 🛒 **Interactive Cart System**: Fully functional cart powered by Zustand state management. Add, remove, and adjust quantities on the fly.
- 🎟️ **Promo Code engine**: Live client-side discount checks and coupon codes:
  - `NUVARA20` — 20% off
  - `WELCOME10` — Flat $10 off
  - `FREESHIP` — Free shipping
- ❤️ **Wishlist Management**: Toggle items to your wishlist directly from product cards or detail pages, and view/manage them in your Dashboard.
- 👤 **Personalized Dashboard**: Tabs to manage active wishlist, mock order history, shipping addresses, and personal profile information.
- 💳 **Structured Checkout Flow**: Multi-step checkout form validation with order summary calculations and a simulated order success route.
- 🔔 **Sleek Toast Notifications**: Premium micro-interaction popups alerting users of additions to cart, applied coupons, and system updates.
- 📱 **Fully Responsive Layout**: Built with a mobile-first design philosophy, featuring a responsive side-drawer menu, sliding categories, and search suggestions.

---

## 🛠️ Tech Stack

- **Core Framework**: React 19 (JavaScript SPA)
- **Build Tooling**: Vite
- **Styling**: Tailwind CSS & CSS Variables
- **State Management**: Zustand
- **Routing**: React Router DOM (HashRouter)
- **Animations**: Framer Motion
- **Internationalization**: i18next & react-i18next
- **Icons**: Lucide React
- **Linter**: Oxlint (for lightning-fast static analysis)

---

## 🚀 Getting Started

### Prerequisites

Make sure you have Node.js installed (v18+ recommended).

### Installation

Clone the repository and install the dependencies:

```bash
git clone https://github.com/34Sakib/Nuvara.git
cd Nuvara
npm install
```

### Run the App Locally

Start the Vite development server:

```bash
npm run dev
```

The application will run locally, usually accessible at `http://localhost:5173`.

### Production Build

Create an optimized bundle for production:

```bash
npm run build
```

This creates a `dist` folder ready to be deployed to your static hosting provider (e.g. Netlify, Vercel, or GitHub Pages).

---

## 📁 Project Structure

```text
Nuvara/
├── public/                 # Static assets (favicons, SVGs)
├── src/
│   ├── assets/             # Images and design resources
│   ├── components/
│   │   ├── category/       # Category Cards, Filter Sidebars
│   │   ├── layout/         # Header, Footer
│   │   ├── product/        # Swatches, Image Galleries, Product Cards
│   │   └── ui/             # Badges, Steppers, Toasts, RatingStars
│   ├── locales/            # JSON localization dictionaries (en, es, bn, ar)
│   ├── pages/              # Cart, Category, Checkout, Dashboard, Home, ProductDetail
│   ├── store/              # Zustand stores (cartStore, localeStore, themeStore, toastStore)
│   ├── utils/              # Mock products database, helper methods
│   ├── App.jsx             # Root routing wrapper and layout setup
│   ├── index.css           # Global custom classes & theme configuration
│   └── main.jsx            # Application entrypoint
├── tailwind.config.js      # Tailwind configuration
└── vite.config.js          # Vite compilation config
```
