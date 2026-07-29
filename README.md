<p align="center">
  <img src="https://github.com/user-attachments/assets/9fe0704c-6c3c-4893-a095-f72b66a3493a"
       width="62%" alt="Nuvara Banner" />
</p>

# 🛍️ Nuvara Storefront & REST API

**Nuvara** is a premium, high-fidelity modern full-stack e-commerce platform. Designed with sophisticated aesthetics, it combines a responsive **React 19 Frontend** client with a robust **Laravel 12 REST API Backend**.

It features full theme customizability (Dark/Light modes), multi-language internationalization with complete Right-to-Left (RTL) support, state-managed shopping cart & promo code engines, product wishlist & review systems, user authentication, multi-step checkout, and dynamic dashboard workflows.

---

## ✨ System Features

### 🎨 Frontend (React 19 SPA)
- 🌓 **Dynamic Theme Toggle**: Seamless transition between light and dark modes with a curated, high-contrast color palette.
- 🌐 **Full Localization (i18n)**: English (`en`), Spanish (`es`), Bengali (`bn`), and Arabic (`ar`) translation bundles.
- ↩️ **Native RTL Support**: Dynamic document direction switching (`ltr` ↔ `rtl`) optimized for Arabic scripts, with auto-flipping icons and navigation layouts.
- 🛒 **Interactive Cart System**: Powered by Zustand state management. Add, remove, and adjust quantities on the fly.
- 🎟️ **Promo Code Engine**: Client-side & server-side validated discount codes:
  - `NUVARA20` — 20% off
  - `WELCOME10` — Flat $10 off
  - `FREESHIP` — Free shipping
- ❤️ **Wishlist Management**: Toggle wishlist items directly from product cards or detail pages, synchronized with customer accounts.
- 👤 **Personalized Dashboard**: Tabs to manage active wishlists, order histories, saved shipping addresses, and personal profiles.
- 💳 **Structured Checkout Flow**: Multi-step checkout form validation with order summary calculations and order success routes.
- 🔔 **Sleek Toast Notifications**: Micro-interaction popups alerting users of cart additions, applied coupons, and system updates.
- 📱 **Fully Responsive Layout**: Built with a mobile-first philosophy featuring sliding categories, drawer navigation, and search suggestions.

### ⚙️ Backend (Laravel 12 REST API)
- 🔐 **Sanctum Authentication**: Secure token-based API authentication for user registration, login, profile management, and logouts.
- 📦 **Product Catalog Engine**: Complete database schemas for Products, Product Variants, Categories, Brands, Product Images, and Banners.
- 🔍 **Search & Filtering**: RESTful query parameters to filter products by category, price ranges, ratings, and keyword search.
- 🛒 **Order Management & Checkout**: Endpoints supporting both guest checkouts and authenticated user orders.
- 📍 **Address Book API**: Full CRUD capabilities for user delivery and billing addresses.
- ⭐️ **Product Reviews & Ratings**: Submit and fetch customer product reviews and star ratings.
- 📧 **Newsletter Subscriptions**: Subscriber endpoint for marketing integrations.

---

## 🛠️ Tech Stack

### Frontend (`Nuvara_frontend`)
- **Core Framework**: React 19 (JavaScript SPA)
- **Build Tooling**: Vite
- **Styling**: Tailwind CSS & CSS Variables
- **State Management**: Zustand
- **Routing**: React Router DOM (HashRouter / BrowserRouter)
- **Animations**: Framer Motion
- **Internationalization**: i18next & react-i18next
- **Icons**: Lucide React
- **Linter**: Oxlint

### Backend (`Nuvara_backend`)
- **Framework**: PHP 8.2+ / Laravel 12
- **Authentication**: Laravel Sanctum
- **Database**: SQLite / MySQL / PostgreSQL (via Eloquent ORM)
- **Package Manager**: Composer

---

## 📁 Project Architecture

```text
Nuvara/
├── Nuvara_frontend/                 # React 19 Frontend Application
│   ├── public/                      # Static assets (favicons, SVGs)
│   ├── src/
│   │   ├── assets/                  # Images and design resources
│   │   ├── components/
│   │   │   ├── category/            # Category Cards, Filter Sidebars
│   │   │   ├── layout/              # Header, Footer Navigation
│   │   │   ├── product/             # Swatches, Image Galleries, Product Cards
│   │   │   └── ui/                  # Badges, Steppers, Toasts, RatingStars
│   │   ├── locales/                 # JSON i18n translation dictionaries (en, es, bn, ar)
│   │   ├── pages/                   # Cart, Category, Checkout, Dashboard, Home, ProductDetail
│   │   ├── store/                   # Zustand stores (cartStore, localeStore, themeStore, toastStore)
│   │   ├── utils/                   # Mock database fallback & helper functions
│   │   ├── App.jsx                  # Root routing wrapper & layout setup
│   │   ├── index.css                # Global CSS variables & Tailwind config
│   │   └── main.jsx                 # Application entrypoint
│   ├── package.json
│   └── vite.config.js
│
└── Nuvara_backend/                  # Laravel 12 REST API Backend
    ├── app/
    │   ├── Http/Controllers/Api/V1/ # REST API Controllers (Products, Auth, Orders, etc.)
    │   └── Models/                  # Eloquent Models (Product, Category, Order, User, etc.)
    ├── database/
    │   ├── factories/               # Model factories
    │   ├── migrations/              # Database schema migrations
    │   └── seeders/                 # Database seeders
    ├── routes/
    │   ├── api.php                  # API Routes (/api/v1/*)
    │   └── web.php                  # Web routes
    ├── .env.example                 # Environment configuration template
    └── composer.json                # PHP dependency configuration
