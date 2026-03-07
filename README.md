# 🛍️ Khadyobitan — E-Commerce Platform

A full-stack e-commerce platform built with **Laravel 11** (backend) and **React 19 + Inertia.js** (frontend). Designed for organic & natural food stores with bilingual support (English & বাংলা).

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![React](https://img.shields.io/badge/React-19.x-blue.svg)](https://react.dev)
[![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)](https://php.net)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-blue.svg)](https://typescriptlang.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

> **Repository:** [github.com/Ahasan39/khadyobitan_newversion](https://github.com/Ahasan39/khadyobitan_newversion)

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Tech Stack](#-tech-stack)
- [Key Features](#-key-features)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Running the Application](#-running-the-application)
- [Project Structure](#-project-structure)
- [API Reference](#-api-reference)
- [Frontend (React + Inertia.js)](#-frontend-react--inertiajs)
- [Admin Panel](#-admin-panel)
- [Development Workflow](#-development-workflow)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🎯 Overview

Khadyobitan is a production-ready e-commerce platform for online stores. It combines a Laravel backend with a modern React SPA frontend via Inertia.js — meaning **no separate API calls** are needed for the storefront. Data flows directly from Laravel controllers to React components.

### Architecture Highlights

- **Inertia.js Bridge** — React components receive data directly from Laravel controllers (no REST API needed for frontend)
- **REST API** — Full v1 API available for mobile apps or external integrations
- **Modular Design** — Laravel Modules (`nwidart/laravel-modules`) for extensibility
- **Event-Driven** — 6 custom events with webhook listeners for order/cart/product tracking
- **Queue Jobs** — Background processing for email & SMS notifications

---

## 🛠️ Tech Stack

### Backend
| Technology | Purpose |
|---|---|
| Laravel 11.x | PHP Framework |
| PHP 8.2+ | Runtime |
| MySQL 5.7+ | Database |
| JWT (tymon/jwt-auth) | API Authentication |
| Laravel Sanctum | Token Authentication |
| Spatie Permission | Role & Permission Management |
| Inertia.js (Server) | Laravel-to-React Bridge |
| nwidart/laravel-modules | Modular Architecture |

### Frontend
| Technology | Purpose |
|---|---|
| React 19 | UI Library |
| TypeScript 5.x | Type Safety |
| Inertia.js (Client) | SPA without API calls |
| Tailwind CSS 4.x | Styling |
| Radix UI / shadcn/ui | 48+ UI Components |
| Zustand | State Management (Cart) |
| Framer Motion | Animations |
| i18next | Bilingual (EN / বাংলা) |
| React Hook Form + Zod | Form Handling & Validation |
| TanStack React Query | Server State Management |
| Recharts | Charts & Analytics |
| Vite 5 | Build Tool |

### Key Laravel Packages
| Package | Purpose |
|---|---|
| `intervention/image` | Image processing |
| `spatie/laravel-analytics` | Google Analytics |
| `spatie/laravel-sitemap` | Auto sitemap generation |
| `anayarojo/shoppingcart` | Cart functionality |
| `milon/barcode` | Barcode generation |
| `shurjopayv2/laravel8` | Payment gateway |
| `brian2694/laravel-toastr` | Flash notifications |
| `barryvdh/laravel-debugbar` | Dev debugging |

---

## ✨ Key Features

### 🛒 Storefront (React)
- 24 page components (Home, Shop, Product Detail, Cart, Checkout, Account, Blog, etc.)
- Responsive design with mobile bottom navigation
- Bilingual UI (English & বাংলা) via i18next
- Product filtering, sorting, search
- Shopping cart & wishlist (Zustand store)
- Order tracking
- WhatsApp integration button
- Back-to-top, promo popups, page transitions

### 📦 Product Management
- Categories → Subcategories → Child categories (3-level hierarchy)
- Brands, colors, sizes, tags
- Product variants with separate pricing/stock
- Multiple product images
- Stock alerts & inventory tracking
- Barcode generation
- Product duplication
- Product policies

### 🧾 Order System
- Complete order lifecycle (pending → processing → shipped → delivered)
- Incomplete order / abandoned cart tracking (`CheckoutLead`)
- Admin order creation
- Bulk courier assignment (Pathao integration)
- Order notifications (email + SMS)
- Invoice generation
- Fraud checker
- Order reports & analytics

### 💳 Payments & Shipping
- ShurjoPay gateway integration
- bKash payment support
- Cash on Delivery
- District-based shipping charges
- Pathao courier API integration

### 👤 Customer Management
- JWT-based API authentication
- Session-based web authentication
- Customer registration with OTP verification
- Profile management & order history
- Forgot password flow
- IP blocking for security

### 📊 Admin Panel (Blade-based)
- Dashboard with sales analytics
- 42+ admin controllers covering all CRUD operations
- Role-based access control (Spatie)
- User & permission management
- Expense tracking & reporting
- Campaign & coupon management
- Banner & page builder
- Theme color customization
- Feature toggle system
- Google Tag Manager & pixel tracking
- Business settings & WhatsApp configuration
- Visitor analytics
- Update/license management system

### 🔧 Advanced
- **6 Events**: `AddToCart`, `OrderNow`, `OrderPlaced`, `OrderStatus`, `OrderStatusChanged`, `ProductViewed`
- **5 Webhook Listeners**: Auto-send data to external services on events
- **2 Queue Jobs**: `SendOrderNotifications`, `SendOrderSms`
- **6 Model Observers**: Auto-actions on Brand, Campaign, Category, CreatePage, Product, Subcategory changes
- **Feature Toggles**: Enable/disable features without deployment
- **4 Artisan Commands**: Cache management, sitemap generation, source encryption/decryption
- **Response Caching Service** for performance
- **Docker configs** for PHP 7.4, 8.0, 8.1, 8.2

---

## 💻 System Requirements

- **PHP** >= 8.2 (with BCMath, Ctype, Fileinfo, GD/Imagick, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML)
- **Composer** >= 2.x
- **MySQL** >= 5.7 or MariaDB >= 10.3
- **Node.js** >= 16.x & NPM >= 8.x
- **Web Server**: Apache or Nginx

---

## 📦 Installation

```bash
# 1. Clone
git clone https://github.com/Ahasan39/khadyobitan_newversion.git
cd khadyobitan_newversion

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Environment setup
copy .env.example .env
php artisan key:generate
php artisan jwt:secret

# 5. Configure database in .env
# DB_DATABASE=your_db  DB_USERNAME=root  DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Seed database (optional)
php artisan db:seed

# 8. Create storage link
php artisan storage:link

# 9. Build frontend assets
npm run build
```

---

## 🚀 Running the Application

### Development

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Vite dev server (hot reload)
npm run dev
```

Or use the batch file:
```
FIX_AND_START.bat
```

Access at: **http://127.0.0.1:8000**

### Production

```bash
APP_ENV=production
APP_DEBUG=false

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
composer install --optimize-autoloader --no-dev
npm run build
```

---

## 📁 Project Structure

```
├── app/
│   ├── Console/Commands/       # 4 Artisan commands (cache, sitemap, encrypt/decrypt)
│   ├── Events/                 # 6 events (AddToCart, OrderPlaced, ProductViewed, etc.)
│   ├── Helpers/                # FeatureHelper (feature_enabled())
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # 42 admin controllers
│   │   │   ├── Api/            # 10 API controllers (v1)
│   │   │   └── Frontend/       # 18 frontend controllers (Inertia + legacy)
│   │   └── Middleware/         # 19 middleware (JWT, IP filter, Inertia, CSRF, etc.)
│   ├── Jobs/                   # 2 queue jobs (email & SMS notifications)
│   ├── Listeners/              # 5 webhook listeners
│   ├── Mail/                   # OrderNotificationMail
│   ├── Models/                 # 61 Eloquent models
│   ├── Observers/              # 6 model observers
│   ├── Providers/              # 6 service providers
│   └── Services/               # ResponseCacheService
├── config/                     # 30+ config files (jwt, shurjopay, permission, etc.)
├── database/
│   ├── migrations/             # 80+ migrations
│   └── seeders/
├── docker/                     # Docker configs (PHP 7.4, 8.0, 8.1, 8.2)
├── Modules/
│   └── HomePageOne/            # Homepage module (Laravel Modules)
├── public/
│   ├── backEnd/                # Admin panel assets
│   ├── frontEnd/               # Legacy frontend assets
│   └── uploads/                # User uploads
├── resources/
│   ├── js/                     # React frontend (Inertia.js)
│   │   ├── Components/         # 11 shared components + 48 UI components (shadcn)
│   │   │   ├── layout/         # Header, Footer, MainLayout, MobileBottomNav
│   │   │   └── ui/             # shadcn/ui component library
│   │   ├── Pages/              # 24 page components
│   │   ├── hooks/              # Custom React hooks
│   │   ├── i18n/locales/       # en.json, bn.json translations
│   │   ├── store/              # Zustand cart store
│   │   └── types/              # TypeScript type definitions
│   └── views/                  # Blade templates (admin panel + emails)
├── routes/
│   ├── api.php                 # REST API v1 routes
│   ├── inertia.php             # React frontend routes (Inertia.js)
│   └── web.php                 # Admin + legacy web routes
├── composer.json               # PHP dependencies (17 prod + 7 dev packages)
├── package.json                # Node dependencies (40+ packages)
├── vite.config.ts              # Vite + React SWC + Laravel plugin
├── tailwind.config.ts          # Tailwind CSS configuration
└── tsconfig.json               # TypeScript configuration
```

---

## 📡 API Reference

**Base URL:** `http://your-domain.com/api/v1`

### Public Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/app-config` | App configuration |
| GET | `/categories` | All categories |
| GET | `/sub-categories` | All subcategories |
| GET | `/brands` | All brands |
| GET | `/products` | All products |
| GET | `/product/{slug}` | Single product |
| GET | `/featured-product` | Featured products |
| GET | `/latest-product` | New arrivals |
| GET | `/popular-product` | Popular products |
| GET | `/trending-product` | Trending products |
| GET | `/best-selling-product` | Best sellers |
| GET | `/category/{id}` | Products by category |
| GET | `/products-by-brand/{slug}` | Products by brand |
| GET | `/products-by-tag/{tag}` | Products by tag |
| POST | `/products/filter` | Filter products |
| GET | `/global-search` | Search products |
| GET | `/slider` | Homepage sliders |
| GET | `/banner/{id}` | Banners |
| GET | `/offers` | Active offers |
| GET | `/theme-colors` | Theme colors |
| GET | `/feature-toggles` | Enabled features |
| GET | `/page/{slug}` | Custom page |
| POST | `/chack-out` | Place order |
| POST | `/customer/login` | Customer login |
| POST | `/customer/register` | Customer registration |

### Protected Endpoints (JWT Required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/customer/profile` | Customer profile |
| GET | `/customer/orders` | Customer orders |
| POST | `/customer/profile-update` | Update profile |
| POST | `/customer/change-password` | Change password |
| POST | `/customer/logout` | Logout |

### Incomplete Orders API

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/incomplete-orders` | Create |
| GET | `/incomplete-orders` | List all |
| GET | `/incomplete-orders/statistics` | Statistics |
| GET | `/incomplete-orders/{id}` | Get one |
| POST | `/incomplete-orders/{id}/update-status` | Update status |
| DELETE | `/incomplete-orders/{id}` | Delete |

> Full API docs: [API_DOCUMENTATION.md](API_DOCUMENTATION.md) · [API_QUICK_REFERENCE.md](API_QUICK_REFERENCE.md)

---

## 🎨 Frontend (React + Inertia.js)

The storefront is a React 19 SPA served via Inertia.js. No separate API calls — data flows directly from Laravel controllers.

### Data Flow
```
Browser Request → Laravel Route → Controller → Inertia::render('PageName', $data) → React Component
```

### Pages (24 total)
`Index` · `Shop` · `ProductDetail` · `Cart` · `Wishlist` · `Checkout` · `Login` · `Register` · `Account` · `Orders` · `OrderDetail` · `OrderConfirmation` · `OrderTracking` · `ProfileEdit` · `ChangePassword` · `Blog` · `BlogDetail` · `About` · `Contact` · `FAQ` · `Privacy` · `Terms` · `ShippingPolicy` · `ReturnPolicy`

### UI Component Library (shadcn/ui — 48 components)
Accordion · Alert · Avatar · Badge · Breadcrumb · Button · Calendar · Card · Carousel · Chart · Checkbox · Command · Dialog · Drawer · Dropdown · Form · Input · Label · Navigation Menu · Pagination · Popover · Progress · Radio · Scroll Area · Select · Separator · Sheet · Sidebar · Skeleton · Slider · Switch · Table · Tabs · Textarea · Toast · Toggle · Tooltip · and more...

### Inertia Controllers (11)
`InertiaHomeController` · `InertiaShopController` · `InertiaProductController` · `InertiaCartController` · `InertiaCheckoutController` · `InertiaAuthController` · `InertiaAccountController` · `InertiaOrderTrackingController` · `InertiaWishlistController` · `InertiaPageController` · `InertiaBlogController`

---

## 🔐 Admin Panel

Access at: **http://your-domain.com/admin/login**

Blade-based admin panel with Bootstrap 5. Features include:

- **Dashboard** — Sales stats, order overview, revenue charts
- **Products** — CRUD, variants, images, stock, barcode, duplication
- **Orders** — Process, invoice, courier assign, bulk operations, reports
- **Categories** — 3-level hierarchy management
- **Customers** — Manage accounts, IP blocking, admin login-as
- **Campaigns** — Special offers with product assignment
- **Coupons** — Discount codes with conditions
- **Banners** — Homepage sliders & promotional content
- **Pages** — Custom page builder
- **Reviews** — Product & customer review moderation
- **Shipping** — District-based charges, courier API config
- **Expenses** — Category-based expense tracking
- **Settings** — General, social media, contact, theme colors, business settings
- **Users & Roles** — Spatie permission-based access control
- **Feature Toggles** — Enable/disable features dynamically
- **Analytics** — Visitor reports, Google Analytics integration
- **Notifications** — Email & SMS order notification settings

---

## 👨‍💻 Development Workflow

```bash
# Pull latest & update dependencies
git pull origin main
composer install
npm install

# Clear caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear

# Run new migrations
php artisan migrate

# Start dev servers
php artisan serve        # Terminal 1
npm run dev              # Terminal 2
```

### Code Style
```bash
./vendor/bin/pint        # Laravel Pint (PSR-12)
./vendor/bin/pint --test # Check only
```

### Feature Toggle Usage
```php
// In PHP
if (feature_enabled('new_checkout_flow')) {
    // new logic
}

// Via API
GET /api/v1/feature-toggles
```

---

## 🧪 Testing

```bash
php artisan test                              # Run all tests
php artisan test tests/Feature/ExampleTest.php # Specific test
php artisan test --coverage                    # With coverage
```

---

## 🚢 Deployment

### Checklist
- [ ] `APP_ENV=production`, `APP_DEBUG=false`
- [ ] Configure production database, mail, payment gateways
- [ ] SSL certificate
- [ ] Run optimization commands (see above)
- [ ] Set up queue worker (Supervisor)
- [ ] Set up cron: `* * * * * php artisan schedule:run >> /dev/null 2>&1`
- [ ] Point web server to `public/` directory

### Nginx Config
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/project/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

---

## 🔧 Troubleshooting

| Problem | Solution |
|---------|----------|
| Assets not loading | `php artisan config:clear && php artisan cache:clear` then hard refresh (Ctrl+F5) |
| Port in use | `php artisan serve --port=8001` |
| DB connection error | Check `.env` credentials, ensure MySQL is running |
| Permission errors (Linux) | `chmod -R 775 storage bootstrap/cache` |
| JWT token issues | `php artisan jwt:secret --force && php artisan config:clear` |
| Queue not processing | `php artisan queue:work` or `php artisan queue:restart` |
| Migration errors | `php artisan migrate:rollback` or `php artisan migrate:fresh --seed` |

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Follow PSR-12 standards (use Laravel Pint)
4. Write tests for new features
5. Commit: `git commit -m 'Add your feature'`
6. Push & open a Pull Request

---

## 📚 Additional Documentation

| Document | Description |
|----------|-------------|
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Complete API reference |
| [API_QUICK_REFERENCE.md](API_QUICK_REFERENCE.md) | Quick API guide |
| [HOW_TO_RUN.md](HOW_TO_RUN.md) | Quick start guide |
| [FRONTEND_INTEGRATION_GUIDE.md](FRONTEND_INTEGRATION_GUIDE.md) | Inertia.js integration |
| [PATHAO_INTEGRATION_README.md](PATHAO_INTEGRATION_README.md) | Courier integration |
| [PERFORMANCE_AUDIT_REPORT.md](PERFORMANCE_AUDIT_REPORT.md) | Performance optimization |
| [DEVELOPER_NOTES.md](DEVELOPER_NOTES.md) | Development notes |
| [COLLABORATION.md](COLLABORATION.md) | Team collaboration guide |
| [CONTRIBUTORS.md](CONTRIBUTORS.md) | Contributors |

---

## 📊 Project Statistics

| Metric | Count |
|--------|-------|
| Eloquent Models | 61 |
| Database Migrations | 80+ |
| Admin Controllers | 42 |
| API Controllers | 10 |
| Frontend Controllers (Inertia) | 11 |
| React Pages | 24 |
| UI Components (shadcn) | 48 |
| Custom Events | 6 |
| Webhook Listeners | 5 |
| Queue Jobs | 2 |
| Model Observers | 6 |
| Middleware | 19 |
| Artisan Commands | 4 |
| NPM Dependencies | 40+ |
| Composer Packages | 24 |

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Author

**Ahasan39**
- 🌐 [ahasan39.github.io](https://ahasan39.github.io/)
- 💻 [@Ahasan39](https://github.com/Ahasan39)
- 📧 imamul190071@gmail.com

---

**Built with Laravel 11 + React 19 + Inertia.js + Tailwind CSS**
