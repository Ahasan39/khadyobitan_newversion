# 🚀 Quick Start - Frontend Integration

## TL;DR - Fast Integration Steps

### 1. Install Inertia (5 minutes)

```bash
# Backend
composer require inertiajs/inertia-laravel
php artisan inertia:middleware

# Frontend
npm install @inertiajs/react
```

### 2. Create Root Template

Create `resources/views/app.blade.php`:

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name') }}</title>
    @routes
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
```

### 3. Update app.tsx

Replace `resources/js/app.tsx`:

```typescript
import './bootstrap';
import '../css/app.css';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});
```

### 4. Copy Your Frontend

```bash
# Copy components
xcopy /E /I "khadyobitan_frontend\src" "resources\js"

# Copy configs
copy "khadyobitan_frontend\tailwind.config.ts" "."
copy "khadyobitan_frontend\postcss.config.js" "."
```

### 5. Update Routes

Edit `routes/web.php`:

```php
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index', [
        'products' => Product::latest()->take(8)->get(),
        'categories' => Category::all(),
    ]);
});
```

### 6. Convert Pages

Rename `pages/Index.tsx` to `Pages/Index.tsx` and update:

```typescript
import { Head } from '@inertiajs/react';

export default function Index({ products, categories }) {
    return (
        <>
            <Head title="Home" />
            {/* Your existing JSX */}
        </>
    );
}
```

### 7. Update Links

Replace React Router links:

```typescript
// Before
import { Link } from 'react-router-dom';
<Link to="/shop">Shop</Link>

// After
import { Link } from '@inertiajs/react';
<Link href="/shop">Shop</Link>
```

### 8. Run

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

---

## 🔄 Common Conversions

### Navigation

```typescript
// React Router → Inertia
import { Link } from '@inertiajs/react';

<Link href="/products">Products</Link>
<Link href={`/product/${slug}`}>View</Link>
```

### Forms

```typescript
import { useForm } from '@inertiajs/react';

const { data, setData, post, processing, errors } = useForm({
    name: '',
    email: '',
});

const submit = (e) => {
    e.preventDefault();
    post('/submit');
};
```

### Data Fetching

```typescript
// No need for useEffect + fetch!
// Data comes from controller as props

export default function Products({ products }) {
    // products is already available!
    return <div>{products.map(...)}</div>
}
```

### Redirects

```typescript
import { router } from '@inertiajs/react';

// Navigate
router.visit('/products');

// POST request
router.post('/cart/add', { product_id: 1 });

// DELETE request
router.delete(`/cart/${id}`);
```

---

## 📦 Controller Example

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Shop', [
            'products' => Product::with('images')->paginate(12),
            'categories' => Category::all(),
        ]);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        return Inertia::render('ProductDetail', [
            'product' => $product,
            'related' => Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get(),
        ]);
    }
}
```

---

## 🎯 Key Differences

| Feature | React Router (API) | Inertia.js |
|---------|-------------------|------------|
| Navigation | `<Link to="">` | `<Link href="">` |
| Data Fetching | `useEffect + fetch` | Props from controller |
| Forms | `axios.post()` | `useForm().post()` |
| Auth | JWT tokens | Laravel sessions |
| CSRF | Manual headers | Automatic |
| SEO | Client-side only | Server-rendered |

---

## ✅ Checklist

- [ ] Install Inertia packages
- [ ] Create app.blade.php
- [ ] Update app.tsx
- [ ] Copy frontend files
- [ ] Update vite.config.ts
- [ ] Convert pages to Inertia pages
- [ ] Replace React Router with Inertia Links
- [ ] Update forms to use Inertia
- [ ] Create controllers
- [ ] Update routes
- [ ] Test everything
- [ ] Build for production

---

## 🚨 Common Issues

### 1. "Inertia version mismatch"
```bash
npm install @inertiajs/react@latest
composer require inertiajs/inertia-laravel
```

### 2. "Cannot find module '@/components'"
Update `vite.config.ts`:
```typescript
resolve: {
    alias: {
        '@': path.resolve(__dirname, './resources/js'),
    },
}
```

### 3. "CSRF token mismatch"
Add to `app.tsx`:
```typescript
import axios from 'axios';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

---

## 📚 Resources

- Full Guide: `FRONTEND_INTEGRATION_GUIDE.md`
- Inertia Docs: https://inertiajs.com
- Laravel Docs: https://laravel.com/docs

---

**Ready to integrate? Run:**

```bash
SETUP_FRONTEND_INTEGRATION.bat
```

Or follow the manual steps above!
