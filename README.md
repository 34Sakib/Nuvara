# 🛍️ Nuvara Full-Stack E-Commerce Platform

---

## 🎨 Nuvara Frontend (React 19 SPA)

<p align="center">
  <img width="100%" alt="Nuvara Frontend Preview" src="https://github.com/user-attachments/assets/ae5cafa5-5f3d-42b3-811d-6a72a14f5d2e" />
</p>

**Nuvara Frontend** is a high-fidelity, responsive client built with **React 19**, **Vite**, and **Tailwind CSS**. It features full dark/light theme switching, multi-language internationalization (i18n) with Right-to-Left (RTL) support, Zustand state management, wishlist controls, and multi-step checkout workflows.

---

## ⚙️ Nuvara Backend (Laravel 12 REST API)

<p align="center">
  <img width="100%" alt="Nuvara Backend REST API & Database Architecture" src="https://github.com/user-attachments/assets/deaab408-7342-466a-be20-20420724c67e" />
</p>

**Nuvara Backend** powers the platform with a high-performance RESTful API built on **Laravel 12** and **PHP 8.2+**. It handles user authentication via Laravel Sanctum, database ORM models (Products, Orders, Wishlist, Addresses, Reviews, Coupons), category filtering, search, and checkout processing.

---

## 📝 Platform Overview

**Nuvara** is a premium full-stack e-commerce application designed to deliver an exceptional shopping experience and robust backend functionality.

- **Frontend Client**: React 19, Vite, Tailwind CSS, Zustand, Framer Motion, and i18next (with native RTL support for Arabic, Bengali, Spanish, and English).
- **Backend API**: Laravel 12, PHP 8.2+, Laravel Sanctum token authentication, and SQLite/MySQL Eloquent ORM.

---

## ✨ System Features

### 🎨 Frontend Features (`Nuvara_frontend`)
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

### ⚙️ Backend Features (`Nuvara_backend`)
- 🔐 **Sanctum Authentication**: Secure token-based API authentication for user registration, login, profile management, and logouts (`/api/v1/auth/*`).
- 📦 **Product Catalog Engine**: Complete database schemas and controllers for Products, Product Variants, Categories, Brands, Product Images, and Banners.
- 🔍 **Search & Filtering API**: RESTful query parameters to filter products by category, price ranges, ratings, and keyword search (`/api/v1/products`).
- 🛒 **Order Management & Checkout API**: Endpoints supporting both guest checkouts and authenticated user orders (`/api/v1/checkout`).
- 📍 **Address Book API**: Full CRUD capabilities for user delivery and billing addresses (`/api/v1/addresses`).
- ⭐️ **Product Reviews & Ratings API**: Submit and fetch customer product reviews and star ratings (`/api/v1/reviews`).
- 📧 **Newsletter Subscriptions API**: Subscriber endpoint for marketing integrations (`/api/v1/newsletter`).

---

## 🛠️ Tech Stack

### 🎨 Frontend (`Nuvara_frontend`)
- **Core Framework**: React 19 (JavaScript SPA)
- **Build Tooling**: Vite
- **Styling**: Tailwind CSS & CSS Variables
- **State Management**: Zustand
- **Routing**: React Router DOM (HashRouter / BrowserRouter)
- **Animations**: Framer Motion
- **Internationalization**: i18next & react-i18next
- **Icons**: Lucide React
- **Linter**: Oxlint

### ⚙️ Backend (`Nuvara_backend`)
- **Core Framework**: PHP 8.2+ / Laravel 12
- **API Authentication**: Laravel Sanctum
- **Database Layer**: SQLite / MySQL / PostgreSQL (via Eloquent ORM)
- **Package Management**: Composer

---

## 📁 Project Architecture

```text
Nuvara/
├── 🎨 Nuvara_frontend/             # React 19 Frontend Application
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
└── ⚙️ Nuvara_backend/              # Laravel 12 REST API Backend
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
```

---

## 🔌 API Endpoints Reference (`/api/v1`)

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/v1/products` | Fetch all products / filter / search | Public |
| `GET` | `/api/v1/products/{slug}` | Get single product details | Public |
| `GET` | `/api/v1/categories` | Get category hierarchy | Public |
| `POST` | `/api/v1/coupons/validate` | Validate discount promo codes | Public |
| `POST` | `/api/v1/checkout` | Process guest or user order checkout | Public |
| `POST` | `/api/v1/newsletter` | Subscribe email to newsletter | Public |
| `POST` | `/api/v1/auth/register` | Register new user account | Public |
| `POST` | `/api/v1/auth/login` | Authenticate user & receive Sanctum token | Public |
| `GET` | `/api/v1/me` | Fetch authenticated user profile | Protected (Sanctum Token) |
| `PUT` | `/api/v1/me` | Update authenticated profile | Protected (Sanctum Token) |
| `GET` | `/api/v1/addresses` | List user shipping addresses | Protected (Sanctum Token) |
| `POST` | `/api/v1/addresses` | Add new shipping address | Protected (Sanctum Token) |
| `POST` | `/api/v1/wishlist/{product}`| Toggle product in user wishlist | Protected (Sanctum Token) |
| `POST` | `/api/v1/reviews` | Submit product review & rating | Protected (Sanctum Token) |

---

## 🚀 Getting Started

### Prerequisites

Ensure you have the following installed on your local machine:
- **Node.js** (v18+ recommended) & `npm`
- **PHP** (v8.2+ required)
- **Composer** (v2+)

---

### 1. Setting Up the Backend (`Nuvara_backend`)

Navigate into the backend directory:

```bash
cd Nuvara_backend
```

Install PHP dependencies:
```bash
composer install
```

Set up the environment file:
```bash
copy .env.example .env
# On macOS/Linux: cp .env.example .env
```

Generate the application encryption key:
```bash
php artisan key:generate
```

Run database migrations and seeders:
```bash
php artisan migrate --seed
```

Start the Laravel development server:
```bash
php artisan serve
```
The backend REST API will run at `http://127.0.0.1:8000`.

---

### 2. Setting Up the Frontend (`Nuvara_frontend`)

In a separate terminal, navigate into the frontend directory:

```bash
cd Nuvara_frontend
```

Install JavaScript dependencies:
```bash
npm install
```

*(Optional)* Set the API base URL in `.env`:
```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api/v1
```

Start the Vite development server:
```bash
npm run dev
```
The frontend client will run at `http://localhost:5173`.

---

## 📦 Production Deployment

### 🎨 Frontend Production Build
```bash
cd Nuvara_frontend
npm run build
```
Builds static production assets in `Nuvara_frontend/dist/`.

### ⚙️ Backend Production Setup
```bash
cd Nuvara_backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📄 License

This project is open-sourced and available under the [MIT License](LICENSE).
