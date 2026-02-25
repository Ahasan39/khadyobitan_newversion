# 🌐 Browser Testing Guide - Incomplete Orders API

## ✅ Easy Ways to Test API in Browser

---

## 🎯 Method 1: HTML Test Page (Easiest!)

### Step 1: Access the Test Page

Open your browser and go to:
```
http://your-domain.com/test-api.html
```

Or if testing locally:
```
http://localhost:8000/test-api.html
```

### Step 2: Test Public Endpoint (No Auth Required)

1. **Scroll to "Create Incomplete Order" section**
2. **Fill in the form** (or use pre-filled data):
   - Customer Name: John Doe
   - Phone: 01712345678
   - Address: Dhaka, Bangladesh
   - Cart Data: (JSON already filled)
   - Total Amount: 3500
3. **Click "🚀 Create Order" button**
4. **See the response** below the button

**Expected Response:**
```json
{
  "success": true,
  "message": "Incomplete order created successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "phone": "01712345678",
    ...
  }
}
```

### Step 3: Get JWT Token (For Protected Endpoints)

**Option A: From Admin Panel**
1. Login to your admin panel
2. Open browser console (F12)
3. Type: `localStorage.getItem('jwt_token')`
4. Copy the token

**Option B: From Login API**
```javascript
// Open browser console and run:
fetch('http://your-domain.com/api/v1/customer/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    phone: '01712345678',
    password: 'your-password'
  })
})
.then(res => res.json())
.then(data => console.log('Token:', data.token));
```

### Step 4: Test Protected Endpoints

1. **Paste JWT token** in the "JWT Token" field (section 2)
2. **Click "📊 Get Statistics"**
3. **See the response** with order counts

4. **Try other endpoints**:
   - Get All Orders
   - Get Single Order
   - Update Status
   - Add Note
   - Mark as Contacted
   - Delete Order

---

## 🎯 Method 2: Browser Console (Quick Test)

### Open Browser Console
- **Chrome/Edge**: Press `F12` or `Ctrl+Shift+J`
- **Firefox**: Press `F12` or `Ctrl+Shift+K`
- **Safari**: Press `Cmd+Option+C`

### Test Public Endpoint (Create Order)

```javascript
// Copy and paste this in console:
fetch('http://your-domain.com/api/v1/incomplete-orders', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    name: "John Doe",
    phone: "01712345678",
    address: "Dhaka, Bangladesh",
    cart_data: [
      {
        id: 1,
        name: "Red Dress",
        qty: 2,
        price: 1500.00,
        image: "/uploads/products/red-dress.jpg",
        size: "M",
        color: "Red"
      }
    ],
    total_amount: 3000.00
  })
})
.then(res => res.json())
.then(data => console.log('Response:', data))
.catch(err => console.error('Error:', err));
```

### Test Protected Endpoint (Get Statistics)

```javascript
// Replace YOUR_JWT_TOKEN with actual token
const token = 'YOUR_JWT_TOKEN';

fetch('http://your-domain.com/api/v1/incomplete-orders/statistics', {
  headers: {
    'Authorization': `Bearer ${token}`
  }
})
.then(res => res.json())
.then(data => console.log('Statistics:', data))
.catch(err => console.error('Error:', err));
```

### Test Get All Orders

```javascript
const token = 'YOUR_JWT_TOKEN';

fetch('http://your-domain.com/api/v1/incomplete-orders?status=pending&per_page=10', {
  headers: {
    'Authorization': `Bearer ${token}`
  }
})
.then(res => res.json())
.then(data => {
  console.log('Orders:', data.data.orders);
  console.log('Statistics:', data.data.statistics);
})
.catch(err => console.error('Error:', err));
```

---

## 🎯 Method 3: Direct URL (GET Requests Only)

### Test in Browser Address Bar

**Get Statistics** (requires JWT token in URL - not recommended for production):
```
http://your-domain.com/api/v1/incomplete-orders/statistics
```

**Note**: For GET requests with authentication, you'll need to use Method 1 or 2, as browsers don't allow setting custom headers in the address bar.

---

## 🎯 Method 4: Browser Extensions

### Using REST Client Extensions

**Chrome/Edge:**
- Install "Talend API Tester" or "Advanced REST Client"
- Create new request
- Set URL, method, headers, body
- Send request

**Firefox:**
- Install "RESTClient"
- Similar process as above

---

## 📋 Testing Checklist

### Public Endpoint (No Auth)
- [ ] Create incomplete order
- [ ] Verify response has `success: true`
- [ ] Check order ID is returned
- [ ] Verify cart data is saved

### Protected Endpoints (With JWT)
- [ ] Get statistics
- [ ] Get all orders
- [ ] Get single order
- [ ] Update order status
- [ ] Add admin note
- [ ] Mark as contacted
- [ ] Delete order

---

## ���� What to Look For

### Success Response
```json
{
  "success": true,
  "message": "Action completed successfully",
  "data": { ... }
}
```
✅ Green background in test page  
✅ `success: true` in response  
✅ Data object present  

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```
❌ Red background in test page  
❌ `success: false` in response  
❌ Error message explaining issue  

---

## 🐛 Common Issues & Solutions

### Issue 1: CORS Error
**Error**: "Access to fetch has been blocked by CORS policy"

**Solution**:
1. Check `config/cors.php`
2. Add your domain to `allowed_origins`
3. Or set `'allowed_origins' => ['*']` for testing

### Issue 2: 401 Unauthorized
**Error**: `{"success": false, "message": "Unauthenticated"}`

**Solution**:
- Check JWT token is valid
- Verify token is included in Authorization header
- Get fresh token from login API

### Issue 3: 404 Not Found
**Error**: "404 Not Found"

**Solution**:
- Verify URL is correct
- Check route is registered: `php artisan route:list | findstr incomplete`
- Clear route cache: `php artisan route:clear`

### Issue 4: 422 Validation Error
**Error**: `{"success": false, "message": "Validation failed"}`

**Solution**:
- Check all required fields are provided
- Verify data types match requirements
- Check JSON format is valid

### Issue 5: 500 Server Error
**Error**: `{"success": false, "message": "Failed to..."}`

**Solution**:
- Check Laravel logs: `storage/logs/laravel.log`
- Enable debug mode: `APP_DEBUG=true` in `.env`
- Verify database connection

---

## 💡 Pro Tips

### Tip 1: Save JWT Token
```javascript
// In browser console
localStorage.setItem('jwt_token', 'your-token-here');

// Retrieve later
const token = localStorage.getItem('jwt_token');
```

### Tip 2: Quick Test Function
```javascript
// Add to console for quick testing
async function testAPI(endpoint, method = 'GET', body = null) {
  const token = localStorage.getItem('jwt_token');
  const options = {
    method,
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  };
  
  if (body) {
    options.body = JSON.stringify(body);
  }
  
  const response = await fetch(`http://your-domain.com/api/v1${endpoint}`, options);
  const data = await response.json();
  console.log(data);
  return data;
}

// Usage:
testAPI('/incomplete-orders/statistics');
testAPI('/incomplete-orders', 'GET');
testAPI('/incomplete-orders/1/update-status', 'POST', { status: 'contacted' });
```

### Tip 3: Monitor Network Tab
1. Open DevTools (F12)
2. Go to "Network" tab
3. Filter by "Fetch/XHR"
4. See all API requests and responses

---

## 📊 Example Test Scenarios

### Scenario 1: Complete Flow Test

```javascript
// 1. Create incomplete order
const createResponse = await fetch('http://your-domain.com/api/v1/incomplete-orders', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    name: "Test Customer",
    phone: "01712345678",
    address: "Test Address",
    cart_data: [{ id: 1, name: "Product", qty: 1, price: 1000 }],
    total_amount: 1000
  })
}).then(res => res.json());

console.log('Created:', createResponse);
const orderId = createResponse.data.id;

// 2. Get the order
const token = 'YOUR_JWT_TOKEN';
const getResponse = await fetch(`http://your-domain.com/api/v1/incomplete-orders/${orderId}`, {
  headers: { 'Authorization': `Bearer ${token}` }
}).then(res => res.json());

console.log('Retrieved:', getResponse);

// 3. Update status
const updateResponse = await fetch(`http://your-domain.com/api/v1/incomplete-orders/${orderId}/update-status`, {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({ status: 'contacted' })
}).then(res => res.json());

console.log('Updated:', updateResponse);
```

---

## 🎓 Video Tutorial Steps

### For Complete Beginners:

1. **Open your project in browser**
   - Type: `http://localhost:8000/test-api.html`
   - Press Enter

2. **See the test page**
   - Beautiful interface with forms
   - Multiple test sections

3. **Test first endpoint**
   - Scroll to "Create Incomplete Order"
   - Click "🚀 Create Order" button
   - See green box with response

4. **Get JWT token**
   - Login to admin panel
   - Press F12 (open console)
   - Type: `localStorage.getItem('jwt_token')`
   - Copy the token

5. **Test protected endpoints**
   - Paste token in "JWT Token" field
   - Click any test button
   - See responses

---

## 📞 Quick Reference

### Test Page URL
```
http://your-domain.com/test-api.html
```

### API Base URL
```
http://your-domain.com/api/v1
```

### Public Endpoint (No Auth)
```
POST /api/v1/incomplete-orders
```

### Protected Endpoints (JWT Required)
```
GET    /api/v1/incomplete-orders
GET    /api/v1/incomplete-orders/statistics
GET    /api/v1/incomplete-orders/{id}
POST   /api/v1/incomplete-orders/{id}/update-status
POST   /api/v1/incomplete-orders/{id}/add-note
POST   /api/v1/incomplete-orders/{id}/mark-contacted
DELETE /api/v1/incomplete-orders/{id}
```

---

## ✅ Success Indicators

### API is Working If:
- ✅ Test page loads without errors
- ✅ Create order returns `success: true`
- ✅ Order ID is returned in response
- ✅ Statistics show correct counts
- ✅ Can retrieve created orders
- ✅ Status updates work
- ✅ Notes can be added

### API Has Issues If:
- ❌ 404 errors (routes not found)
- ❌ 500 errors (server errors)
- ❌ CORS errors (cross-origin issues)
- ❌ 401 errors (authentication issues)
- ❌ No response (server not running)

---

## 🚀 Start Testing Now!

1. **Open**: `http://your-domain.com/test-api.html`
2. **Test**: Click "🚀 Create Order"
3. **Verify**: See green success response
4. **Done**: API is working! ✅

---

**Happy Testing!** 🎉

**File Location**: `public/test-api.html`  
**Access URL**: `http://your-domain.com/test-api.html`  
**Status**: ✅ Ready to Use
