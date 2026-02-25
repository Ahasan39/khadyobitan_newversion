# 🚀 Incomplete Orders API - Frontend Developer Guide

## 📡 Base URL
```
https://your-domain.com/api/v1
```

**Local Development:**
```
http://localhost:8000/api/v1
```

---

## ✅ Authentication
**NO AUTHENTICATION REQUIRED** - All endpoints are public!

---

## 📋 API Endpoints

### 1. Create Incomplete Order (Auto-save from Checkout)

**Endpoint:** `POST /api/v1/incomplete-orders`

**Purpose:** Save incomplete order when customer fills checkout form

**When to Call:** 
- When customer types in checkout form (debounce 2-3 seconds)
- Before customer leaves checkout page
- When cart items change

**Request Body:**
```json
{
  "name": "John Doe",
  "phone": "01712345678",
  "address": "123 Main St, Dhaka, Bangladesh",
  "cart_data": [
    {
      "id": 1,
      "name": "Red Dress",
      "qty": 2,
      "price": 1500.00,
      "image": "/uploads/products/red-dress.jpg",
      "size": "M",
      "color": "Red"
    }
  ],
  "total_amount": 3500.00
}
```

**Response (Success - 201):**
```json
{
  "success": true,
  "message": "Incomplete order created successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "phone": "01712345678",
    "address": "123 Main St, Dhaka",
    "cart_data": [...],
    "total_amount": 3500.00,
    "status": "pending",
    "created_at": "2026-01-15T12:00:00.000000Z"
  }
}
```

**JavaScript Example:**
```javascript
// Auto-save incomplete order
async function saveIncompleteOrder(formData, cartItems, totalAmount) {
  try {
    const response = await fetch('https://your-domain.com/api/v1/incomplete-orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        name: formData.name,
        phone: formData.phone,
        address: formData.address,
        cart_data: cartItems,
        total_amount: totalAmount
      })
    });
    
    const data = await response.json();
    if (data.success) {
      console.log('Order saved:', data.data.id);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}
```

---

### 2. Get All Incomplete Orders (Admin Panel)

**Endpoint:** `GET /api/v1/incomplete-orders`

**Purpose:** Display list of incomplete orders in admin panel

**Query Parameters:**
- `status` (optional): Filter by status (pending, contacted, converted, abandoned)
- `keyword` (optional): Search by name or phone
- `start_date` (optional): Filter from date (YYYY-MM-DD)
- `end_date` (optional): Filter to date (YYYY-MM-DD)
- `per_page` (optional): Items per page (default: 15)
- `page` (optional): Page number (default: 1)

**Example Request:**
```
GET /api/v1/incomplete-orders?status=pending&per_page=20&page=1
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Incomplete orders retrieved successfully",
  "data": {
    "orders": [
      {
        "id": 1,
        "name": "John Doe",
        "phone": "01712345678",
        "address": "Dhaka",
        "status": "pending",
        "total_amount": 3500.00,
        "cart_data": [...],
        "created_at": "2026-01-15T12:00:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 15,
      "total": 75,
      "from": 1,
      "to": 15
    },
    "statistics": {
      "total": 100,
      "pending": 30,
      "contacted": 40,
      "converted": 20,
      "abandoned": 10
    }
  }
}
```

**JavaScript Example:**
```javascript
// Get incomplete orders with filters
async function getIncompleteOrders(filters = {}) {
  const params = new URLSearchParams(filters);
  
  const response = await fetch(
    `https://your-domain.com/api/v1/incomplete-orders?${params}`
  );
  
  const data = await response.json();
  return data.data;
}

// Usage
const result = await getIncompleteOrders({
  status: 'pending',
  per_page: 20,
  page: 1
});

console.log('Orders:', result.orders);
console.log('Stats:', result.statistics);
```

---

### 3. Get Statistics

**Endpoint:** `GET /api/v1/incomplete-orders/statistics`

**Purpose:** Get order counts for dashboard

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Statistics retrieved successfully",
  "data": {
    "total": 100,
    "pending": 30,
    "contacted": 40,
    "converted": 20,
    "abandoned": 10,
    "today": 5,
    "this_week": 25,
    "this_month": 80
  }
}
```

**JavaScript Example:**
```javascript
// Get statistics for dashboard
async function getStatistics() {
  const response = await fetch(
    'https://your-domain.com/api/v1/incomplete-orders/statistics'
  );
  
  const data = await response.json();
  return data.data;
}

// Usage
const stats = await getStatistics();
console.log('Pending:', stats.pending);
console.log('Today:', stats.today);
```

---

### 4. Get Single Order Details

**Endpoint:** `GET /api/v1/incomplete-orders/{id}`

**Purpose:** View detailed information about specific order

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Incomplete order retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "phone": "01712345678",
    "address": "123 Main St, Dhaka",
    "ip": "192.168.1.1",
    "status": "pending",
    "status_badge": "warning",
    "admin_note": "Customer interested in bulk order",
    "cart_data": [...],
    "total_amount": 3500.00,
    "contacted_at": null,
    "contacted_by": null,
    "created_at": "2026-01-15T12:00:00.000000Z",
    "formatted_created_at": "15 Jan 2026, 12:00 PM"
  }
}
```

**JavaScript Example:**
```javascript
// Get order details
async function getOrderDetails(orderId) {
  const response = await fetch(
    `https://your-domain.com/api/v1/incomplete-orders/${orderId}`
  );
  
  const data = await response.json();
  return data.data;
}

// Usage
const order = await getOrderDetails(1);
console.log('Order:', order);
```

---

### 5. Update Order Status

**Endpoint:** `POST /api/v1/incomplete-orders/{id}/update-status`

**Purpose:** Change order status (pending, contacted, converted, abandoned)

**Request Body:**
```json
{
  "status": "contacted"
}
```

**Valid Status Values:**
- `pending` - New order, needs attention
- `contacted` - Admin contacted customer
- `converted` - Order completed
- `abandoned` - Customer not interested

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Status updated successfully",
  "data": {
    "id": 1,
    "status": "contacted",
    "contacted_at": "2026-01-15T14:00:00.000000Z"
  }
}
```

**JavaScript Example:**
```javascript
// Update order status
async function updateOrderStatus(orderId, status) {
  const response = await fetch(
    `https://your-domain.com/api/v1/incomplete-orders/${orderId}/update-status`,
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ status })
    }
  );
  
  const data = await response.json();
  return data;
}

// Usage
await updateOrderStatus(1, 'contacted');
```

---

### 6. Add Admin Note

**Endpoint:** `POST /api/v1/incomplete-orders/{id}/add-note`

**Purpose:** Add or update admin note for order

**Request Body:**
```json
{
  "admin_note": "Customer wants to order next week. Follow up on Monday."
}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Note added successfully",
  "data": {
    "id": 1,
    "admin_note": "Customer wants to order next week..."
  }
}
```

**JavaScript Example:**
```javascript
// Add admin note
async function addNote(orderId, note) {
  const response = await fetch(
    `https://your-domain.com/api/v1/incomplete-orders/${orderId}/add-note`,
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ admin_note: note })
    }
  );
  
  return await response.json();
}

// Usage
await addNote(1, 'Customer interested in bulk order');
```

---

### 7. Mark as Contacted

**Endpoint:** `POST /api/v1/incomplete-orders/{id}/mark-contacted`

**Purpose:** Mark order as contacted (updates timestamp)

**Request Body:** None required

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Marked as contacted successfully",
  "data": {
    "id": 1,
    "status": "contacted",
    "contacted_at": "2026-01-15T14:00:00.000000Z"
  }
}
```

**JavaScript Example:**
```javascript
// Mark as contacted
async function markAsContacted(orderId) {
  const response = await fetch(
    `https://your-domain.com/api/v1/incomplete-orders/${orderId}/mark-contacted`,
    {
      method: 'POST'
    }
  );
  
  return await response.json();
}

// Usage
await markAsContacted(1);
```

---

### 8. Delete Order

**Endpoint:** `DELETE /api/v1/incomplete-orders/{id}`

**Purpose:** Delete specific incomplete order

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Incomplete order deleted successfully"
}
```

**JavaScript Example:**
```javascript
// Delete order
async function deleteOrder(orderId) {
  const response = await fetch(
    `https://your-domain.com/api/v1/incomplete-orders/${orderId}`,
    {
      method: 'DELETE'
    }
  );
  
  return await response.json();
}

// Usage
await deleteOrder(1);
```

---

### 9. Bulk Delete Orders

**Endpoint:** `POST /api/v1/incomplete-orders/bulk-delete`

**Purpose:** Delete multiple orders at once

**Request Body:**
```json
{
  "order_ids": [1, 2, 3, 4, 5]
}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "5 incomplete order(s) deleted successfully",
  "data": {
    "deleted_count": 5
  }
}
```

**JavaScript Example:**
```javascript
// Bulk delete
async function bulkDeleteOrders(orderIds) {
  const response = await fetch(
    'https://your-domain.com/api/v1/incomplete-orders/bulk-delete',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ order_ids: orderIds })
    }
  );
  
  return await response.json();
}

// Usage
await bulkDeleteOrders([1, 2, 3]);
```

---

### 10. Bulk Update Status

**Endpoint:** `POST /api/v1/incomplete-orders/bulk-update-status`

**Purpose:** Update status for multiple orders

**Request Body:**
```json
{
  "order_ids": [1, 2, 3],
  "status": "contacted"
}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "3 incomplete order(s) status updated successfully",
  "data": {
    "updated_count": 3,
    "new_status": "contacted"
  }
}
```

**JavaScript Example:**
```javascript
// Bulk update status
async function bulkUpdateStatus(orderIds, status) {
  const response = await fetch(
    'https://your-domain.com/api/v1/incomplete-orders/bulk-update-status',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        order_ids: orderIds,
        status: status
      })
    }
  );
  
  return await response.json();
}

// Usage
await bulkUpdateStatus([1, 2, 3], 'contacted');
```

---

## 🎯 Implementation Guide

### For Checkout Page (Auto-save)

```javascript
// Debounce function
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func(...args), wait);
  };
}

// Auto-save function
const autoSaveOrder = debounce(async (formData, cart, total) => {
  try {
    await fetch('https://your-domain.com/api/v1/incomplete-orders', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: formData.name,
        phone: formData.phone,
        address: formData.address,
        cart_data: cart,
        total_amount: total
      })
    });
  } catch (error) {
    console.error('Auto-save failed:', error);
  }
}, 2000); // Wait 2 seconds after typing stops

// Attach to form inputs
document.querySelectorAll('#checkout-form input').forEach(input => {
  input.addEventListener('input', () => {
    const formData = getFormData();
    const cart = getCartItems();
    const total = calculateTotal();
    autoSaveOrder(formData, cart, total);
  });
});

// Save before leaving page
window.addEventListener('beforeunload', () => {
  const formData = getFormData();
  const cart = getCartItems();
  const total = calculateTotal();
  
  // Use sendBeacon for reliable sending
  navigator.sendBeacon(
    'https://your-domain.com/api/v1/incomplete-orders',
    JSON.stringify({
      name: formData.name,
      phone: formData.phone,
      address: formData.address,
      cart_data: cart,
      total_amount: total
    })
  );
});
```

---

### For Admin Panel (List Orders)

```javascript
// React/Vue/Angular example
async function loadIncompleteOrders(page = 1, filters = {}) {
  const params = new URLSearchParams({
    page,
    per_page: 20,
    ...filters
  });
  
  const response = await fetch(
    `https://your-domain.com/api/v1/incomplete-orders?${params}`
  );
  
  const result = await response.json();
  
  if (result.success) {
    // Update UI with orders
    displayOrders(result.data.orders);
    displayPagination(result.data.pagination);
    displayStatistics(result.data.statistics);
  }
}

// Load on page mount
loadIncompleteOrders();
```

---

## 📦 Complete React Example

```javascript
import { useState, useEffect } from 'react';

const API_BASE = 'https://your-domain.com/api/v1';

function IncompleteOrdersPage() {
  const [orders, setOrders] = useState([]);
  const [stats, setStats] = useState({});
  const [loading, setLoading] = useState(true);
  const [filters, setFilters] = useState({
    status: '',
    keyword: '',
    page: 1
  });

  // Fetch orders
  useEffect(() => {
    fetchOrders();
  }, [filters]);

  const fetchOrders = async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams(filters);
      const response = await fetch(`${API_BASE}/incomplete-orders?${params}`);
      const data = await response.json();
      
      if (data.success) {
        setOrders(data.data.orders);
        setStats(data.data.statistics);
      }
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  const updateStatus = async (orderId, status) => {
    try {
      const response = await fetch(
        `${API_BASE}/incomplete-orders/${orderId}/update-status`,
        {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ status })
        }
      );
      
      const data = await response.json();
      if (data.success) {
        fetchOrders(); // Refresh list
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  return (
    <div>
      <h1>Incomplete Orders</h1>
      
      {/* Statistics */}
      <div className="stats">
        <div>Total: {stats.total}</div>
        <div>Pending: {stats.pending}</div>
        <div>Contacted: {stats.contacted}</div>
      </div>

      {/* Filters */}
      <select onChange={(e) => setFilters({...filters, status: e.target.value})}>
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="contacted">Contacted</option>
      </select>

      {/* Orders List */}
      {loading ? (
        <div>Loading...</div>
      ) : (
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {orders.map(order => (
              <tr key={order.id}>
                <td>{order.id}</td>
                <td>{order.name}</td>
                <td>{order.phone}</td>
                <td>৳{order.total_amount}</td>
                <td>{order.status}</td>
                <td>
                  <button onClick={() => updateStatus(order.id, 'contacted')}>
                    Mark Contacted
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}

export default IncompleteOrdersPage;
```

---

## 🔧 Error Handling

```javascript
async function apiCall(url, options = {}) {
  try {
    const response = await fetch(url, options);
    const data = await response.json();
    
    if (!data.success) {
      throw new Error(data.message || 'API request failed');
    }
    
    return data;
  } catch (error) {
    console.error('API Error:', error);
    // Show error to user
    alert('Error: ' + error.message);
    throw error;
  }
}

// Usage
try {
  const result = await apiCall('https://your-domain.com/api/v1/incomplete-orders');
  console.log('Orders:', result.data);
} catch (error) {
  // Handle error
}
```

---

## 📝 Summary for Frontend Developer

### What to Give Them:

1. **Base URL**: `https://your-domain.com/api/v1`

2. **Main Endpoints**:
   - `POST /incomplete-orders` - Auto-save from checkout
   - `GET /incomplete-orders` - List orders (admin)
   - `GET /incomplete-orders/statistics` - Dashboard stats
   - `GET /incomplete-orders/{id}` - Order details
   - `POST /incomplete-orders/{id}/update-status` - Update status
   - `POST /incomplete-orders/{id}/add-note` - Add note
   - `DELETE /incomplete-orders/{id}` - Delete order

3. **Key Points**:
   - ✅ No authentication required
   - ✅ All endpoints are public
   - ✅ Returns JSON
   - ✅ Standard HTTP methods
   - ✅ Clear error messages

4. **This Document**: `FRONTEND_DEVELOPER_API_GUIDE.md`

---

**Give them this file and they have everything they need!** 🚀
