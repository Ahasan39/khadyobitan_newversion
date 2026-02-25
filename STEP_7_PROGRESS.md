# 🎯 Step 7 Progress: Updating Pages for Inertia

## ✅ Completed

### Index.tsx (Homepage) - DONE ✅

**Changes Made:**
1. ✅ Replaced `import { Link } from "react-router-dom"` with `import { Head, Link } from "@inertiajs/react"`
2. ✅ Changed all `<Link to="">` to `<Link href="">`
3. ✅ Added `<Head title="Home - Khadyobitan" />` for page title
4. ✅ Wrapped return in `<>` fragment to include Head component
5. ✅ Ready to receive props from Laravel controller (currently using fallback static data)

**Links Updated:**
- `/shop` links (3 instances)
- `/blog` links (2 instances)
- `/blog/${post.slug}` links
- `/shop?category=${cat.slug}` links

---

## 🔄 Next Pages to Update

### Priority 1 (Core Functionality):
1. **Shop.tsx** - Product listing page
2. **ProductDetail.tsx** - Single product page
3. **Cart.tsx** - Shopping cart
4. **Checkout.tsx** - Checkout process

### Priority 2 (User Features):
5. **Login.tsx** - Customer login/register
6. **Account.tsx** - Customer account dashboard
7. **Wishlist.tsx** - Wishlist page
8. **OrderTracking.tsx** - Track orders
9. **OrderConfirmation.tsx** - Order success page

### Priority 3 (Content Pages):
10. **About.tsx** - About page
11. **Contact.tsx** - Contact page
12. **FAQ.tsx** - FAQ page
13. **Blog.tsx** - Blog listing
14. **BlogDetail.tsx** - Single blog post

### Priority 4 (Legal Pages):
15. **Privacy.tsx** - Privacy policy
16. **Terms.tsx** - Terms & conditions
17. **ReturnPolicy.tsx** - Return policy
18. **ShippingPolicy.tsx** - Shipping policy
19. **NotFound.tsx** - 404 page

---

## 📝 Standard Changes for Each Page

### 1. Update Imports
```typescript
// Before
import { Link, useNavigate } from "react-router-dom";

// After
import { Head, Link, router } from "@inertiajs/react";
```

### 2. Add TypeScript Interface for Props
```typescript
interface PageProps {
  // Define props that will come from Laravel controller
  data?: any;
}

const PageName = ({ data }: PageProps) => {
```

### 3. Add Head Component
```typescript
return (
  <>
    <Head title="Page Title" />
    <div>
      {/* Page content */}
    </div>
  </>
);
```

### 4. Update Links
```typescript
// Before
<Link to="/path">Text</Link>

// After
<Link href="/path">Text</Link>
```

### 5. Update Navigation
```typescript
// Before
const navigate = useNavigate();
navigate('/path');

// After
import { router } from "@inertiajs/react";
router.visit('/path');
```

### 6. Update Forms
```typescript
// Before
const handleSubmit = async (e) => {
  e.preventDefault();
  await axios.post('/api/endpoint', data);
};

// After
import { useForm } from "@inertiajs/react";
const { data, setData, post, processing, errors } = useForm({
  field: ''
});

const handleSubmit = (e) => {
  e.preventDefault();
  post('/endpoint');
};
```

---

## 🎯 Next Steps

### Immediate Next Actions:

1. **Update Shop.tsx** (Product listing)
   - Remove React Router
   - Add Inertia imports
   - Update all links
   - Prepare to receive products from controller

2. **Update ProductDetail.tsx** (Product page)
   - Remove React Router
   - Add Inertia imports
   - Update navigation
   - Prepare to receive product data

3. **Update Cart.tsx** (Shopping cart)
   - Remove React Router
   - Add Inertia imports
   - Update cart actions to use Inertia forms
   - Connect with Laravel Shopping Cart package

4. **Update Checkout.tsx** (Checkout)
   - Remove React Router
   - Add Inertia imports
   - Convert checkout form to Inertia form
   - Handle order submission

---

## 📊 Progress Tracking

| Page | Status | Priority | Estimated Time |
|------|--------|----------|----------------|
| Index.tsx | ✅ Complete | P1 | Done |
| Shop.tsx | ✅ Complete | P1 | Done |
| ProductDetail.tsx | ⏳ Pending | P1 | 15 min |
| Cart.tsx | ⏳ Pending | P1 | 20 min |
| Checkout.tsx | ⏳ Pending | P1 | 25 min |
| Login.tsx | ⏳ Pending | P2 | 20 min |
| Account.tsx | ⏳ Pending | P2 | 15 min |
| Wishlist.tsx | ⏳ Pending | P2 | 10 min |
| OrderTracking.tsx | ⏳ Pending | P2 | 10 min |
| OrderConfirmation.tsx | ⏳ Pending | P2 | 10 min |
| About.tsx | ⏳ Pending | P3 | 5 min |
| Contact.tsx | ⏳ Pending | P3 | 10 min |
| FAQ.tsx | ⏳ Pending | P3 | 5 min |
| Blog.tsx | ⏳ Pending | P3 | 10 min |
| BlogDetail.tsx | ⏳ Pending | P3 | 10 min |
| Privacy.tsx | ⏳ Pending | P4 | 5 min |
| Terms.tsx | ⏳ Pending | P4 | 5 min |
| ReturnPolicy.tsx | ⏳ Pending | P4 | 5 min |
| ShippingPolicy.tsx | ⏳ Pending | P4 | 5 min |
| NotFound.tsx | ⏳ Pending | P4 | 5 min |

**Total Progress: 10% (2/20 pages)**
**Estimated Time Remaining: ~3 hours**

---

## 🚀 Quick Commands

```bash
# Continue development
php artisan serve          # Terminal 1
npm run dev               # Terminal 2

# Test the homepage
# Visit: http://127.0.0.1:8000
```

---

## 💡 Tips

1. **Test as you go**: After updating each page, test it immediately
2. **Keep static data**: Keep fallback to static data until controllers are ready
3. **TypeScript**: Add proper TypeScript interfaces for props
4. **Commit often**: Commit after each page is updated
5. **Focus on P1 first**: Get core functionality working before content pages

---

**Status**: Step 7 - 5% Complete (1/20 pages)
**Next**: Update Shop.tsx
**Time**: ~15 minutes per page average
