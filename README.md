# 🛍️ Laravel E-Commerce Backend API

A comprehensive, feature-rich Laravel-based e-commerce backend application with RESTful API, modular architecture, and advanced features for managing online stores.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Key Features](#-key-features)
- [Technology Stack](#-technology-stack)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Running the Application](#-running-the-application)
- [API Documentation](#-api-documentation)
- [Project Structure](#-project-structure)
- [Key Modules](#-key-modules)
- [Development Workflow](#-development-workflow)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🎯 Overview

This is a production-ready Laravel e-commerce backend application designed to power modern online stores. It provides a complete RESTful API for frontend applications (web, mobile, or SPA) with comprehensive features including product management, order processing, customer management, payment integration, and advanced analytics.

### What Makes This Special?

- **Modular Architecture**: Built with Laravel Modules for scalability
- **Feature Toggle System**: Enable/disable features without code changes
- **Advanced Order Management**: Including incomplete order tracking
- **Multi-Payment Gateway**: Support for multiple payment methods
- **Courier Integration**: Automated shipping with Pathao and other couriers
- **Real-time Analytics**: Google Analytics and Tag Manager integration
- **Event-Driven**: Webhooks and event listeners for extensibility
- **JWT Authentication**: Secure API authentication for customers
- **Role-Based Access**: Spatie permissions for admin users
- **Optimized Performance**: Caching, eager loading, and database optimization

---

## ✨ Key Features

### 🛒 E-Commerce Core
- **Product Management**: Categories, subcategories, child categories, brands, colors, sizes
- **Product Variants**: Multiple variations with different prices and stock
- **Inventory Management**: Stock tracking, low stock alerts
- **Shopping Cart**: Session-based cart with Laravel Shopping Cart package
- **Order Processing**: Complete order lifecycle management
- **Incomplete Orders**: Track abandoned carts and follow up with customers
- **Coupon System**: Discount codes with various conditions
- **Reviews & Ratings**: Customer product reviews with images

### 👥 Customer Management
- **Customer Registration/Login**: JWT-based authentication
- **Customer Profiles**: Profile management and order history
- **Address Management**: Multiple shipping addresses
- **Order Tracking**: Real-time order status tracking
- **Wishlist**: Save products for later

### 💳 Payment & Shipping
- **Multiple Payment Gateways**: ShurjoPay and extensible for others
- **Shipping Zones**: District-based shipping charges
- **Courier Integration**: Pathao API integration
- **Cash on Delivery**: COD support

### 📊 Admin Features
- **Dashboard Analytics**: Sales, orders, revenue statistics
- **Order Management**: Process, update, and track orders
- **Customer Management**: View and manage customer accounts
- **Product Management**: CRUD operations for products
- **Banner Management**: Homepage sliders and promotional banners
- **Campaign Management**: Special offers and campaigns
- **Expense Tracking**: Business expense management
- **Report Generation**: Sales and inventory reports

### 🎨 Frontend Customization
- **Theme Colors**: Dynamic theme color management
- **Banner Categories**: Organize promotional content
- **Page Builder**: Create custom pages
- **SEO Management**: Meta tags, sitemap generation
- **Social Media Integration**: Links and sharing

### 🔧 Advanced Features
- **Feature Toggles**: Enable/disable features via API
- **Event System**: Order placed, status changed, product viewed events
- **Webhook Support**: Send data to external services
- **Email Notifications**: Order confirmations and updates
- **SMS Notifications**: Order alerts via SMS gateway
- **IP Blocking**: Security feature to block malicious IPs
- **Google Tag Manager**: Marketing and analytics integration
- **Multi-language Support**: Localization ready
- **API Versioning**: v1 API with room for future versions

---

## 🛠️ Technology Stack

### Backend Framework
- **Laravel 11.x** - PHP Framework
- **PHP 8.2+** - Programming Language

### Database
- **MySQL** - Primary Database

### Authentication & Authorization
- **JWT (tymon/jwt-auth)** - API Authentication
- **Laravel Sanctum** - Token-based authentication
- **Spatie Laravel Permission** - Role & Permission management

### Key Packages
- **nwidart/laravel-modules** - Modular application structure
- **intervention/image** - Image processing and manipulation
- **spatie/laravel-analytics** - Google Analytics integration
- **spatie/laravel-sitemap** - Automatic sitemap generation
- **anayarojo/shoppingcart** - Shopping cart functionality
- **milon/barcode** - Barcode generation
- **guzzlehttp/guzzle** - HTTP client for API calls
- **brian2694/laravel-toastr** - Flash messages

### Development Tools
- **Laravel Debugbar** - Development debugging
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework
- **Vite** - Frontend build tool

### Frontend Assets
- **Bootstrap 5** - CSS Framework
- **jQuery** - JavaScript library
- **Axios** - HTTP client

---

## 💻 System Requirements

- **PHP**: >= 8.2
- **Composer**: Latest version
- **MySQL**: >= 5.7 or MariaDB >= 10.3
- **Node.js**: >= 16.x (for asset compilation)
- **NPM**: >= 8.x
- **Web Server**: Apache or Nginx
- **PHP Extensions**:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - GD or Imagick

---

## 📦 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd main_project_backend-main
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
# Copy the example environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### 5. Database Setup

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

Seed the database (optional):

```bash
php artisan db:seed
```

### 6. Storage Link

Create symbolic link for storage:

```bash
php artisan storage:link
```

### 7. Build Assets

```bash
npm run build
```

---

## ⚙️ Configuration

### Application Settings

Edit `.env` file for basic configuration:

```env
APP_NAME="Your Store Name"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
```

### Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourstore.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Payment Gateway (ShurjoPay)

Configure in admin panel or `.env`:

```env
SHURJOPAY_USERNAME=your_username
SHURJOPAY_PASSWORD=your_password
SHURJOPAY_PREFIX=your_prefix
```

### Google Analytics

```env
ANALYTICS_VIEW_ID=your_view_id
```

### Queue Configuration

For production, use Redis or database queue:

```env
QUEUE_CONNECTION=database
```

Then run queue worker:

```bash
php artisan queue:work
```

---

## 🚀 Running the Application

### Development Server

#### Quick Start (Recommended)

Double-click the batch file:
```
FIX_AND_START.bat
```

Or run manually:

```bash
php artisan serve
```

Access the application at: `http://127.0.0.1:8000`

#### Test Assets Loading

Visit: `http://127.0.0.1:8000/test-direct.php`

You should see green checkmarks indicating assets are loading correctly.

### Production Server

For production deployment:

1. Set environment to production:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize the application:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

3. Configure your web server (Apache/Nginx) to point to the `public` directory

---

## 📡 API Documentation

### Base URL

```
http://your-domain.com/api/v1
```

### Authentication

Most endpoints require JWT authentication. Include the token in the header:

```
Authorization: Bearer {your-jwt-token}
```

### Key API Endpoints

#### Public Endpoints

```
GET  /api/v1/app-config              - Application configuration
GET  /api/v1/categories              - All categories
GET  /api/v1/products                - All products
GET  /api/v1/product/{slug}          - Single product details
GET  /api/v1/slider                  - Homepage sliders
GET  /api/v1/banner/{id}             - Banners
POST /api/v1/chack-out               - Place order
POST /api/v1/customer/login          - Customer login
POST /api/v1/customer/register       - Customer registration
GET  /api/v1/global-search           - Search products
```

#### Protected Endpoints (Require Authentication)

```
GET  /api/v1/customer/profile        - Customer profile
GET  /api/v1/customer/orders         - Customer orders
POST /api/v1/customer/profile-update - Update profile
POST /api/v1/customer/change-password - Change password
POST /api/v1/customer/logout         - Logout
```

#### Product Endpoints

```
GET  /api/v1/featured-product        - Featured products
GET  /api/v1/latest-product          - New arrivals
GET  /api/v1/popular-product         - Popular products
GET  /api/v1/trending-product        - Trending products
GET  /api/v1/best-selling-product    - Best sellers
GET  /api/v1/category/{id}           - Products by category
GET  /api/v1/products-by-brand/{slug} - Products by brand
POST /api/v1/products/filter         - Filter products
```

#### Incomplete Orders API

```
POST   /api/v1/incomplete-orders              - Create incomplete order
GET    /api/v1/incomplete-orders              - List all incomplete orders
GET    /api/v1/incomplete-orders/{id}         - Get single order
POST   /api/v1/incomplete-orders/{id}/update-status - Update status
POST   /api/v1/incomplete-orders/{id}/add-note - Add admin note
DELETE /api/v1/incomplete-orders/{id}         - Delete order
POST   /api/v1/incomplete-orders/bulk-delete  - Bulk delete
GET    /api/v1/incomplete-orders/statistics   - Get statistics
```

#### Feature Toggles

```
GET /api/v1/feature-toggles           - Get all enabled features
GET /api/v1/feature-toggles/{key}     - Get specific feature
```

#### Theme & Customization

```
GET /api/v1/theme-colors              - Get theme colors
GET /api/v1/page/{slug}               - Get custom page
```

### Detailed API Documentation

For complete API documentation with request/response examples, see:
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Full API reference
- [API_QUICK_REFERENCE.md](API_QUICK_REFERENCE.md) - Quick reference guide
- [FRONTEND_DEVELOPER_API_GUIDE.md](FRONTEND_DEVELOPER_API_GUIDE.md) - Frontend integration guide

---

## 📁 Project Structure

```
main_project_backend-main/
├── app/
│   ├── Console/              # Artisan commands
│   ├── Events/               # Event classes
│   │   ├── AddToCart.php
│   │   ├── OrderPlaced.php
│   │   ├── OrderStatusChanged.php
│   │   └── ProductViewed.php
│   ├── Exceptions/           # Exception handlers
│   ├── Helpers/              # Helper functions
│   │   └── FeatureHelper.php
│   ├── Http/
│   │   ├── Controllers/      # API & Web controllers
│   │   │   └── Api/          # API controllers
│   │   └── Middleware/       # Custom middleware
│   ├── Jobs/                 # Queue jobs
│   │   ├── SendOrderNotifications.php
│   │   └── SendOrderSms.php
│   ├── Listeners/            # Event listeners
│   │   ├── SendOrderPlacedWebhook.php
│   │   └── SendOrderStatusWebhook.php
│   ├── Mail/                 # Mail classes
│   │   └── OrderNotificationMail.php
│   ├── Models/               # Eloquent models (60+ models)
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── Customer.php
│   │   ├── Category.php
│   │   └── ...
│   ├── Observers/            # Model observers
│   ├── Providers/            # Service providers
│   └── Services/             # Business logic services
├── bootstrap/                # Bootstrap files
├── config/                   # Configuration files
│   ├── app.php
│   ├── database.php
│   ├── jwt.php
│   ├── permission.php
│   └── ...
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── Modules/                  # Laravel Modules
│   └── HomePageOne/          # Homepage module
├── public/                   # Public assets
│   ├── backEnd/              # Admin panel assets
│   ├── frontEnd/             # Frontend assets
│   ├── uploads/              # Uploaded files
│   └── index.php             # Entry point
├── resources/
│   ├── css/                  # CSS source files
│   ├── js/                   # JavaScript source files
│   └── views/                # Blade templates
│       ├── frontEnd/
│       └── backEnd/
├── routes/
│   ├── api.php               # API routes
│   ├── web.php               # Web routes
│   ├── channels.php          # Broadcast channels
│   └── console.php           # Console routes
├── storage/                  # Storage directory
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/                    # Test files
│   ├── Feature/
│   └── Unit/
├── .env.example              # Environment example
├── composer.json             # PHP dependencies
├── package.json              # Node dependencies
├���─ artisan                   # Artisan CLI
└── README.md                 # This file
```

---

## 🔑 Key Modules

### Product Management
- **Models**: Product, ProductVariable, ProductImage, ProductPolicy
- **Features**: Variants, images, stock management, pricing
- **API**: Full CRUD operations, filtering, sorting, search

### Order Management
- **Models**: Order, OrderDetails, OrderStatus
- **Features**: Order processing, status tracking, notifications
- **Events**: OrderPlaced, OrderStatusChanged
- **Jobs**: SendOrderNotifications, SendOrderSms

### Customer Management
- **Models**: Customer, CustomerReview, CustomerProductReview
- **Authentication**: JWT-based API authentication
- **Features**: Registration, login, profile, order history

### Incomplete Orders
- **Model**: CheckoutLead
- **Purpose**: Track abandoned carts and incomplete checkouts
- **Features**: Status management, admin notes, follow-up tracking
- **API**: Complete CRUD with statistics

### Category System
- **Models**: Category, Subcategory, Childcategory
- **Features**: Three-level category hierarchy
- **API**: Nested categories with product counts

### Payment Integration
- **Models**: Payment, PaymentGateway
- **Gateways**: ShurjoPay, Cash on Delivery
- **Features**: Multiple payment methods, transaction tracking

### Shipping & Courier
- **Models**: Shipping, ShippingCharge, Courierapi, District
- **Features**: Zone-based shipping, Pathao integration
- **API**: Calculate shipping charges by district

### Marketing & Analytics
- **Models**: Banner, Campaign, CouponCode, EcomPixel, GoogleTagManager
- **Features**: Promotional banners, discount campaigns, tracking pixels
- **Integration**: Google Analytics, Tag Manager

### Feature Toggle System
- **Model**: FeatureToggle
- **Purpose**: Enable/disable features without code deployment
- **API**: Get enabled features for frontend
- **Helper**: `feature_enabled('feature_key')`

---

## 👨‍💻 Development Workflow

### Daily Workflow

```bash
# 1. Pull latest changes
git pull origin main

# 2. Install/update dependencies (if needed)
composer install
npm install

# 3. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 4. Run migrations (if any new)
php artisan migrate

# 5. Start development server
php artisan serve

# 6. In another terminal, watch for asset changes
npm run dev
```

### Code Style

This project uses Laravel Pint for code formatting:

```bash
# Format code
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test
```

### Creating New Features

1. **Create Migration**:
```bash
php artisan make:migration create_table_name
```

2. **Create Model**:
```bash
php artisan make:model ModelName -m
```

3. **Create Controller**:
```bash
php artisan make:controller Api/ControllerName
```

4. **Create Event**:
```bash
php artisan make:event EventName
```

5. **Create Listener**:
```bash
php artisan make:listener ListenerName --event=EventName
```

### Using Feature Toggles

```php
// In your code
if (feature_enabled('new_checkout_flow')) {
    // New checkout logic
} else {
    // Old checkout logic
}

// In API response
$features = FeatureToggle::where('is_enabled', true)->pluck('feature_key');
```

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProductTest.php

# Run with coverage
php artisan test --coverage
```

### Test Structure

```
tests/
├── Feature/              # Feature tests (API, integration)
│   ├── ProductApiTest.php
│   ├── OrderTest.php
│   └── CustomerAuthTest.php
└── Unit/                 # Unit tests (isolated logic)
    ├── ProductTest.php
    └── CartTest.php
```

### Writing Tests

```php
// Example API test
public function test_can_get_products()
{
    $response = $this->getJson('/api/v1/products');
    
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'data' => [
                     '*' => ['id', 'name', 'price']
                 ]
             ]);
}
```

---

## 🚢 Deployment

### Pre-Deployment Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure production database
- [ ] Set up mail server
- [ ] Configure payment gateways
- [ ] Set up SSL certificate
- [ ] Configure queue worker
- [ ] Set up scheduled tasks (cron)
- [ ] Optimize application

### Optimization Commands

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Optimize application
php artisan optimize
```

### Server Configuration

#### Apache (.htaccess)

The project includes `.htaccess` files for Apache. Ensure `mod_rewrite` is enabled.

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Queue Worker (Supervisor)

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/worker.log
```

### Scheduled Tasks (Cron)

Add to crontab:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔧 Troubleshooting

### CSS/JS Not Loading

**Problem**: Assets not loading on local development server

**Solution**:
1. Stop the server (Ctrl+C)
2. Clear all caches:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```
3. Clear browser cache (Ctrl+Shift+Delete)
4. Restart server: `php artisan serve`
5. Hard refresh browser (Ctrl+F5)
6. Test at: `http://127.0.0.1:8000/test-direct.php`

### Port Already in Use

```bash
# Use different port
php artisan serve --port=8001
```

### Database Connection Error

1. Check `.env` database credentials
2. Ensure MySQL is running
3. Test connection:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

### Permission Errors

```bash
# Fix storage permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows - Run as Administrator
icacls storage /grant Users:F /t
icacls bootstrap/cache /grant Users:F /t
```

### JWT Token Issues

```bash
# Regenerate JWT secret
php artisan jwt:secret --force

# Clear config cache
php artisan config:clear
```

### Queue Not Processing

```bash
# Check queue worker is running
php artisan queue:work

# Restart queue worker
php artisan queue:restart

# Check failed jobs
php artisan queue:failed
```

### Migration Errors

```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback all and re-run
php artisan migrate:fresh

# With seeding
php artisan migrate:fresh --seed
```

---

## 📚 Additional Documentation

- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Complete API reference
- [API_QUICK_REFERENCE.md](API_QUICK_REFERENCE.md) - Quick API guide
- [FRONTEND_DEVELOPER_API_GUIDE.md](FRONTEND_DEVELOPER_API_GUIDE.md) - Frontend integration
- [HOW_TO_RUN.md](HOW_TO_RUN.md) - Quick start guide
- [PROJECT_ANALYSIS.md](PROJECT_ANALYSIS.md) - Project structure analysis
- [PATHAO_INTEGRATION_README.md](PATHAO_INTEGRATION_README.md) - Courier integration
- [PERFORMANCE_AUDIT_REPORT.md](PERFORMANCE_AUDIT_REPORT.md) - Performance optimization
- [DEVELOPER_NOTES.md](DEVELOPER_NOTES.md) - Development notes
- [COLLABORATION.md](COLLABORATION.md) - Team collaboration guide

---

## 🤝 Contributing

### How to Contribute

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Use Laravel best practices
- Write meaningful commit messages
- Add tests for new features
- Update documentation

### Pull Request Guidelines

- Describe what the PR does
- Reference any related issues
- Include screenshots for UI changes
- Ensure all tests pass
- Update relevant documentation

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Team & Support

### Development Team

This project is maintained by a dedicated team of developers. For questions or support:

- Check the documentation files
- Review existing issues
- Create a new issue for bugs or feature requests

### Important Notes

- Always use `php artisan serve` for local development
- Access via `http://127.0.0.1:8000` (not localhost)
- Keep the `/public/` prefix in asset paths
- Clear caches after pulling changes
- Test assets loading at `/test-direct.php`

---

## 🎯 Quick Links

- **Start Server**: Run `FIX_AND_START.bat` or `php artisan serve`
- **Test Assets**: http://127.0.0.1:8000/test-direct.php
- **Main App**: http://127.0.0.1:8000
- **API Base**: http://127.0.0.1:8000/api/v1

---

## 📊 Project Statistics

- **Laravel Version**: 11.x
- **PHP Version**: 8.2+
- **Total Models**: 60+
- **API Endpoints**: 100+
- **Modules**: Modular architecture with Laravel Modules
- **Events**: 6 custom events
- **Jobs**: Queue-based background processing
- **Listeners**: Webhook integrations

---

## 🔄 Recent Updates

- ✅ Feature toggle system implemented
- ✅ Incomplete orders API added
- ✅ Theme color management
- ✅ Pathao courier integration
- ✅ Performance optimizations
- ✅ Enhanced API documentation
- ✅ Event-driven architecture
- ✅ Webhook support

---

## 🚀 Getting Started Checklist

- [ ] Clone repository
- [ ] Run `composer install`
- [ ] Run `npm install`
- [ ] Copy `.env.example` to `.env`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Generate JWT secret: `php artisan jwt:secret`
- [ ] Configure database in `.env`
- [ ] Run migrations: `php artisan migrate`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Start server: `php artisan serve`
- [ ] Test at: http://127.0.0.1:8000/test-direct.php
- [ ] Access app: http://127.0.0.1:8000

---

**Built with ❤️ using Laravel**

For detailed setup instructions, see [HOW_TO_RUN.md](HOW_TO_RUN.md)

For API integration, see [FRONTEND_DEVELOPER_API_GUIDE.md](FRONTEND_DEVELOPER_API_GUIDE.md)
