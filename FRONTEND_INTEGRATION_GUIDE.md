# 🔗 Frontend Integration Guide - React + Laravel (Without API)

## Overview

This guide explains how to integrate your existing React frontend (`khadyobitan_frontend`) with the Laravel backend **without using API calls**. We'll use **Inertia.js** which allows React to work seamlessly with Laravel's routing and controllers.

---

## 🎯 Integration Strategy

### Why Inertia.js?

- ✅ **No API needed** - Data passed directly from Laravel controllers to React components
- ✅ **Laravel routing** - Use Laravel's powerful routing system
- ✅ **Server-side rendering ready** - Better SEO
- ✅ **Shared authentication** - Use Laravel's session-based auth
- ✅ **CSRF protection** - Built-in security
- ✅ **Keep your existing React components** - Minimal changes needed

---

## 📦 Step 1: Install Inertia.js

### Backend (Laravel)

```bash
# Install Inertia server-side adapter
composer require inertiajs/inertia-laravel

# Publish Inertia middleware
php artisan inertia:middleware
```

### Frontend (React)

```bash
# Navigate to frontend directory
cd khadyobitan_frontend

# Install Inertia client-side adapter
npm install @inertiajs/react
```

---

## 🔧 Step 2: Configure Laravel

### 2.1 Register Inertia Middleware

Edit `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... existing middleware
        \App\Http\Middleware\HandleInertiaRequests::class,
    ],
];
```

### 2.2 Create Inertia Root Template

Create `resources/views/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
```

### 2.3 Update HandleInertiaRequests Middleware

Edit `app/Http/Middleware/HandleInertiaRequests.php`:

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'csrf_token' => csrf_token(),
            'app' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
            ],
        ]);
    }
}
```

---

## 🎨 Step 3: Move Frontend to Laravel

### 3.1 Copy React Components

```bash
# Copy your frontend src to Laravel resources
xcopy /E /I "khadyobitan_frontend\src" "resources\js"

# Copy Tailwind config
copy "khadyobitan_frontend\tailwind.config.ts" "tailwind.config.ts"

# Copy PostCSS config
copy "khadyobitan_frontend\postcss.config.js" "postcss.config.js"

# Copy TypeScript config
copy "khadyobitan_frontend\tsconfig.json" "tsconfig.json"
```

### 3.2 Update Vite Configuration

Edit `vite.config.js` (rename to `vite.config.ts`):

```typescript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react-swc';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
});
```

### 3.3 Update package.json

Merge dependencies from `khadyobitan_frontend/package.json` into Laravel's `package.json`:

```json
{
  "private": true,
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "preview": "vite preview"
  },
  "dependencies": {
    "@inertiajs/react": "^1.0.0",
    "@hookform/resolvers": "^3.10.0",
    "@radix-ui/react-accordion": "^1.2.11",
    "@radix-ui/react-dialog": "^1.1.14",
    "@radix-ui/react-dropdown-menu": "^2.1.15",
    "@radix-ui/react-label": "^2.1.7",
    "@radix-ui/react-select": "^2.2.5",
    "@radix-ui/react-slot": "^1.2.3",
    "@radix-ui/react-toast": "^1.2.14",
    "@tanstack/react-query": "^5.83.0",
    "class-variance-authority": "^0.7.1",
    "clsx": "^2.1.1",
    "framer-motion": "^12.33.0",
    "lucide-react": "^0.462.0",
    "react": "^18.3.1",
    "react-dom": "^18.3.1",
    "react-hook-form": "^7.61.1",
    "react-router-dom": "^6.30.1",
    "tailwind-merge": "^2.6.0",
    "tailwindcss-animate": "^1.0.7",
    "zod": "^3.25.76",
    "zustand": "^5.0.11"
  },
  "devDependencies": {
    "@types/node": "^22.16.5",
    "@types/react": "^18.3.23",
    "@types/react-dom": "^18.3.7",
    "@vitejs/plugin-react-swc": "^3.11.0",
    "autoprefixer": "^10.4.21",
    "laravel-vite-plugin": "^0.7.2",
    "postcss": "^8.5.6",
    "tailwindcss": "^3.4.17",
    "typescript": "^5.8.3",
    "vite": "^5.4.19"
  }
}
```

---

## 🔄 Step 4: Update React App for Inertia

### 4.1 Create New app.tsx

Create `resources/js/app.tsx`:

```typescript
import './bootstrap';
import '../css/app.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { TooltipProvider } from '@/components/ui/tooltip';
import { Toaster } from '@/components/ui/toaster';
import { Toaster as Sonner } from '@/components/ui/sonner';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const queryClient = new QueryClient();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <QueryClientProvider client={queryClient}>
                <TooltipProvider>
                    <App {...props} />
                    <Toaster />
                    <Sonner />
                </TooltipProvider>
            </QueryClientProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});
```

### 4.2 Create Layout Component

Create `resources/js/Layouts/MainLayout.tsx`:

```typescript
import { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import Header from '@/components/layout/Header';
import Footer from '@/components/layout/Footer';
import MobileBottomNav from '@/components/layout/MobileBottomNav';
import WhatsAppButton from '@/components/WhatsAppButton';
import BackToTop from '@/components/BackToTop';

interface MainLayoutProps {
    children: ReactNode;
    title?: string;
}

export default function MainLayout({ children, title }: MainLayoutProps) {
    return (
        <>
            <Head title={title} />
            <Header />
            <main className="min-h-screen">
                {children}
            </main>
            <Footer />
            <MobileBottomNav />
            <WhatsAppButton />
            <BackToTop />
            <div className="h-16 lg:hidden" />
        </>
    );
}
```

### 4.3 Convert Pages to Inertia Pages

Example: `resources/js/Pages/Index.tsx`:

```typescript
import { Head } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';
import HeroSection from '@/components/home/HeroSection';
import CategorySection from '@/components/home/CategorySection';
import FeaturedProducts from '@/components/home/FeaturedProducts';

interface IndexProps {
    sliders: any[];
    categories: any[];
    featuredProducts: any[];
    banners: any[];
}

export default function Index({ sliders, categories, featuredProducts, banners }: IndexProps) {
    return (
        <MainLayout title="Home">
            <HeroSection sliders={sliders} />
            <CategorySection categories={categories} />
            <FeaturedProducts products={featuredProducts} />
        </MainLayout>
    );
}
```

---

## 🛣️ Step 5: Update Laravel Routes

Edit `routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('category');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

// Static Pages
Route::get('/about', fn() => Inertia::render('About'))->name('about');
Route::get('/contact', fn() => Inertia::render('Contact'))->name('contact');
Route::get('/faq', fn() => Inertia::render('FAQ'))->name('faq');

// Customer Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => Inertia::render('Login'))->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth:customer')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
```

---

## 🎮 Step 6: Create Controllers

### HomeController

Create `app/Http/Controllers/Frontend/HomeController.php`:

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'sliders' => Banner::where('status', 1)
                ->where('banner_category_id', 1)
                ->orderBy('position')
                ->get(),
            
            'categories' => Category::where('status', 1)
                ->withCount('products')
                ->orderBy('position')
                ->take(12)
                ->get(),
            
            'featuredProducts' => Product::where('status', 1)
                ->where('featured', 1)
                ->with(['category', 'images'])
                ->take(8)
                ->get(),
            
            'banners' => Banner::where('status', 1)
                ->where('banner_category_id', 2)
                ->get(),
        ];

        return Inertia::render('Index', $data);
    }
}
```

### ProductController

Create `app/Http/Controllers/Frontend/ProductController.php`:

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 1)
            ->with(['category', 'images', 'brand']);

        // Filters
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->brand) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->min_price) {
            $query->where('selling_price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('selling_price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->sort ?? 'latest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);

        return Inertia::render('Shop', [
            'products' => $products,
            'categories' => Category::where('status', 1)->get(),
            'filters' => $request->only(['category', 'brand', 'min_price', 'max_price', 'sort']),
        ]);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->with(['category', 'subcategory', 'brand', 'images', 'variables', 'reviews'])
            ->firstOrFail();

        // Increment views
        $product->increment('views');

        // Related products
        $relatedProducts = Product::where('status', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images'])
            ->take(8)
            ->get();

        return Inertia::render('ProductDetail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
```

### CartController

Create `app/Http/Controllers/Frontend/CartController.php`:

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cart;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::content();
        $cartTotal = Cart::total();
        $cartCount = Cart::count();

        return Inertia::render('Cart', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'qty' => 'required|integer|min:1',
        ]);

        Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'options' => [
                'image' => $request->image,
                'size' => $request->size,
                'color' => $request->color,
            ]
        ]);

        return redirect()->back()->with('message', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        Cart::update($request->rowId, $request->qty);
        
        return redirect()->back()->with('message', 'Cart updated!');
    }

    public function remove($rowId)
    {
        Cart::remove($rowId);
        
        return redirect()->back()->with('message', 'Item removed from cart!');
    }
}
```

---

## 🔐 Step 7: Update Components for Inertia

### Update Navigation Links

Instead of `<a href>` or `<Link>` from react-router, use Inertia's `Link`:

```typescript
import { Link } from '@inertiajs/react';

// Before (React Router)
<Link to="/shop">Shop</Link>

// After (Inertia)
<Link href="/shop">Shop</Link>
```

### Update Forms

Use Inertia's form helper:

```typescript
import { useForm } from '@inertiajs/react';

function LoginForm() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <form onSubmit={submit}>
            <input
                type="email"
                value={data.email}
                onChange={e => setData('email', e.target.value)}
            />
            {errors.email && <span>{errors.email}</span>}
            
            <input
                type="password"
                value={data.password}
                onChange={e => setData('password', e.target.value)}
            />
            {errors.password && <span>{errors.password}</span>}
            
            <button type="submit" disabled={processing}>
                Login
            </button>
        </form>
    );
}
```

---

## 📦 Step 8: Install Dependencies & Build

```bash
# Install all dependencies
npm install

# Install Inertia
npm install @inertiajs/react

# Build assets
npm run build

# For development
npm run dev
```

---

## 🚀 Step 9: Run the Application

```bash
# Start Laravel server
php artisan serve

# In another terminal, start Vite
npm run dev

# Access at
http://127.0.0.1:8000
```

---

## 🎯 Benefits of This Approach

### ✅ No API Calls Needed
- Data passed directly from controllers to React components
- No need for axios/fetch requests
- No CORS issues

### ✅ Laravel Features Work Seamlessly
- Session-based authentication
- CSRF protection
- Form validation
- Flash messages
- Middleware

### ✅ Better Performance
- Server-side rendering ready
- Automatic code splitting
- Optimized asset loading

### ✅ SEO Friendly
- Server-rendered initial page
- Better for search engines

---

## 📝 Example: Complete Flow

### 1. User visits homepage

```
Browser → Laravel Route → HomeController → Inertia::render('Index', $data) → React Index Component
```

### 2. User clicks product

```typescript
<Link href={`/product/${product.slug}`}>View Product</Link>
```

```
Browser → Laravel Route → ProductController::show() → Inertia::render('ProductDetail', $product) → React ProductDetail Component
```

### 3. User adds to cart

```typescript
import { router } from '@inertiajs/react';

const addToCart = () => {
    router.post('/cart/add', {
        id: product.id,
        name: product.name,
        price: product.price,
        qty: quantity,
    });
};
```

```
Browser → Laravel Route → CartController::add() → Redirect back with message
```

---

## 🔄 Migration Checklist

- [ ] Install Inertia.js (Laravel & React)
- [ ] Create app.blade.php root template
- [ ] Update vite.config.ts
- [ ] Merge package.json dependencies
- [ ] Copy frontend components to resources/js
- [ ] Create MainLayout component
- [ ] Convert pages to Inertia pages
- [ ] Update routes in web.php
- [ ] Create frontend controllers
- [ ] Replace React Router Links with Inertia Links
- [ ] Update forms to use Inertia form helper
- [ ] Test all pages and functionality
- [ ] Build and deploy

---

## 🐛 Troubleshooting

### Issue: Vite not finding components

**Solution**: Check alias in vite.config.ts:
```typescript
resolve: {
    alias: {
        '@': path.resolve(__dirname, './resources/js'),
    },
},
```

### Issue: TypeScript errors

**Solution**: Update tsconfig.json:
```json
{
  "compilerOptions": {
    "baseUrl": ".",
    "paths": {
      "@/*": ["resources/js/*"]
    }
  }
}
```

### Issue: CSS not loading

**Solution**: Ensure app.css is imported in app.tsx:
```typescript
import '../css/app.css';
```

---

## 📚 Additional Resources

- [Inertia.js Documentation](https://inertiajs.com/)
- [Laravel Inertia Adapter](https://github.com/inertiajs/inertia-laravel)
- [React Inertia Adapter](https://github.com/inertiajs/inertia-react)

---

## 🎉 Next Steps

1. Follow the steps above to integrate your frontend
2. Test all pages and functionality
3. Update any API calls to use Inertia
4. Deploy to production

Your React frontend will now work seamlessly with Laravel backend without any API calls!
