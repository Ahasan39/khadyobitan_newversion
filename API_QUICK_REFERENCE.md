# 📡 Incomplete Orders API - Quick Reference

## Base URL
```
https://your-domain.com/api/v1
```

## 🔓 Authentication
**NONE REQUIRED** - All endpoints are public

---

## 📋 Endpoints

### 1. Create Order (Checkout Auto-save)
```
POST /incomplete-orders
```
**Body:**
```json
{
  "name": "John Doe",
  "phone": "01712345678",
  "address": "Dhaka",
  "cart_data": [{...}],
  "total_amount": 3500
}
```

### 2. Get All Orders (Admin List)
```
GET /incomplete-orders?status=pending&page=1&per_page=20
```

### 3. Get Statistics (Dashboard)
```
GET /incomplete-orders/statistics
```

### 4. Get Single Order
```
GET /incomplete-orders/{id}
```

### 5. Update Status
```
POST /incomplete-orders/{id}/update-status
Body: {"status": "contacted"}
```

### 6. Add Note
```
POST /incomplete-orders/{id}/add-note
Body: {"admin_note": "text"}
```

### 7. Mark Contacted
```
POST /incomplete-orders/{id}/mark-contacted
```

### 8. Delete Order
```
DELETE /incomplete-orders/{id}
```

### 9. Bulk Delete
```
POST /incomplete-orders/bulk-delete
Body: {"order_ids": [1,2,3]}
```

### 10. Bulk Update Status
```
POST /incomplete-orders/bulk-update-status
Body: {"order_ids": [1,2,3], "status": "contacted"}
```

---

## 🎯 Status Values
- `pending` - New order
- `contacted` - Admin contacted
- `converted` - Order completed
- `abandoned` - Not interested

---

## 📦 Quick Example
```javascript
// Create order
fetch('https://your-domain.com/api/v1/incomplete-orders', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    name: "John",
    phone: "01712345678",
    address: "Dhaka",
    cart_data: [],
    total_amount: 1000
  })
});

// Get all orders
fetch('https://your-domain.com/api/v1/incomplete-orders')
  .then(res => res.json())
  .then(data => console.log(data));
```

---

**Full Documentation**: `FRONTEND_DEVELOPER_API_GUIDE.md`
