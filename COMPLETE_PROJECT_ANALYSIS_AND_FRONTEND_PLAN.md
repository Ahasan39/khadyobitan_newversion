# 🎯 Complete Project Analysis & React Frontend Development Plan

**Date**: January 2026  
**Project**: Laravel E-commerce Backend with React Frontend Integration  
**Purpose**: Create 3 separate React-based public websites (Organic Products, Fashion Brand, Electronics) using existing Laravel API

---

## 📊 PART 1: COMPLETE PROJECT ANALYSIS

### 🏗️ **Project Architecture Overview**

#### **Backend Technology Stack**
- **Framework**: Laravel 9.x/10.x
- **Language**: PHP 8.x
- **Database**: MySQL
- **Authentication**: JWT (JSON Web Tokens) + Laravel Sanctum
- **Admin Panel**: Blade.php templates (Server-side rendering)
- **API**: RESTful API (v1) with comprehensive endpoints

#### **Current Structure**
```
main_project_backend-main/
├── app/
│   ├── Http/Controllers/Api/          # API Controllers
│   ├── Models/                        # Database Models (60+ models)
│   ├── Events/                        # Event system
│   ├── Listeners/                     # Event listeners
│   └── Services/                      # Business logic
├── routes/
│   ├── api.php                        # API routes (PUBLIC)
│   └── web.php                        # Admin panel routes
├── resources/views/                   # Blade templates (Admin)
├── public/                            # Public assets
│   ├── frontEnd/                      # Frontend assets
│   ├── backEnd/                       # Admin assets
│   └── uploads/                       # Product images, etc.
├── database/migrations/               # 80+ migration files
└── config/                            # Configuration files
```

---

### 🗄️ **Database Schema Analysis**

#### **Core E-commerce Tables**

1. **Products System**
   - `products` - Main product table
   - `product_images` - Multiple images per product
   - `product_variables` - Size/color variations with stock
   - `product_policies` - Product-specific policies
   - `product_tag` - Product tagging system
   - `reviews` - Product reviews

2. **Category System**
   - `categories` - Main categories
   - `subcategories` - Second level
   - `childcategories` - Third level (nested)
   - `brands` - Product brands
   - `tags` - Tagging system

3. **Order Management**
   - `orders` - Main orders
   - `order_details` - Order line items
   - `order_statuses` - Status tracking
   - `shippings` - Shipping information
   - `payments` - Payment records
   - `checkout_leads` - Incomplete orders (NEW)

4. **Customer System**
   - `customers` - Customer accounts
   - `customer_reviews` - Customer testimonials
   - `customer_product_reviews` - Product reviews

5. **Marketing & Sales**
   - `campaigns` - Marketing campaigns
   - `offers` - Special offers
   - `offer_product` - Products in offers
   - `coupon_codes` - Discount coupons
   - `banners` - Homepage banners
   - `banner_categories` - Banner organization

6. **Configuration**
   - `general_settings` - Site settings
   - `business_settings` - Business config
   - `theme_settings` - Theme customization
   - `theme_colors` - Color schemes
   - `shipping_charges` - Shipping rates
   - `districts` - Location-based shipping

7. **Advanced Features**
   - `ecom_pixels` - Facebook/Google pixels
   - `google_tag_managers` - GTM integration
   - `feature_toggles` - Feature flags
   - `visits` - Analytics tracking
   - `whatsapp_settings` - WhatsApp integration
   - `sms_gateways` - SMS notifications
   - `courierapis` - Courier integration

---

### 🔌 **API Endpoints Analysis**

#### **Public Endpoints (No Authentication Required)**

##### **1. Configuration & Settings**
```
GET /api/v1/app-config              - App configuration
GET /api/v1/siteinfo                - Site information
GET /api/v1/contactinfo             - Contact details
GET /api/v1/social-media            - Social media links
GET /api/v1/theme-colors            - Theme color scheme
GET /api/v1/feature-toggles         - Enabled features
GET /api/v1/tag-manager/manage      - Google Tag Manager
GET /api/v1/notice                  - Site notices
```

##### **2. Product Catalog**
```
GET /api/v1/all-products            - All products (paginated)
GET /api/v1/single-product/{id}     - Product by ID
GET /api/v1/product/{slug}          - Product by slug
GET /api/v1/featured-product        - Featured products
GET /api/v1/latest-product          - New arrivals
GET /api/v1/popular-product         - Popular products
GET /api/v1/trending-product        - Trending products
GET /api/v1/best-selling-product    - Best sellers
GET /api/v1/hotdeal-product         - Hot deals
GET /api/v1/product-stock           - In-stock products
GET /api/v1/product-stock-out       - Out-of-stock products
GET /api/v1/products-by-tag/{tag}   - Products by tag
```

##### **3. Categories & Navigation**
```
GET /api/v1/categories              - All categories
GET /api/v1/category/{id}           - Products by category
GET /api/v1/sub-categories          - Subcategories
GET /api/v1/products-by-subcategory/{slug}
GET /api/v1/child-categories        - Child categories
GET /api/v1/products-by-childcategory/{slug}
GET /api/v1/brands                  - All brands
GET /api/v1/products-by-brand/{slug}
```

##### **4. Filtering & Search**
```
GET /api/v1/global-search           - Global search
GET /api/v1/filter-byCategory/{id}  - Filter by category
GET /api/v1/products-range          - Price range filter
GET /api/v1/products-sort           - Sort products
POST /api/v1/products/filter        - Advanced filtering
```

##### **5. Marketing & Promotions**
```
GET /api/v1/slider                  - Homepage sliders
GET /api/v1/banner/{id}             - Banners
GET /api/v1/offers                  - All offers
GET /api/v1/offers/{id}             - Offer products
GET /api/v1/coupon                  - Coupon codes
GET /api/v1/campaigns               - Campaigns
```

##### **6. Reviews & Testimonials**
```
GET /api/v1/reviews                 - Product reviews
GET /api/v1/image-review            - Customer reviews with images
POST /api/v1/customer-review/store  - Submit review
POST /api/v1/product/review/store   - Submit product review
```

##### **7. Checkout & Orders**
```
POST /api/v1/chack-out              - Place order
GET /api/v1/shipping-charge         - Calculate shipping
GET /api/v1/getDistrict             - Get districts
GET /api/v1/districts/{name}        - District details
POST /api/v1/customer/coupon        - Apply coupon
GET /api/v1/customer/order-track/result - Track order
```

##### **8. Incomplete Orders (Lead Capture)**
```
POST /api/v1/incomplete-orders      - Save incomplete order
GET /api/v1/incomplete-orders       - List all (admin)
GET /api/v1/incomplete-orders/statistics
GET /api/v1/incomplete-orders/{id}
POST /api/v1/incomplete-orders/{id}/update-status
POST /api/v1/incomplete-orders/{id}/add-note
POST /api/v1/incomplete-orders/{id}/mark-contacted
DELETE /api/v1/incomplete-orders/{id}
POST /api/v1/incomplete-orders/bulk-delete
POST /api/v1/incomplete-orders/bulk-update-status
```

##### **9. Customer Authentication**
```
POST /api/v1/customer/login         - Customer login
POST /api/v1/customer/register      - Customer registration
POST /api/v1/customer/verify        - Verify account
GET /api/v1/customer/forgot-password
POST /api/v1/customer/forgot-verify
POST /api/v1/customer/forgot-password/store
```

##### **10. Content Pages**
```
GET /api/v1/page/{slug}             - Dynamic pages
POST /api/v1/user-message           - Contact form
GET /api/v1/footer-menu-left        - Footer links
GET /api/v1/footer-menu-right       - Footer links
```

##### **11. Product Attributes**
```
GET /api/v1/colors                  - Available colors
GET /api/v1/sizes                   - Available sizes
```

#### **Protected Endpoints (JWT Authentication Required)**
```
GET /api/v1/customer/profile        - Customer profile
GET /api/v1/customer/login-check    - Check login status
POST /api/v1/customer/logout        - Logout
POST /api/v1/customer/change-password
POST /api/v1/customer/profile-update
GET /api/v1/customer/orders         - Order history
```

---

### 🎨 **Product Data Structure**

#### **Product Object Example**
```json
{
  "id": 1,
  "name": "Premium Organic Cotton T-Shirt",
  "slug": "premium-organic-cotton-t-shirt",
  "category_id": 5,
  "subcategory_id": 12,
  "childcategory_id": 25,
  "brand_id": 8,
  "purchase_price": 500.00,
  "old_price": 1500.00,
  "new_price": 1200.00,
  "stock": 150,
  "description": "High-quality organic cotton...",
  "short_description": "Comfortable and eco-friendly",
  "status": 1,
  "topsale": 1,
  "bestsale": 1,
  "trending": 1,
  "featured": 1,
  "new_arrival": 1,
  "warranty": "6 months",
  "shipping_charge": 60.00,
  "shipping_title": "Standard Delivery",
  "meta_title": "Buy Organic Cotton T-Shirt",
  "meta_description": "...",
  "tags": ["organic", "cotton", "eco-friendly"],
  "images": [
    {
      "id": 1,
      "image": "/uploads/products/tshirt-1.jpg"
    },
    {
      "id": 2,
      "image": "/uploads/products/tshirt-2.jpg"
    }
  ],
  "variables": [
    {
      "id": 1,
      "size": "M",
      "color": "Blue",
      "stock": 50,
      "price": 1200.00,
      "image": "/uploads/products/tshirt-blue-m.jpg"
    },
    {
      "id": 2,
      "size": "L",
      "color": "Red",
      "stock": 30,
      "price": 1200.00,
      "image": "/uploads/products/tshirt-red-l.jpg"
    }
  ],
  "category": {
    "id": 5,
    "name": "Clothing",
    "slug": "clothing"
  },
  "brand": {
    "id": 8,
    "name": "EcoWear",
    "slug": "ecowear"
  },
  "reviews": [
    {
      "id": 1,
      "customer_name": "John Doe",
      "rating": 5,
      "comment": "Excellent quality!",
      "created_at": "2026-01-15"
    }
  ],
  "policies": [
    {
      "id": 1,
      "title": "Return Policy",
      "description": "30-day return guarantee"
    }
  ]
}
```

---

### 🔐 **Authentication Flow**

#### **Customer Authentication (JWT)**
```javascript
// 1. Register
POST /api/v1/customer/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "01712345678",
  "password": "password123",
  "password_confirmation": "password123"
}

// 2. Login
POST /api/v1/customer/login
{
  "email": "john@example.com",
  "password": "password123"
}

// Response
{
  "success": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "customer": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}

// 3. Use token in subsequent requests
Headers: {
  "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

---

## 🎯 PART 2: STEP-BY-STEP FRONTEND DEVELOPMENT PLAN

### **Phase 1: Project Setup & Architecture (Week 1)**

#### **Step 1.1: Create React Project Structure**
```bash
# Create main frontend directory
mkdir frontend-projects
cd frontend-projects

# Create 3 separate projects
npx create-react-app organic-store
npx create-react-app fashion-brand
npx create-react-app electronics-store

# Or use Vite (recommended for better performance)
npm create vite@latest organic-store -- --template react
npm create vite@latest fashion-brand -- --template react
npm create vite@latest electronics-store -- --template react
```

#### **Step 1.2: Install Common Dependencies**
```bash
# For each project, install:
npm install axios react-router-dom
npm install @tanstack/react-query
npm install zustand
npm install react-hook-form
npm install react-hot-toast
npm install swiper
npm install framer-motion
npm install @headlessui/react
npm install @heroicons/react

# Tailwind CSS
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

#### **Step 1.3: Create Shared API Service Layer**
```javascript
// src/services/api.js
import axios from 'axios';

const API_BASE_URL = 'https://your-domain.com/api/v1';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add JWT token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
```

#### **Step 1.4: Create Folder Structure**
```
src/
├── components/
│   ├── common/              # Shared components
│   │   ├── Header.jsx
│   │   ├── Footer.jsx
│   │   ├── Navbar.jsx
│   │   ├── ProductCard.jsx
│   │   ├── CategoryCard.jsx
│   │   └── LoadingSpinner.jsx
│   ├── home/                # Homepage components
│   │   ├── HeroSlider.jsx
│   │   ├── FeaturedProducts.jsx
│   │   ├── CategorySection.jsx
│   │   └── OfferBanner.jsx
│   ├── product/             # Product components
│   │   ├── ProductList.jsx
│   │   ├── ProductDetail.jsx
│   │   ├── ProductGallery.jsx
│   │   └── ProductReviews.jsx
│   ├── cart/                # Cart components
│   │   ├── CartSidebar.jsx
│   │   ├── CartItem.jsx
│   │   └── CartSummary.jsx
│   └── checkout/            # Checkout components
│       ├── CheckoutForm.jsx
│       ├── ShippingForm.jsx
│       └── PaymentForm.jsx
├── pages/
│   ├── Home.jsx
│   ├── Shop.jsx
│   ├── ProductDetails.jsx
│   ├── Cart.jsx
│   ├── Checkout.jsx
│   ├── Account.jsx
│   ├── OrderTracking.jsx
│   └── About.jsx
├── services/
│   ├── api.js               # Axios instance
│   ├── productService.js    # Product APIs
│   ├── categoryService.js   # Category APIs
│   ├── orderService.js      # Order APIs
│   ├── authService.js       # Authentication
│   └── cartService.js       # Cart management
├── store/
│   ├── useCartStore.js      # Cart state (Zustand)
│   ├── useAuthStore.js      # Auth state
│   └── useConfigStore.js    # App config
├── hooks/
│   ├── useProducts.js       # Product queries
│   ├── useCategories.js     # Category queries
│   └── useCart.js           # Cart logic
├── utils/
│   ├── helpers.js           # Helper functions
│   ├── constants.js         # Constants
│   └── validators.js        # Form validation
└── styles/
    └── tailwind.css         # Tailwind imports
```

---

### **Phase 2: Core API Integration (Week 2)**

#### **Step 2.1: Create API Service Files**

**productService.js**
```javascript
import api from './api';

export const productService = {
  // Get all products
  getAllProducts: (params) => 
    api.get('/all-products', { params }),
  
  // Get single product
  getProduct: (slug) => 
    api.get(`/product/${slug}`),
  
  // Get featured products
  getFeaturedProducts: () => 
    api.get('/featured-product'),
  
  // Get products by category
  getProductsByCategory: (categoryId) => 
    api.get(`/category/${categoryId}`),
  
  // Search products
  searchProducts: (keyword) => 
    api.get('/global-search', { params: { keyword } }),
  
  // Filter products
  filterProducts: (filters) => 
    api.post('/products/filter', filters),
};
```

**categoryService.js**
```javascript
import api from './api';

export const categoryService = {
  getCategories: () => api.get('/categories'),
  getSubcategories: () => api.get('/sub-categories'),
  getBrands: () => api.get('/brands'),
};
```

**orderService.js**
```javascript
import api from './api';

export const orderService = {
  // Place order
  placeOrder: (orderData) => 
    api.post('/chack-out', orderData),
  
  // Calculate shipping
  getShippingCharge: (district) => 
    api.get('/shipping-charge', { params: { district } }),
  
  // Track order
  trackOrder: (orderId) => 
    api.get('/customer/order-track/result', { params: { orderId } }),
  
  // Save incomplete order
  saveIncompleteOrder: (data) => 
    api.post('/incomplete-orders', data),
};
```

**authService.js**
```javascript
import api from './api';

export const authService = {
  login: (credentials) => 
    api.post('/customer/login', credentials),
  
  register: (userData) => 
    api.post('/customer/register', userData),
  
  logout: () => 
    api.post('/customer/logout'),
  
  getProfile: () => 
    api.get('/customer/profile'),
  
  updateProfile: (data) => 
    api.post('/customer/profile-update', data),
};
```

#### **Step 2.2: Create React Query Hooks**

**useProducts.js**
```javascript
import { useQuery } from '@tanstack/react-query';
import { productService } from '../services/productService';

export const useProducts = (params) => {
  return useQuery({
    queryKey: ['products', params],
    queryFn: () => productService.getAllProducts(params),
  });
};

export const useProduct = (slug) => {
  return useQuery({
    queryKey: ['product', slug],
    queryFn: () => productService.getProduct(slug),
    enabled: !!slug,
  });
};

export const useFeaturedProducts = () => {
  return useQuery({
    queryKey: ['featured-products'],
    queryFn: () => productService.getFeaturedProducts(),
  });
};
```

#### **Step 2.3: Create Zustand Stores**

**useCartStore.js**
```javascript
import { create } from 'zustand';
import { persist } from 'zustand/middleware';

export const useCartStore = create(
  persist(
    (set, get) => ({
      items: [],
      
      addItem: (product, quantity = 1, variant = null) => {
        const items = get().items;
        const existingItem = items.find(
          item => item.id === product.id && 
          JSON.stringify(item.variant) === JSON.stringify(variant)
        );
        
        if (existingItem) {
          set({
            items: items.map(item =>
              item.id === product.id && 
              JSON.stringify(item.variant) === JSON.stringify(variant)
                ? { ...item, quantity: item.quantity + quantity }
                : item
            ),
          });
        } else {
          set({
            items: [...items, { ...product, quantity, variant }],
          });
        }
      },
      
      removeItem: (productId, variant = null) => {
        set({
          items: get().items.filter(
            item => !(item.id === productId && 
            JSON.stringify(item.variant) === JSON.stringify(variant))
          ),
        });
      },
      
      updateQuantity: (productId, quantity, variant = null) => {
        set({
          items: get().items.map(item =>
            item.id === productId && 
            JSON.stringify(item.variant) === JSON.stringify(variant)
              ? { ...item, quantity }
              : item
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
      
      getItemCount: () => {
        return get().items.reduce(
          (count, item) => count + item.quantity,
          0
        );
      },
    }),
    {
      name: 'cart-storage',
    }
  )
);
```

---

### **Phase 3: Build Core Components (Week 3-4)**

#### **Step 3.1: Homepage Components**

**HeroSlider.jsx**
```javascript
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

export default function HeroSlider({ sliders }) {
  return (
    <Swiper
      modules={[Autoplay, Pagination]}
      autoplay={{ delay: 5000 }}
      pagination={{ clickable: true }}
      className="h-[500px]"
    >
      {sliders.map((slider) => (
        <SwiperSlide key={slider.id}>
          <div className="relative h-full">
            <img
              src={slider.image}
              alt={slider.title}
              className="w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-black/30 flex items-center">
              <div className="container mx-auto px-4">
                <h2 className="text-5xl font-bold text-white mb-4">
                  {slider.title}
                </h2>
                <p className="text-xl text-white mb-6">
                  {slider.subtitle}
                </p>
                <button className="bg-primary text-white px-8 py-3 rounded-lg">
                  Shop Now
                </button>
              </div>
            </div>
          </div>
        </SwiperSlide>
      ))}
    </Swiper>
  );
}
```

**ProductCard.jsx**
```javascript
import { Link } from 'react-router-dom';
import { useCartStore } from '../store/useCartStore';
import toast from 'react-hot-toast';

export default function ProductCard({ product }) {
  const addItem = useCartStore((state) => state.addItem);
  
  const handleAddToCart = () => {
    addItem(product, 1);
    toast.success('Added to cart!');
  };
  
  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden group">
      <Link to={`/product/${product.slug}`}>
        <div className="relative overflow-hidden">
          <img
            src={product.image?.image || product.images?.[0]?.image}
            alt={product.name}
            className="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300"
          />
          {product.old_price > product.new_price && (
            <span className="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
              {Math.round(((product.old_price - product.new_price) / product.old_price) * 100)}% OFF
            </span>
          )}
        </div>
      </Link>
      
      <div className="p-4">
        <Link to={`/product/${product.slug}`}>
          <h3 className="font-semibold text-lg mb-2 hover:text-primary">
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
          className="w-full bg-primary text-white py-2 rounded-lg hover:bg-primary-dark transition"
        >
          Add to Cart
        </button>
      </div>
    </div>
  );
}
```

#### **Step 3.2: Product Detail Page**

**ProductDetails.jsx**
```javascript
import { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useProduct } from '../hooks/useProducts';
import { useCartStore } from '../store/useCartStore';
import ProductGallery from '../components/product/ProductGallery';
import ProductReviews from '../components/product/ProductReviews';

export default function ProductDetails() {
  const { slug } = useParams();
  const { data: product, isLoading } = useProduct(slug);
  const addItem = useCartStore((state) => state.addItem);
  
  const [selectedVariant, setSelectedVariant] = useState(null);
  const [quantity, setQuantity] = useState(1);
  
  if (isLoading) return <div>Loading...</div>;
  
  const handleAddToCart = () => {
    addItem(product, quantity, selectedVariant);
    toast.success('Added to cart!');
  };
  
  return (
    <div className="container mx-auto px-4 py-8">
      <div className="grid md:grid-cols-2 gap-8">
        {/* Product Gallery */}
        <ProductGallery images={product.images} />
        
        {/* Product Info */}
        <div>
          <h1 className="text-3xl font-bold mb-4">{product.name}</h1>
          
          <div className="flex items-center gap-4 mb-6">
            {product.old_price > product.new_price && (
              <span className="text-2xl text-gray-400 line-through">
                ৳{product.old_price}
              </span>
            )}
            <span className="text-4xl font-bold text-primary">
              ৳{product.new_price}
            </span>
          </div>
          
          {/* Variants */}
          {product.variables?.length > 0 && (
            <div className="mb-6">
              <h3 className="font-semibold mb-3">Select Variant:</h3>
              <div className="flex flex-wrap gap-2">
                {product.variables.map((variant) => (
                  <button
                    key={variant.id}
                    onClick={() => setSelectedVariant(variant)}
                    className={`px-4 py-2 border rounded ${
                      selectedVariant?.id === variant.id
                        ? 'border-primary bg-primary text-white'
                        : 'border-gray-300'
                    }`}
                  >
                    {variant.size} - {variant.color}
                  </button>
                ))}
              </div>
            </div>
          )}
          
          {/* Quantity */}
          <div className="mb-6">
            <h3 className="font-semibold mb-3">Quantity:</h3>
            <div className="flex items-center gap-4">
              <button
                onClick={() => setQuantity(Math.max(1, quantity - 1))}
                className="px-4 py-2 border rounded"
              >
                -
              </button>
              <span className="text-xl">{quantity}</span>
              <button
                onClick={() => setQuantity(quantity + 1)}
                className="px-4 py-2 border rounded"
              >
                +
              </button>
            </div>
          </div>
          
          {/* Add to Cart */}
          <button
            onClick={handleAddToCart}
            className="w-full bg-primary text-white py-4 rounded-lg text-lg font-semibold hover:bg-primary-dark"
          >
            Add to Cart
          </button>
          
          {/* Description */}
          <div className="mt-8">
            <h3 className="text-xl font-semibold mb-4">Description</h3>
            <div dangerouslySetInnerHTML={{ __html: product.description }} />
          </div>
        </div>
      </div>
      
      {/* Reviews */}
      <ProductReviews productId={product.id} reviews={product.reviews} />
    </div>
  );
}
```

#### **Step 3.3: Checkout with Auto-save**

**CheckoutForm.jsx**
```javascript
import { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { useCartStore } from '../store/useCartStore';
import { orderService } from '../services/orderService';
import { debounce } from '../utils/helpers';

export default function CheckoutForm() {
  const { register, watch, handleSubmit } = useForm();
  const cart = useCartStore((state) => state.items);
  const total = useCartStore((state) => state.getTotal());
  
  // Watch form values for auto-save
  const formValues = watch();
  
  // Auto-save incomplete order
  useEffect(() => {
    const saveIncompleteOrder = debounce(async () => {
      if (formValues.name && formValues.phone) {
        try {
          await orderService.saveIncompleteOrder({
            name: formValues.name,
            phone: formValues.phone,
            address: formValues.address,
            cart_data: cart.map(item => ({
              id: item.id,
              name: item.name,
              qty: item.quantity,
              price: item.new_price,
              image: item.image?.image,
              size: item.variant?.size,
              color: item.variant?.color,
            })),
            total_amount: total,
          });
        } catch (error) {
          console.error('Auto-save failed:', error);
        }
      }
    }, 2000);
    
    saveIncompleteOrder();
  }, [formValues, cart, total]);
  
  const onSubmit = async (data) => {
    try {
      const orderData = {
        ...data,
        products: cart.map(item => ({
          product_id: item.id,
          quantity: item.quantity,
          price: item.new_price,
          variant_id: item.variant?.id,
        })),
        total_amount: total,
      };
      
      const response = await orderService.placeOrder(orderData);
      
      if (response.data.success) {
        toast.success('Order placed successfully!');
        clearCart();
        navigate('/order-success');
      }
    } catch (error) {
      toast.error('Order failed. Please try again.');
    }
  };
  
  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
      <div>
        <label className="block mb-2">Full Name *</label>
        <input
          {...register('name', { required: true })}
          className="w-full border rounded px-4 py-2"
        />
      </div>
      
      <div>
        <label className="block mb-2">Phone Number *</label>
        <input
          {...register('phone', { required: true })}
          className="w-full border rounded px-4 py-2"
        />
      </div>
      
      <div>
        <label className="block mb-2">Address *</label>
        <textarea
          {...register('address', { required: true })}
          className="w-full border rounded px-4 py-2"
          rows="3"
        />
      </div>
      
      <button
        type="submit"
        className="w-full bg-primary text-white py-3 rounded-lg font-semibold"
      >
        Place Order
      </button>
    </form>
  );
}
```

---

### **Phase 4: Customization for Each Store (Week 5)**

#### **Step 4.1: Organic Products Store**
- **Theme**: Green, natural, eco-friendly
- **Features**: 
  - Organic certification badges
  - Farm-to-table story sections
  - Sustainability information
  - Health benefits highlights

#### **Step 4.2: Fashion Brand Store**
- **Theme**: Modern, stylish, trendy
- **Features**:
  - Lookbook galleries
  - Size guide
  - Style recommendations
  - Fashion blog integration

#### **Step 4.3: Electronics Store**
- **Theme**: Tech-focused, clean, professional
- **Features**:
  - Technical specifications tables
  - Comparison tools
  - Warranty information
  - Tech support chat

---

### **Phase 5: Testing & Deployment (Week 6)**

#### **Step 5.1: Testing Checklist**
- [ ] All API endpoints working
- [ ] Cart functionality
- [ ] Checkout process
- [ ] Order tracking
- [ ] Customer authentication
- [ ] Mobile responsiveness
- [ ] Cross-browser compatibility
- [ ] Performance optimization

#### **Step 5.2: Deployment**
```bash
# Build for production
npm run build

# Deploy to hosting (Vercel/Netlify/etc.)
vercel deploy
# or
netlify deploy
```

---

## 🎨 PART 3: LOVABLE.DEV PROMPTS

### **Prompt 1: Organic Products Store (Premium Natural & Organic Foods)**

```
Create a modern, premium organic foods e-commerce website specializing in natural and organic products like Himalayan Pink Salt, Black Chia Seeds, Black Rice, Premium Khejurs (Dates), Organic Oils, Quinoa, Khejur Jaggery, Honey, and other superfoods. Use React, Tailwind CSS, and Framer Motion.

STORE CONCEPT:
This is a premium organic foods marketplace focusing on authentic, natural, and health-conscious products sourced directly from organic farms and trusted suppliers. The store emphasizes purity, health benefits, and traditional natural foods.

PRODUCT CATEGORIES TO FEATURE:
1. **Organic Salts** - Himalayan Pink Salt, Sea Salt, Black Salt
2. **Superseeds** - Black Chia Seeds, Flax Seeds, Pumpkin Seeds, Sunflower Seeds
3. **Organic Grains** - Black Rice, Red Rice, Quinoa, Millets, Organic Wheat
4. **Natural Sweeteners** - Khejur Jaggery (Date Palm Jaggery), Organic Honey, Coconut Sugar
5. **Premium Dates** - Medjool Dates, Ajwa Dates, Khejur (Local Dates), Stuffed Dates
6. **Organic Oils** - Coconut Oil, Olive Oil, Mustard Oil, Sesame Oil, Ghee
7. **Nuts & Dry Fruits** - Almonds, Walnuts, Cashews, Raisins, Dried Figs
8. **Organic Spices** - Turmeric, Cumin, Coriander, Black Pepper, Cinnamon
9. **Herbal Teas** - Green Tea, Chamomile, Tulsi Tea, Ginger Tea
10. **Health Foods** - Organic Flour, Oats, Protein Powders, Nutritional Supplements

DESIGN REQUIREMENTS:
- **Color Scheme**: 
  - Primary: Earthy Green (#2D5016, #4A7C2C)
  - Secondary: Warm Brown (#8B4513, #A0522D)
  - Accent: Golden Yellow (#F4A460)
  - Background: Cream/Off-white (#FAF9F6)
  - Text: Dark Brown (#3E2723)
- **Typography**: 
  - Headings: Playfair Display (elegant, trustworthy)
  - Body: Inter (clean, readable)
  - Accent: Satisfy or Dancing Script (for organic/natural feel)
- **Style**: Natural, premium, trustworthy, health-focused, minimalist with earthy aesthetics

PAGES TO CREATE:

1. **Homepage**:
   - **Hero Section**: 
     * Full-width slider with high-quality images of products
     * Tagline: "Pure. Natural. Organic." or "Nature's Finest, Delivered to Your Door"
     * CTA buttons: "Shop Now" and "Learn More"
   
   - **Why Choose Us Section**:
     * 100% Organic Certified
     * Direct from Farms
     * No Chemicals or Preservatives
     * Lab Tested for Purity
     * Sustainable Packaging
     * Free Delivery on Orders Above ৳1000
   
   - **Featured Categories** (with beautiful images):
     * Organic Salts & Seasonings
     * Superseeds & Grains
     * Natural Sweeteners
     * Premium Dates & Dry Fruits
     * Organic Oils & Ghee
     * Health Foods & Supplements
   
   - **Best Selling Products Grid**:
     * Himalayan Pink Salt (500g, 1kg)
     * Black Chia Seeds (250g)
     * Organic Black Rice (1kg)
     * Premium Medjool Dates (500g)
     * Raw Organic Honey (500ml)
     * Cold-Pressed Coconut Oil (500ml)
     * Khejur Jaggery (500g)
     * Organic Quinoa (500g)
   
   - **Health Benefits Section**:
     * "Discover the Power of Natural Foods"
     * Cards showing benefits: Heart Health, Weight Management, Immunity Boost, Energy & Vitality
   
   - **Customer Testimonials**: 
     * Real customer reviews with photos
     * 5-star ratings
     * "Verified Purchase" badges
   
   - **Blog/Recipe Section**:
     * "Healthy Recipes Using Our Products"
     * Recipe cards with images
   
   - **Newsletter Signup**:
     * "Get 10% Off Your First Order"
     * Email subscription form
   
   - **Trust Badges**:
     * Organic Certification logos
     * Secure Payment icons
     * Free Shipping badge
     * Money-back Guarantee

2. **Shop Page**:
   - **Advanced Filters Sidebar**:
     * Categories (dropdown with subcategories)
     * Price Range (slider: ৳0 - ৳5000)
     * Weight/Size (250g, 500g, 1kg, 2kg)
     * Certifications (Organic, Non-GMO, Gluten-Free)
     * Health Benefits (Heart Health, Weight Loss, Immunity)
     * Brand/Source
     * Availability (In Stock, Pre-order)
   
   - **Product Grid**:
     * 4 columns on desktop, 2 on tablet, 1 on mobile
     * Product card shows: Image, Name, Weight, Price, Discount badge
     * Hover effects: Quick view, Add to cart, Wishlist
   
   - **Sorting Options**:
     * Featured
     * Price: Low to High
     * Price: High to Low
     * Newest Arrivals
     * Best Selling
     * Customer Rating
   
   - **Active Filters Display**:
     * Chips showing selected filters
     * Clear all option
   
   - **Pagination or Infinite Scroll**

3. **Product Detail Page**:
   - **Product Gallery**:
     * Main large image with zoom on hover
     * Thumbnail gallery below
     * 360° view option (if available)
     * Multiple product angles
   
   - **Product Information**:
     * Product name (e.g., "Himalayan Pink Salt - Premium Quality")
     * SKU and Brand
     * Star rating with review count
     * Price with old price strikethrough if on sale
     * Discount percentage badge
     * Stock status: "In Stock" (green) or "Low Stock" (orange)
   
   - **Product Variants**:
     * Weight selector: 250g, 500g, 1kg, 2kg (with price update)
     * Packaging type (if applicable): Glass Jar, Pouch, Bottle
   
   - **Quantity Selector**:
     * - and + buttons
     * Input field
     * "Add to Cart" button (large, prominent)
     * "Buy Now" button (direct checkout)
     * Wishlist heart icon
   
   - **Product Highlights** (icon-based):
     * ✓ 100% Organic Certified
     * ✓ No Preservatives
     * ✓ Lab Tested
     * ✓ Sustainably Sourced
     * ✓ Free Shipping on Orders Above ৳1000
   
   - **Tabbed Information**:
     * **Description**: Detailed product description, origin, sourcing
     * **Nutritional Information**: Table with nutritional values per 100g
     * **Health Benefits**: List of health benefits with icons
     * **How to Use**: Usage instructions, recipes, tips
     * **Storage Instructions**: How to store for maximum freshness
     * **Certifications**: Organic certificates, lab reports
     * **Reviews**: Customer reviews with photos, verified purchase badges
     * **Shipping & Returns**: Delivery time, return policy
   
   - **Why This Product Section**:
     * Unique selling points
     * Quality assurance
     * Source/farm information
   
   - **Related Products**: "You May Also Like"
   
   - **Recently Viewed Products**
   
   - **FAQ Section**: Common questions about the product

4. **Cart Page**:
   - **Cart Items List**:
     * Product image, name, weight/variant
     * Price per unit
     * Quantity controls (-, +, remove)
     * Subtotal per item
     * "Save for Later" option
   
   - **Cart Summary** (sticky sidebar):
     * Subtotal
     * Shipping (Free above ৳1000, otherwise ৳60)
     * Discount (if coupon applied)
     * Total
   
   - **Coupon Code Section**:
     * Input field
     * "Apply" button
     * Show applied coupon with discount
   
   - **Recommended Products**: "Complete Your Order"
   
   - **Action Buttons**:
     * "Continue Shopping"
     * "Proceed to Checkout" (prominent)
   
   - **Trust Badges**: Secure checkout, money-back guarantee

5. **Checkout Page**:
   - **Progress Indicator**: Shipping → Payment → Review
   
   - **Shipping Information Form**:
     * Full Name *
     * Phone Number *
     * Email
     * Full Address *
     * District/City * (dropdown)
     * Area/Thana
     * Postal Code
     * Delivery Instructions (optional)
   
   - **Delivery Options**:
     * Standard Delivery (3-5 days) - ৳60
     * Express Delivery (1-2 days) - ৳120
     * Free Delivery (Orders above ৳1000)
   
   - **Payment Methods**:
     * Cash on Delivery (COD)
     * bKash
     * Nagad
     * Credit/Debit Card
     * Bank Transfer
   
   - **Order Summary** (sticky sidebar):
     * Product list with images
     * Subtotal
     * Shipping
     * Discount
     * Total
   
   - **Special Instructions**: Text area for order notes
   
   - **Terms & Conditions**: Checkbox
   
   - **Place Order Button**: Large, prominent
   
   - **Auto-save Feature**: Save incomplete orders to backend

6. **Account Pages**:
   - **Login/Register**:
     * Email/Phone login
     * Password
     * "Remember Me" checkbox
     * Social login options (Google, Facebook)
     * "Forgot Password" link
     * Register form with validation
   
   - **Customer Dashboard**:
     * Welcome message
     * Quick stats: Total Orders, Wishlist Items, Reward Points
     * Recent orders
     * Quick links: Profile, Orders, Wishlist, Addresses
   
   - **Order History**:
     * List of all orders
     * Order number, date, status, total
     * "View Details" and "Track Order" buttons
     * "Reorder" option
   
   - **Order Details**:
     * Order timeline with status updates
     * Product list
     * Shipping address
     * Payment method
     * Invoice download
   
   - **Profile Settings**:
     * Edit personal information
     * Change password
     * Email preferences
   
   - **Wishlist**:
     * Saved products
     * "Add to Cart" button
     * Remove option
   
   - **Saved Addresses**:
     * Multiple addresses
     * Add/Edit/Delete
     * Set default address

7. **Additional Pages**:
   - **About Us**: Brand story, mission, values, team
   - **Why Organic**: Benefits of organic foods, certifications
   - **Blog/Recipes**: Healthy recipes using products
   - **Contact Us**: Form, phone, email, location map
   - **FAQ**: Common questions
   - **Shipping & Delivery**: Delivery areas, times, charges
   - **Return & Refund Policy**: Clear policy
   - **Terms & Conditions**: Legal terms
   - **Privacy Policy**: Data protection

COMPONENTS TO BUILD:

1. **Header/Navigation**:
   - Logo (left)
   - Search bar (center) with autocomplete
   - Icons: Wishlist, Cart (with item count), Account
   - Main navigation: Home, Shop, Categories, About, Blog, Contact
   - Mega menu for categories (on hover)
   - Mobile hamburger menu
   - Sticky header on scroll
   - Top announcement bar: "Free Shipping on Orders Above ৳1000"

2. **Footer**:
   - **Quick Links**: About, Shop, Blog, Contact, FAQ
   - **Categories**: All product categories
   - **Customer Service**: Shipping, Returns, Track Order, Help
   - **Contact Info**: Phone, Email, Address
   - **Social Media**: Facebook, Instagram, YouTube icons
   - **Newsletter Signup**: Email input
   - **Payment Methods**: Icons of accepted payments
   - **Certifications**: Organic certification logos
   - Copyright text

3. **Product Card**:
   - Product image with hover zoom
   - "New" or "Sale" badge
   - Product name
   - Weight/size
   - Star rating
   - Price (with old price if on sale)
   - "Add to Cart" button
   - Wishlist heart icon
   - Quick view icon
   - Organic certification badge

4. **Category Card**:
   - Large category image
   - Category name overlay
   - Product count
   - Hover effect with "Shop Now" button

5. **Search Component**:
   - Search input with icon
   - Autocomplete dropdown
   - Recent searches
   - Popular searches
   - Product suggestions with images

6. **Cart Sidebar**:
   - Slide-in from right
   - Cart items list
   - Subtotal
   - "View Cart" and "Checkout" buttons
   - Close button

7. **Product Quick View Modal**:
   - Product image
   - Basic info
   - Variant selector
   - Add to cart
   - "View Full Details" link

8. **Review Component**:
   - Star rating
   - Customer name and date
   - Verified purchase badge
   - Review text
   - Helpful votes
   - Review images

9. **Loading States**:
   - Skeleton loaders for products
   - Spinner for actions
   - Progress bars

10. **Toast Notifications**:
    - Success: "Added to cart!"
    - Error: "Something went wrong"
    - Info: "Product saved to wishlist"

11. **Breadcrumbs**: Home > Shop > Organic Salts > Himalayan Pink Salt

12. **Filter Chips**: Active filters with remove option

13. **Pagination**: Page numbers with prev/next

14. **Image Zoom**: Magnifying glass on hover

15. **Star Rating**: Interactive rating component

SPECIAL FEATURES FOR ORGANIC FOOD STORE:

1. **Certification Badges**:
   - Display organic certification logos
   - "100% Organic" badge
   - "Lab Tested" badge
   - "No Preservatives" badge

2. **Nutritional Information Display**:
   - Detailed nutritional table
   - Calories, protein, carbs, fats, vitamins
   - Per 100g serving

3. **Health Benefits Section**:
   - Icon-based benefits display
   - Heart health, immunity, weight management
   - Scientific backing

4. **Source/Origin Information**:
   - "Sourced from Himalayan Mountains"
   - Farm/supplier information
   - Sustainability practices

5. **Recipe Suggestions**:
   - "How to Use This Product"
   - Recipe cards with images
   - Cooking tips

6. **Freshness Guarantee**:
   - Expiry date display
   - "Best Before" information
   - Storage instructions

7. **Bulk Purchase Options**:
   - Quantity discounts
   - "Buy 2 Get 10% Off" badges
   - Wholesale inquiry form

8. **Subscription Option**:
   - "Subscribe & Save 15%"
   - Monthly delivery
   - Manage subscription

9. **Gift Packaging**:
   - Gift box option
   - Gift message
   - Premium packaging

10. **Loyalty Program**:
    - Earn points on purchases
    - Redeem for discounts
    - Referral rewards

PRODUCT-SPECIFIC FEATURES:

**For Himalayan Pink Salt**:
- Origin: Himalayan Mountains
- Benefits: 84 trace minerals, electrolyte balance
- Uses: Cooking, bath salts, salt lamps
- Variants: Fine, Coarse, Rock form

**For Black Chia Seeds**:
- Origin: Organic farms
- Benefits: Omega-3, fiber, protein
- Uses: Smoothies, puddings, baking
- Serving suggestions

**For Black Rice**:
- Origin: Organic paddy fields
- Benefits: Antioxidants, fiber, iron
- Cooking instructions
- Recipe ideas

**For Premium Dates (Khejur)**:
- Origin: Middle East / Local farms
- Benefits: Natural energy, fiber, minerals
- Varieties: Medjool, Ajwa, Local
- Storage tips

**For Organic Honey**:
- Origin: Local beekeepers
- Benefits: Antibacterial, antioxidants
- Types: Raw, Manuka, Wildflower
- Purity guarantee

**For Organic Oils**:
- Extraction method: Cold-pressed
- Benefits: Heart health, skin care
- Uses: Cooking, massage, hair care
- Storage instructions

**For Quinoa**:
- Origin: South America / Organic farms
- Benefits: Complete protein, gluten-free
- Cooking guide
- Recipe ideas

**For Khejur Jaggery**:
- Origin: Date palm trees
- Benefits: Natural sweetener, iron-rich
- Uses: Tea, desserts, cooking
- Comparison with sugar

TECHNICAL REQUIREMENTS:

- **React 18+** with functional components and hooks
- **Tailwind CSS** for styling with custom theme
- **React Router v6** for navigation
- **Framer Motion** for smooth animations
- **React Hook Form** for form handling with validation
- **Axios** for API calls (prepare for Laravel backend integration)
- **Zustand** for state management (cart, wishlist, auth)
- **React Query** for data fetching and caching
- **Swiper** for carousels and sliders
- **React Icons** for icons
- **React Toastify** for notifications

API INTEGRATION STRUCTURE:

```javascript
// Product object structure
{
  id: 1,
  name: "Himalayan Pink Salt - Premium Quality",
  slug: "himalayan-pink-salt-premium",
  category: "Organic Salts",
  price: 350,
  old_price: 450,
  weight: "500g",
  stock: 150,
  images: ["/products/pink-salt-1.jpg", "/products/pink-salt-2.jpg"],
  description: "Pure Himalayan Pink Salt...",
  nutritional_info: {...},
  health_benefits: [...],
  certifications: ["Organic", "Lab Tested"],
  origin: "Himalayan Mountains",
  rating: 4.8,
  reviews_count: 234
}
```

FEATURES TO IMPLEMENT:

1. **Smooth Animations**:
   - Page transitions
   - Product card hover effects
   - Add to cart animation (product flies to cart)
   - Scroll animations (fade in, slide up)

2. **Responsive Design**:
   - Mobile-first approach
   - Tablet optimization
   - Desktop layouts

3. **Performance**:
   - Lazy loading images
   - Code splitting
   - Optimized bundle size

4. **Accessibility**:
   - ARIA labels
   - Keyboard navigation
   - Screen reader support
   - High contrast mode

5. **SEO**:
   - Meta tags
   - Structured data
   - Semantic HTML
   - Alt texts for images

6. **User Experience**:
   - Fast loading
   - Intuitive navigation
   - Clear CTAs
   - Error handling
   - Loading states

7. **Shopping Features**:
   - Cart persistence (localStorage)
   - Wishlist
   - Recently viewed
   - Product comparison
   - Auto-save checkout form

8. **Trust Building**:
   - Customer reviews
   - Certification badges
   - Secure payment icons
   - Money-back guarantee
   - Free shipping badge

DESIGN INSPIRATION:
- Clean, minimalist layouts
- High-quality product photography
- Earthy, natural color palette
- Generous white space
- Clear typography hierarchy
- Trust-building elements throughout

Please create a complete, production-ready organic foods e-commerce website with:
- Clean, maintainable code
- Proper component structure
- Beautiful, trustworthy UI/UX
- Focus on health, purity, and natural living
- Emphasis on product quality and authenticity
- Easy navigation and shopping experience
- Mobile-responsive design
- Fast performance
- Accessibility compliance

The website should make customers feel confident about purchasing premium organic products and emphasize the health benefits, purity, and authenticity of natural foods.
```

---

### **Prompt 2: Fashion Brand Store**

```
Create a high-end, modern fashion e-commerce website using React, Tailwind CSS, and Framer Motion.

DESIGN REQUIREMENTS:
- Color Scheme: Black (#000000), White (#FFFFFF), Gold accents (#D4AF37)
- Typography: Elegant serif for headings (Playfair Display), clean sans-serif for body (Inter)
- Style: Luxury, minimalist, editorial-inspired, high-fashion

PAGES TO CREATE:
1. Homepage:
   - Full-screen video hero or animated image slider
   - "New Collection" banner with CTA
   - Featured products in masonry grid layout
   - "Shop by Category" with large image cards
   - Instagram feed integration section
   - Brand story section with parallax effect
   - Newsletter popup (appears after 5 seconds)

2. Shop Page:
   - Filter sidebar: Categories, Size, Color, Price, Brand
   - Product grid with hover effects (show second image on hover)
   - Quick shop button overlay
   - Sort by: Featured, Price (Low-High), Price (High-Low), Newest
   - Infinite scroll or pagination
   - "Recently Viewed" section

3. Product Detail Page:
   - Large image gallery (vertical thumbnails on left)
   - Product name and price prominently displayed
   - Size guide modal
   - Color swatches with product image change
   - Size selector with stock availability
   - "Add to Bag" button with loading animation
   - Accordion sections: Details, Fit & Care, Shipping & Returns
   - "Complete the Look" suggestions
   - Customer reviews with photos
   - "Share" buttons (social media)

4. Lookbook Page:
   - Full-screen image gallery
   - Hotspots on images showing product details
   - Click to shop products from the look
   - Filter by season/collection

5. Cart Page:
   - Elegant cart items display
   - Image, name, size, color, price, quantity
   - "Save for Later" option
   - Estimated delivery date
   - Recommended products
   - Gift wrapping option

6. Checkout Page:
   - Clean, single-page checkout
   - Guest checkout option
   - Shipping address form
   - Shipping method selection
   - Payment method (cards, digital wallets)
   - Order review section
   - Trust badges (secure payment, free returns)

7. Account Pages:
   - Stylish login/register forms
   - Dashboard: Orders, Wishlist, Addresses, Payment Methods
   - Order history with status tracking
   - Wishlist with "Move to Bag" option
   - Profile settings

COMPONENTS TO BUILD:
- Sticky header with transparent-to-solid transition on scroll
- Mega menu for categories
- Product card with quick view
- Size guide modal
- Image zoom on hover
- Color swatch selector
- Wishlist heart icon with animation
- Mini cart dropdown
- Search overlay (full-screen)
- Loading animations
- Toast notifications (elegant, minimal)

FEATURES:
- Smooth page transitions
- Image lazy loading with blur-up effect
- Wishlist functionality
- Recently viewed products
- Product comparison
- Size recommendation based on previous purchases
- Virtual try-on (placeholder for future AR integration)
- Style quiz for personalized recommendations

TECHNICAL REQUIREMENTS:
- React 18+ with TypeScript (optional but recommended)
- Tailwind CSS with custom theme
- Framer Motion for sophisticated animations
- React Router v6
- React Hook Form with Yup validation
- Axios for API calls
- Zustand for global state (cart, wishlist, auth)
- React Query for server state
- Swiper for carousels

API INTEGRATION:
- Product endpoints: /products, /product/{slug}
- Category endpoints: /categories
- Cart management
- Order placement
- Customer authentication
- Wishlist sync

FASHION-SPECIFIC FEATURES:
- Virtual styling assistant (chatbot)
- "Shop the Look" from editorial images
- Size recommendation algorithm
- Style profile quiz
- Outfit builder tool
- Fashion blog integration
- Influencer collaborations section
- Limited edition drops countdown timer

ANIMATIONS:
- Smooth page transitions
- Product card hover effects (scale, overlay)
- Add to cart animation (product flies to cart icon)
- Parallax scrolling on hero sections
- Stagger animations for product grids
- Smooth accordion transitions

Please create a luxurious, high-performance fashion e-commerce site with impeccable design, smooth animations, and intuitive UX that makes customers feel they're shopping at a premium brand.
```

---

### **Prompt 3: Electronics Store**

```
Create a modern, tech-focused electronics e-commerce website using React, Tailwind CSS, and Framer Motion.

DESIGN REQUIREMENTS:
- Color Scheme: Dark blue (#0A1929), Electric blue (#00B4D8), White, Gray tones
- Typography: Modern sans-serif (Inter, Roboto)
- Style: Clean, tech-forward, professional, data-driven

PAGES TO CREATE:
1. Homepage:
   - Dynamic hero slider with latest tech products
   - "Hot Deals" countdown timer section
   - Category grid with icons (Smartphones, Laptops, Audio, Gaming, etc.)
   - Featured products carousel
   - "Top Rated" products section
   - Brand logos slider
   - Tech blog/news section
   - Newsletter signup

2. Shop Page:
   - Advanced filter sidebar:
     * Categories with subcategories
     * Price range slider
     * Brands (checkboxes)
     * Specifications (RAM, Storage, Screen Size, etc.)
     * Customer ratings
     * Availability (In Stock, Pre-order)
   - Product grid/list view toggle
   - Sort options: Relevance, Price, Rating, Newest
   - Comparison checkbox on products
   - "Compare" floating button (when 2+ selected)

3. Product Detail Page:
   - Image gallery with 360° view option
   - Product name, SKU, brand
   - Price with financing options display
   - Stock status with delivery estimate
   - Variant selector (color, storage, etc.)
   - Quantity selector
   - "Add to Cart" and "Buy Now" buttons
   - Tabs:
     * Overview (key features with icons)
     * Full Specifications (detailed table)
     * Reviews (with verified purchase badge)
     * Q&A section
     * Warranty & Support
   - "What's in the Box" section
   - Related products
   - Recently viewed products
   - "Customers also bought" section

4. Product Comparison Page:
   - Side-by-side comparison table
   - Highlight differences
   - Add/remove products
   - "Add to Cart" for each product
   - Print comparison option

5. Cart Page:
   - Cart items with product images
   - Quantity controls
   - Remove/Save for later
   - Extended warranty options
   - Accessories recommendations
   - Cart summary with tax breakdown
   - Coupon code input
   - Estimated delivery date

6. Checkout Page:
   - Progress indicator (Shipping → Payment → Review)
   - Shipping address form with address autocomplete
   - Delivery options (Standard, Express, Same-day)
   - Payment methods (Cards, EMI, Digital Wallets, COD)
   - EMI calculator
   - Order summary
   - Apply coupon
   - Terms & conditions checkbox

7. Account Pages:
   - Login/Register with OTP option
   - Dashboard:
     * Orders (with tracking)
     * Wishlist
     * Saved addresses
     * Payment methods
     * Product reviews
     * Support tickets
   - Order tracking with real-time updates
   - Invoice download

8. Support Pages:
   - FAQ with search
   - Contact form
   - Live chat widget
   - Service centers locator
   - Warranty registration

COMPONENTS TO BUILD:
- Mega menu with category images
- Product card (grid/list variants)
- Specification table
- Rating & review component
- Comparison table
- Price history chart
- Stock alert notification
- Wishlist button with animation
- Search with suggestions
- Filter chips (active filters)
- Breadcrumbs
- Pagination
- Loading skeletons
- Error boundaries

FEATURES:
- Advanced search with filters
- Product comparison (up to 4 products)
- Price drop alerts
- Stock notifications
- Wishlist with price tracking
- Recently viewed products
- Product recommendations
- EMI calculator
- Warranty registration
- Order tracking
- Invoice generation
- Review with image upload
- Q&A section

TECHNICAL REQUIREMENTS:
- React 18+ with TypeScript
- Tailwind CSS with custom utilities
- Framer Motion for animations
- React Router v6
- React Hook Form with Zod validation
- Axios with interceptors
- Zustand for state management
- React Query with caching
- Chart.js for price history
- React Table for comparison

API INTEGRATION:
- Products: /products, /product/{slug}
- Categories: /categories, /brands
- Search: /global-search
- Filters: /products/filter
- Cart: localStorage + API sync
- Orders: /chack-out, /customer/orders
- Auth: /customer/login, /customer/register
- Reviews: /product/review/store
- Tracking: /customer/order-track/result

ELECTRONICS-SPECIFIC FEATURES:
- Technical specifications table
- Product comparison tool
- 360° product view
- Video reviews
- Expert reviews section
- Price history graph
- Stock availability checker
- EMI calculator
- Warranty information
- Service center locator
- Trade-in value calculator
- Build your PC tool (for components)
- Compatibility checker
- Tech specs filter (RAM, Storage, etc.)

PERFORMANCE OPTIMIZATIONS:
- Image lazy loading
- Code splitting
- Virtual scrolling for long lists
- Debounced search
- Optimistic UI updates
- Service worker for offline support
- CDN for images

ACCESSIBILITY:
- ARIA labels
- Keyboard navigation
- Screen reader support
- High contrast mode
- Focus indicators

Please create a feature-rich, high-performance electronics store with advanced filtering, product comparison, detailed specifications, and a professional UI that tech enthusiasts will love.
```

---

## 📋 PART 4: IMPLEMENTATION CHECKLIST

### **Pre-Development**
- [ ] Review all API documentation
- [ ] Set up development environment
- [ ] Create project repositories
- [ ] Set up version control (Git)
- [ ] Configure environment variables

### **Week 1: Setup**
- [ ] Create 3 React projects
- [ ] Install dependencies
- [ ] Configure Tailwind CSS
- [ ] Set up folder structure
- [ ] Create API service layer
- [ ] Set up React Query
- [ ] Create Zustand stores

### **Week 2: API Integration**
- [ ] Implement product services
- [ ] Implement category services
- [ ] Implement order services
- [ ] Implement auth services
- [ ] Create custom hooks
- [ ] Test all API endpoints
- [ ] Handle error cases

### **Week 3-4: Core Components**
- [ ] Build Header/Footer
- [ ] Build Homepage
- [ ] Build Product Listing
- [ ] Build Product Details
- [ ] Build Cart
- [ ] Build Checkout
- [ ] Build Account pages
- [ ] Implement search
- [ ] Implement filters

### **Week 5: Customization**
- [ ] Customize Organic Store theme
- [ ] Customize Fashion Store theme
- [ ] Customize Electronics Store theme
- [ ] Add store-specific features
- [ ] Optimize images
- [ ] Add animations

### **Week 6: Testing & Deployment**
- [ ] Unit testing
- [ ] Integration testing
- [ ] Cross-browser testing
- [ ] Mobile responsiveness
- [ ] Performance optimization
- [ ] SEO optimization
- [ ] Deploy to hosting
- [ ] Configure domains
- [ ] Set up analytics

---

## 🎓 LEARNING RESOURCES

### **React & Modern JavaScript**
- React Official Docs: https://react.dev
- React Router: https://reactrouter.com
- JavaScript.info: https://javascript.info

### **Styling & UI**
- Tailwind CSS: https://tailwindcss.com
- Headless UI: https://headlessui.com
- Framer Motion: https://www.framer.com/motion

### **State Management**
- Zustand: https://github.com/pmndrs/zustand
- React Query: https://tanstack.com/query

### **Forms & Validation**
- React Hook Form: https://react-hook-form.com
- Zod: https://zod.dev

---

## 📞 SUPPORT & MAINTENANCE

### **Post-Launch Tasks**
1. Monitor API performance
2. Track user behavior (Google Analytics)
3. Collect user feedback
4. Fix bugs promptly
5. Regular security updates
6. Content updates
7. SEO optimization
8. Performance monitoring

### **Scaling Considerations**
- Implement caching strategies
- Use CDN for static assets
- Optimize database queries
- Add Redis for session management
- Implement rate limiting
- Set up monitoring (Sentry, LogRocket)

---

## ✅ SUCCESS METRICS

### **Technical Metrics**
- Page load time < 3 seconds
- Lighthouse score > 90
- Mobile responsiveness: 100%
- API response time < 500ms
- Zero critical bugs

### **Business Metrics**
- Conversion rate
- Cart abandonment rate
- Average order value
- Customer retention
- Page views per session

---

**Document Version**: 1.0  
**Last Updated**: January 2026  
**Status**: Ready for Implementation ✅
