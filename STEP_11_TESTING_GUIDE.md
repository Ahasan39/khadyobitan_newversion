# 🧪 Step 11 - Test & Debug Guide

## 🚀 Getting Started

### Prerequisites
- PHP 8.0+ installed
- Node.js 16+ installed
- Composer installed
- Database configured (.env file)

### Initial Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Install Node dependencies
npm install

# 3. Generate app key
php artisan key:generate

# 4. Run migrations
php artisan migrate

# 5. Seed database (optional)
php artisan db:seed
```

---

## 🖥️ Starting Development Servers

### Terminal 1: Laravel Server
```bash
php artisan serve
# Server runs at: http://127.0.0.1:8000
```

### Terminal 2: Vite Dev Server
```bash
npm run dev
# Vite runs at: http://localhost:5173
```

### Terminal 3: Queue Worker (Optional)
```bash
php artisan queue:work
```

---

## ✅ Testing Checklist

### 1. Homepage & Navigation

**Test URL**: `http://127.0.0.1:8000/`

- [ ] Page loads without errors
- [ ] All featured products display
- [ ] Categories show correctly
- [ ] Brands display properly
- [ ] Navigation links work
- [ ] Header renders correctly
- [ ] Footer displays
- [ ] Mobile bottom nav appears on mobile

**Expected Elements:**
- Sliders/Banners
- Hot deals section
- New arrivals
- Top rated products
- Categories
- Brands

---

### 2. Shop Page

**Test URL**: `http://127.0.0.1:8000/shop`

- [ ] Products load with pagination
- [ ] Sorting works (newest, oldest, price high-low, etc.)
- [ ] Category filtering works
- [ ] Price range filtering works
- [ ] Load more button functions
- [ ] Product cards display correctly
- [ ] Images load properly
- [ ] Prices display correctly

**Test Sorting Options:**
- [ ] Newest first
- [ ] Oldest first
- [ ] Price high to low
- [ ] Price low to high
- [ ] Name A-Z
- [ ] Name Z-A

---

### 3. Product Details Page

**Test URL**: `http://127.0.0.1:8000/product/sample-product-slug`

- [ ] Product information displays
- [ ] Product images load
- [ ] Price displays correctly
- [ ] Description shows
- [ ] Related products appear
- [ ] Color options display (if available)
- [ ] Size options display (if available)
- [ ] Stock status shows
- [ ] Add to cart button works
- [ ] Add to wishlist button works
- [ ] Reviews section displays

**Test Interactions:**
- [ ] Select color variant
- [ ] Select size variant
- [ ] Check stock availability
- [ ] Add to cart
- [ ] Add to wishlist

---

### 4. Shopping Cart

**Test URL**: `http://127.0.0.1:8000/cart`

- [ ] Cart items display
- [ ] Item quantities show
- [ ] Prices calculate correctly
- [ ] Subtotal displays
- [ ] Shipping options appear
- [ ] Total updates when shipping selected
- [ ] Remove item button works
- [ ] Update quantity works
- [ ] Continue shopping link works
- [ ] Proceed to checkout button works

**Test Cart Operations:**
- [ ] Add product to cart
- [ ] Update quantity
- [ ] Remove item
- [ ] Select shipping method
- [ ] Clear cart

---

### 5. Checkout Page

**Test URL**: `http://127.0.0.1:8000/checkout` (Requires login)

- [ ] Checkout form displays
- [ ] Customer info fields show
- [ ] Address fields display
- [ ] District dropdown works
- [ ] Area dropdown populates based on district
- [ ] Payment method options show
- [ ] Order summary displays
- [ ] Submit button works
- [ ] Form validation works

**Test Checkout Flow:**
- [ ] Fill customer information
- [ ] Select district
- [ ] Select area
- [ ] Choose payment method
- [ ] Submit order
- [ ] Order confirmation displays

---

### 6. Authentication

**Test Login**: `http://127.0.0.1:8000/login`

- [ ] Login form displays
- [ ] Email field works
- [ ] Password field works
- [ ] Submit button works
- [ ] Error messages display for invalid credentials
- [ ] Successful login redirects to account
- [ ] Session persists

**Test Register**: `http://127.0.0.1:8000/register`

- [ ] Register form displays
- [ ] Name field works
- [ ] Email field works
- [ ] Phone field works
- [ ] Password field works
- [ ] Password confirmation works
- [ ] Form validation works
- [ ] Successful registration logs in user
- [ ] Duplicate email shows error

---

### 7. Account Dashboard

**Test URL**: `http://127.0.0.1:8000/account` (Requires login)

- [ ] Dashboard loads
- [ ] Customer info displays
- [ ] Recent orders show
- [ ] Order history displays
- [ ] Profile edit link works
- [ ] Change password link works
- [ ] Logout button works

**Test Account Features:**
- [ ] View profile
- [ ] Edit profile
- [ ] Change password
- [ ] View orders
- [ ] View order details

---

### 8. Wishlist

**Test URL**: `http://127.0.0.1:8000/wishlist`

- [ ] Wishlist page loads
- [ ] Wishlist items display
- [ ] Add to cart from wishlist works
- [ ] Remove from wishlist works
- [ ] Empty wishlist message shows when empty
- [ ] Wishlist count updates in header

**Test Wishlist Operations:**
- [ ] Add product to wishlist
- [ ] View wishlist
- [ ] Remove from wishlist
- [ ] Add to cart from wishlist

---

### 9. Order Tracking

**Test URL**: `http://127.0.0.1:8000/track-order`

- [ ] Tracking form displays
- [ ] Invoice ID field works
- [ ] Email field works
- [ ] Submit button works
- [ ] Order details display after search
- [ ] Order status shows
- [ ] Tracking information displays

---

### 10. Static Pages

**Test URLs:**
- [ ] `/about` - About page loads
- [ ] `/contact` - Contact page loads
- [ ] `/faq` - FAQ page loads
- [ ] `/privacy` - Privacy policy loads
- [ ] `/terms` - Terms & conditions load
- [ ] `/shipping` - Shipping policy loads
- [ ] `/returns` - Return policy loads

**Verify Each Page:**
- [ ] Content displays
- [ ] Navigation works
- [ ] Footer displays
- [ ] No console errors

---

### 11. Blog

**Test URLs:**
- [ ] `/blog` - Blog listing loads
- [ ] `/blog/{slug}` - Blog post loads

**Test Blog Features:**
- [ ] Blog posts display
- [ ] Pagination works
- [ ] Search functionality works
- [ ] Related posts show
- [ ] Post content displays
- [ ] Navigation works

---

### 12. API Endpoints

**Test Cart API:**
```bash
# Add to cart
POST /api/cart/add
Body: { product_id, quantity, color, size }

# Remove from cart
POST /api/cart/remove
Body: { rowId }

# Update cart
POST /api/cart/update
Body: { rowId, quantity }

# Get cart content
GET /api/cart/content

# Update shipping
POST /api/cart/shipping
Body: { shippingId }

# Clear cart
POST /api/cart/clear
```

**Test Wishlist API:**
```bash
# Add to wishlist
POST /api/wishlist/add
Body: { product_id }

# Remove from wishlist
POST /api/wishlist/remove
Body: { product_id }

# Check wishlist
GET /api/wishlist/check?product_id=1
```

**Test Product API:**
```bash
# Check stock
POST /api/product/check-stock
Body: { id, color, size }

# Get variables
GET /api/product/variables?id=1
```

**Test Checkout API:**
```bash
# Store order
POST /api/checkout/store
Body: { customer_name, email, phone, address, district, area, payment_method }

# Get areas
GET /api/checkout/areas?district=Dhaka
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Vite Assets Not Loading
**Symptom**: CSS/JS files return 404
**Solution**:
```bash
# Restart Vite dev server
npm run dev

# Clear cache
npm run build
```

### Issue 2: Database Connection Error
**Symptom**: "SQLSTATE[HY000]"
**Solution**:
```bash
# Check .env file
# Verify database credentials
# Run migrations
php artisan migrate
```

### Issue 3: Session Not Persisting
**Symptom**: User logs out after page refresh
**Solution**:
```bash
# Clear sessions
php artisan session:table
php artisan migrate

# Check SESSION_DRIVER in .env
# Should be: SESSION_DRIVER=cookie or database
```

### Issue 4: CORS Errors
**Symptom**: "Access to XMLHttpRequest blocked by CORS"
**Solution**:
```bash
# Check config/cors.php
# Verify allowed origins
# Restart server
php artisan serve
```

### Issue 5: 404 on Routes
**Symptom**: "Route not found"
**Solution**:
```bash
# Clear route cache
php artisan route:clear

# Verify routes/inertia.php is included in web.php
# Check route names match frontend links
```

---

## 📊 Performance Testing

### Load Testing
```bash
# Install Apache Bench
# macOS: brew install httpd
# Ubuntu: sudo apt-get install apache2-utils

# Test homepage
ab -n 100 -c 10 http://127.0.0.1:8000/

# Test shop page
ab -n 100 -c 10 http://127.0.0.1:8000/shop
```

### Browser DevTools Testing
1. Open Chrome DevTools (F12)
2. Go to Network tab
3. Check:
   - [ ] Page load time < 3 seconds
   - [ ] No failed requests
   - [ ] Images optimized
   - [ ] CSS/JS minified

4. Go to Performance tab
5. Record page load
6. Check:
   - [ ] FCP (First Contentful Paint) < 1.8s
   - [ ] LCP (Largest Contentful Paint) < 2.5s
   - [ ] CLS (Cumulative Layout Shift) < 0.1

---

## 🔍 Browser Console Testing

### Check for Errors
1. Open DevTools Console (F12 → Console)
2. Look for:
   - [ ] No red error messages
   - [ ] No 404 errors
   - [ ] No CORS warnings
   - [ ] No undefined variables

### Test Console Commands
```javascript
// Check if Inertia is loaded
console.log(window.Inertia)

// Check current page props
console.log(window.Inertia.page.props)

// Check router
console.log(window.Inertia.router)
```

---

## 📱 Mobile Testing

### Responsive Design
- [ ] Test on mobile (375px width)
- [ ] Test on tablet (768px width)
- [ ] Test on desktop (1920px width)

### Mobile Features
- [ ] Bottom navigation appears
- [ ] Menu hamburger works
- [ ] Touch interactions work
- [ ] Forms are mobile-friendly
- [ ] Images scale properly

### Test Devices
- [ ] iPhone (Safari)
- [ ] Android (Chrome)
- [ ] iPad (Safari)
- [ ] Desktop (Chrome, Firefox, Safari)

---

## 🔐 Security Testing

### Authentication
- [ ] Cannot access protected routes without login
- [ ] Session expires after inactivity
- [ ] CSRF token present in forms
- [ ] Password hashed in database

### Input Validation
- [ ] SQL injection attempts blocked
- [ ] XSS attempts blocked
- [ ] Invalid email rejected
- [ ] Required fields validated

### API Security
- [ ] API endpoints require authentication where needed
- [ ] Rate limiting works
- [ ] Invalid requests return proper errors

---

## 📝 Testing Report Template

```markdown
# Testing Report - [Date]

## Environment
- PHP Version: [version]
- Node Version: [version]
- Browser: [browser]
- OS: [os]

## Test Results

### Homepage
- Status: ✅ PASS / ❌ FAIL
- Issues: [list any issues]

### Shop Page
- Status: ✅ PASS / ❌ FAIL
- Issues: [list any issues]

### Product Details
- Status: ✅ PASS / ❌ FAIL
- Issues: [list any issues]

### Cart
- Status: ✅ PASS / ❌ FAIL
- Issues: [list any issues]

### Checkout
- Status: ✅ PASS / ❌ FAIL
- Issues: [list any issues]

### Authentication
- Status: ✅ PASS / ❌ FAIL
- Issues: [list any issues]

### Account
- Status: ✅ PASS / ❌ FAIL
- Issues: [list any issues]

### API Endpoints
- Status: ✅ PASS / ❌ FAIL
- Issues: [list any issues]

## Overall Status
- Total Tests: [number]
- Passed: [number]
- Failed: [number]
- Success Rate: [percentage]%

## Recommendations
- [recommendation 1]
- [recommendation 2]
- [recommendation 3]
```

---

## 🎯 Sign-Off Checklist

Before moving to Step 12 (Production Build), verify:

- [ ] All routes load without errors
- [ ] Navigation works across all pages
- [ ] Cart functionality works
- [ ] Checkout process completes
- [ ] Authentication works
- [ ] Account dashboard displays
- [ ] API endpoints respond correctly
- [ ] No console errors
- [ ] Mobile responsive
- [ ] Performance acceptable
- [ ] Security checks pass
- [ ] Database operations work

---

**Status**: Step 11 - Testing & Debugging
**Next**: Step 12 - Build Production
**Estimated Time**: 1-2 hours for thorough testing
