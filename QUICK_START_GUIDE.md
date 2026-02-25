# 🚀 Quick Start Guide - React Frontend Development

## 📋 TL;DR - What You Need to Know

### **Your Backend**
- ✅ Laravel E-commerce API (fully functional)
- ✅ 60+ API endpoints (all public, no auth needed for most)
- ✅ Complete product, category, order, customer management
- ✅ Admin panel in Blade.php (separate from frontend)

### **Your Goal**
Create 3 separate React frontends:
1. **Organic Products Store** (natural, eco-friendly)
2. **Fashion Brand Store** (luxury, modern)
3. **Electronics Store** (tech-focused, professional)

### **API Base URL**
```
Production: https://your-domain.com/api/v1
Local: http://127.0.0.1:8000/api/v1
```

---

## ⚡ Quick Setup (30 Minutes)

### **Step 1: Create React Project**
```bash
# Choose one method:

# Method A: Create React App (easier)
npx create-react-app my-store
cd my-store

# Method B: Vite (faster, recommended)
npm create vite@latest my-store -- --template react
cd my-store
npm install
```

### **Step 2: Install Essential Packages**
```bash
npm install axios react-router-dom @tanstack/react-query zustand react-hook-form react-hot-toast
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### **Step 3: Configure Tailwind**
```javascript
// tailwind.config.js
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#4A7C2C', // Change per store
      },
    },
  },
  plugins: [],
}
```

```css
/* src/index.css */
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### **Step 4: Create API Service**
```javascript
// src/services/api.js
import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:8000/api/v1';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add JWT token if available
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;

// Product services
export const getProducts = () => api.get('/all-products');
export const getProduct = (slug) => api.get(`/product/${slug}`);
export const getCategories = () => api.get('/categories');
export const getFeaturedProducts = () => api.get('/featured-product');
export const searchProducts = (keyword) => api.get('/global-search', { params: { keyword } });
```

### **Step 5: Create Basic Pages**
```javascript
// src/App.jsx
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
import Shop from './pages/Shop';
import ProductDetail from './pages/ProductDetail';
import Cart from './pages/Cart';
import Checkout from './pages/Checkout';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/shop" element={<Shop />} />
        <Route path="/product/:slug" element={<ProductDetail />} />
        <Route path="/cart" element={<Cart />} />
        <Route path="/checkout" element={<Checkout />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
```

### **Step 6: Create Cart Store**
```javascript
// src/store/useCartStore.js
import { create } from 'zustand';
import { persist } from 'zustand/middleware';

export const useCartStore = create(
  persist(
    (set, get) => ({
      items: [],
      
      addItem: (product, quantity = 1) => {
        const items = get().items;
        const existingItem = items.find(item => item.id === product.id);
        
        if (existingItem) {
          set({
            items: items.map(item =>
              item.id === product.id
                ? { ...item, quantity: item.quantity + quantity }
                : item
            ),
          });
        } else {
          set({ items: [...items, { ...product, quantity }] });
        }
      },
      
      removeItem: (productId) => {
        set({ items: get().items.filter(item => item.id !== productId) });
      },
      
      updateQuantity: (productId, quantity) => {
        set({
          items: get().items.map(item =>
            item.id === productId ? { ...item, quantity } : item
          ),
        });
      },
      
      clearCart: () => set({ items: [] }),
      
      getTotal: () => {
        return get().items.reduce(
          (total, item) => total + (item.new_price * item.quantity),
          0
        );
      },
    }),
    { name: 'cart-storage' }
  )
);
```

### **Step 7: Create Homepage**
```javascript
// src/pages/Home.jsx
import { useEffect, useState } from 'react';
import { getProducts, getCategories } from '../services/api';
import ProductCard from '../components/ProductCard';

export default function Home() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  
  useEffect(() => {
    // Fetch products
    getProducts().then(res => {
      setProducts(res.data.data || []);
    });
    
    // Fetch categories
    getCategories().then(res => {
      setCategories(res.data.data || []);
    });
  }, []);
  
  return (
    <div className="min-h-screen">
      {/* Hero Section */}
      <section className="bg-primary text-white py-20">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-5xl font-bold mb-4">Welcome to Our Store</h1>
          <p className="text-xl mb-8">Discover amazing products</p>
          <button className="bg-white text-primary px-8 py-3 rounded-lg font-semibold">
            Shop Now
          </button>
        </div>
      </section>
      
      {/* Categories */}
      <section className="py-16">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold mb-8">Shop by Category</h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
            {categories.map(category => (
              <div key={category.id} className="text-center">
                <img
                  src={category.image}
                  alt={category.name}
                  className="w-full h-48 object-cover rounded-lg mb-4"
                />
                <h3 className="font-semibold">{category.name}</h3>
              </div>
            ))}
          </div>
        </div>
      </section>
      
      {/* Featured Products */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold mb-8">Featured Products</h2>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            {products.slice(0, 8).map(product => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
```

### **Step 8: Create Product Card**
```javascript
// src/components/ProductCard.jsx
import { Link } from 'react-router-dom';
import { useCartStore } from '../store/useCartStore';
import toast from 'react-hot-toast';

export default function ProductCard({ product }) {
  const addItem = useCartStore(state => state.addItem);
  
  const handleAddToCart = () => {
    addItem(product, 1);
    toast.success('Added to cart!');
  };
  
  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden">
      <Link to={`/product/${product.slug}`}>
        <img
          src={product.image?.image || '/placeholder.jpg'}
          alt={product.name}
          className="w-full h-64 object-cover hover:scale-105 transition"
        />
      </Link>
      
      <div className="p-4">
        <Link to={`/product/${product.slug}`}>
          <h3 className="font-semibold mb-2 hover:text-primary">
            {product.name}
          </h3>
        </Link>
        
        <div className="flex items-center gap-2 mb-3">
          {product.old_price > product.new_price && (
            <span className="text-gray-400 line-through">
              ৳{product.old_price}
            </span>
          )}
          <span className="text-xl font-bold text-primary">
            ৳{product.new_price}
          </span>
        </div>
        
        <button
          onClick={handleAddToCart}
          className="w-full bg-primary text-white py-2 rounded hover:bg-primary-dark"
        >
          Add to Cart
        </button>
      </div>
    </div>
  );
}
```

### **Step 9: Run Your Project**
```bash
npm run dev
# or
npm start
```

Visit: `http://localhost:5173` (Vite) or `http://localhost:3000` (CRA)

---

## 🎯 Most Important API Endpoints

### **Products**
```javascript
// Get all products
GET /api/v1/all-products

// Get single product
GET /api/v1/product/{slug}

// Featured products
GET /api/v1/featured-product

// Search
GET /api/v1/global-search?keyword=shirt
```

### **Categories**
```javascript
// All categories
GET /api/v1/categories

// Products by category
GET /api/v1/category/{id}
```

### **Cart & Checkout**
```javascript
// Place order
POST /api/v1/chack-out
{
  "name": "John Doe",
  "phone": "01712345678",
  "address": "Dhaka",
  "products": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 1200
    }
  ],
  "total_amount": 2400
}

// Save incomplete order (auto-save)
POST /api/v1/incomplete-orders
{
  "name": "John",
  "phone": "01712345678",
  "address": "Dhaka",
  "cart_data": [...],
  "total_amount": 2400
}
```

### **Customer Auth**
```javascript
// Login
POST /api/v1/customer/login
{
  "email": "john@example.com",
  "password": "password123"
}

// Register
POST /api/v1/customer/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "01712345678",
  "password": "password123"
}
```

---

## 🎨 Customization for Each Store

### **Organic Store**
```javascript
// tailwind.config.js
theme: {
  extend: {
    colors: {
      primary: '#2D5016',    // Dark green
      secondary: '#4A7C2C',  // Light green
      accent: '#8BC34A',     // Lime
    },
  },
}
```

### **Fashion Store**
```javascript
theme: {
  extend: {
    colors: {
      primary: '#000000',    // Black
      secondary: '#D4AF37',  // Gold
      accent: '#FFFFFF',     // White
    },
  },
}
```

### **Electronics Store**
```javascript
theme: {
  extend: {
    colors: {
      primary: '#0A1929',    // Dark blue
      secondary: '#00B4D8',  // Electric blue
      accent: '#90E0EF',     // Light blue
    },
  },
}
```

---

## 📦 Essential Features Checklist

### **Must Have (Week 1-2)**
- [ ] Homepage with products
- [ ] Product listing page
- [ ] Product detail page
- [ ] Add to cart functionality
- [ ] Cart page
- [ ] Basic checkout form

### **Should Have (Week 3-4)**
- [ ] Search functionality
- [ ] Category filtering
- [ ] Product variants (size, color)
- [ ] Customer authentication
- [ ] Order tracking
- [ ] Responsive design

### **Nice to Have (Week 5-6)**
- [ ] Wishlist
- [ ] Product reviews
- [ ] Related products
- [ ] Recently viewed
- [ ] Auto-save checkout
- [ ] Animations

---

## 🐛 Common Issues & Solutions

### **Issue: CORS Error**
```javascript
// Backend: Add to config/cors.php
'paths' => ['api/*'],
'allowed_origins' => ['http://localhost:3000', 'http://localhost:5173'],
```

### **Issue: Images Not Loading**
```javascript
// Use full URL for images
const imageUrl = `https://your-domain.com${product.image}`;
```

### **Issue: Cart Not Persisting**
```javascript
// Already handled by Zustand persist middleware
// Make sure you're using the persist wrapper
```

---

## 📚 Next Steps

1. **Read Full Documentation**: `COMPLETE_PROJECT_ANALYSIS_AND_FRONTEND_PLAN.md`
2. **Use Lovable Prompts**: Copy prompts from the full doc
3. **Test API Endpoints**: Use Postman or browser
4. **Start with One Store**: Build organic store first
5. **Clone for Others**: Duplicate and customize

---

## 🆘 Need Help?

### **API Documentation**
- `API_DOCUMENTATION.md` - Complete API docs
- `FRONTEND_DEVELOPER_API_GUIDE.md` - Frontend-focused guide
- `API_QUICK_REFERENCE.md` - Quick reference

### **Project Info**
- `PROJECT_ANALYSIS.md` - Project structure analysis
- `README.md` - General project info

### **Testing**
```bash
# Test API endpoint
curl http://127.0.0.1:8000/api/v1/all-products

# Or visit in browser
http://127.0.0.1:8000/api/v1/all-products
```

---

## ✅ Success Checklist

Before going live:
- [ ] All pages responsive (mobile, tablet, desktop)
- [ ] Cart functionality working
- [ ] Checkout process complete
- [ ] Customer can login/register
- [ ] Orders are saved to backend
- [ ] Images loading properly
- [ ] Search working
- [ ] Filters working
- [ ] Performance optimized (< 3s load time)
- [ ] SEO meta tags added

---

**Quick Start Version**: 1.0  
**Last Updated**: January 2026  
**Estimated Time to First Working Version**: 2-3 hours  

Good luck! 🚀
