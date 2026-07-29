# 🛍️ Nuvara Frontend

<p align="center">
  <img width="100%" alt="Nuvara Hero & Grid Showcase" src="https://github.com/user-attachments/assets/deaab408-7342-466a-be20-20420724c67e" />
</p>

<p align="center">
  <img width="100%" alt="Nuvara Landing Page Preview" src="https://github.com/user-attachments/assets/ae5cafa5-5f3d-42b3-811d-6a72a14f5d2e" />
</p>

**Nuvara Frontend** is a high-fidelity modern e-commerce user interface built with **React 19**, **Vite**, and **Tailwind CSS**. It connects seamlessly to the Nuvara Laravel REST API while supporting full offline mock data fallbacks.

---

## ✨ Features

- 🌓 **Dynamic Theme Toggle**: Dark and light mode support with curated CSS color variables.
- 🌐 **Internationalization (i18n)**: English (`en`), Spanish (`es`), Bengali (`bn`), and Arabic (`ar`).
- ↩️ **RTL Layout Support**: Automatic document direction switching (`ltr` ↔ `rtl`) with mirrored icon orientation for Arabic.
- 🛒 **Shopping Cart System**: Powered by Zustand state management with real-time quantity adjustments.
- 🎟️ **Promo Engine**: Client & server coupon code validation (`NUVARA20`, `WELCOME10`, `FREESHIP`).
- ❤️ **Wishlist & Customer Dashboard**: Track favorite items, saved delivery addresses, profile details, and past orders.
- 💳 **Checkout Flow**: Multi-step checkout experience with validation and simulated payment processing.
- 🔔 **Toast Notifications**: Interactive notification popups for cart updates and system alerts.

---

## 🛠️ Tech Stack

- **Framework**: React 19 (JavaScript SPA)
- **Build Tool**: Vite
- **Styling**: Tailwind CSS & CSS Variables
- **State Management**: Zustand
- **Routing**: React Router DOM
- **Animations**: Framer Motion
- **i18n**: i18next & react-i18next
- **Icons**: Lucide React
- **Linter**: Oxlint

---

## 🚀 Quick Start

### 1. Installation

```bash
npm install
```

### 2. Configure Environment

Create a `.env` file in the root of `Nuvara_frontend`:

```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api/v1
```

### 3. Run Locally

```bash
npm run dev
```

The app will start at `http://localhost:5173`.
