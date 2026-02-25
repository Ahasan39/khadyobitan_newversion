# 📡 Incomplete Orders API Documentation

## Base URL
```
https://your-domain.com/api/v1
```

---

## 🔐 Authentication

### Public Endpoints
- `POST /incomplete-orders` - Create incomplete order (no auth required)

### Protected Endpoints (Require JWT Token)
All other endpoints require JWT authentication. Include the token in the header:

```
Authorization: Bearer {your-jwt-token}
```

---

## 📋 API Endpoints

### 1. Create Incomplete Order (Public)

**Endpoint:** `POST /api/v1/incomplete-orders`

**Description:** Create or update an incomplete order from frontend checkout

**Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "John Doe",
  "phone": "01712345678",
  "address": "123 Main St, Dhaka",
  "cart_data": [
    {
      "id": 1,
      "name": "Red Dress",
      "qty": 2,
      "price": 1500.00,
      "image": "/uploads/products/red-dress.jpg",
      "size": "M",
      "color": "Red"
    },
    {
      "id": 2,
      "name": "Blue Scarf",
      "qty": 1,
      "price": 500.00,
      "image": "/uploads/products/blue-scarf.jpg",
      "size": null,
      "color": "Blue"
    }
  ],
  "total_amount": 3500.00
}
```

**Success Response (201 Created):**
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
    "ip": "192.168.1.1",
    "created_at": "2026-01-15T12:00:00.000000Z",
    "updated_at": "2026-01-15T12:00:00.000000Z"
  }
}
```

**Update Response (200 OK):**
```json
{
  "success": true,
  "message": "Incomplete order updated successfully",
  "data": {...}
}
```

**Error Response (422 Validation Error):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": ["The name field is required."],
    "phone": ["The phone field is required."]
  }
}
```

---

### 2. Get All Incomplete Orders (Protected)

**Endpoint:** `GET /api/v1/incomplete-orders`

**Description:** Get paginated list of incomplete orders with filters

**Headers:**
```
Authorization: Bearer {your-jwt-token}
Content-Type: application/json
```

**Query Parameters:**
- `status` (optional): Filter by status (pending, contacted, converted, abandoned)
- `keyword` (optional): Search by name or phone
- `start_date` (optional): Filter from date (YYYY-MM-DD)
- `end_date` (optional): Filter to date (YYYY-MM-DD)
- `per_page` (optional): Items per page (default: 15)
- `page` (optional): Page number (default: 1)

**Example Request:**
```
GET /api/v1/incomplete-orders?status=pending&keyword=john&per_page=20&page=1
```

**Success Response (200 OK):**
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
        "address": "123 Main St, Dhaka",
        "ip": "192.168.1.1",
        "status": "pending",
        "status_badge": "warning",
        "admin_note": null,
        "cart_data": [...],
        "total_amount": 3500.00,
        "contacted_at": null,
        "contacted_by": null,
        "created_at": "2026-01-15T12:00:00.000000Z",
        "updated_at": "2026-01-15T12:00:00.000000Z",
        "contacted_by_user": null
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

---

### 3. Get Single Incomplete Order (Protected)

**Endpoint:** `GET /api/v1/incomplete-orders/{id}`

**Description:** Get detailed information about a specific incomplete order

**Headers:**
```
Authorization: Bearer {your-jwt-token}
```

**Success Response (200 OK):**
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
    "total_amount": 3500.00,
    "contacted_at": "2026-01-15T14:00:00.000000Z",
    "contacted_by": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com"
    },
    "created_at": "2026-01-15T12:00:00.000000Z",
    "updated_at": "2026-01-15T14:00:00.000000Z",
    "formatted_created_at": "15 Jan 2026, 12:00 PM",
    "formatted_contacted_at": "15 Jan 2026, 02:00 PM"
  }
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Incomplete order not found"
}
```

---

### 4. Update Order Status (Protected)

**Endpoint:** `POST /api/v1/incomplete-orders/{id}/update-status`

**Description:** Update the status of an incomplete order

**Headers:**
```
Authorization: Bearer {your-jwt-token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "status": "contacted"
}
```

**Valid Status Values:**
- `pending`
- `contacted`
- `converted`
- `abandoned`

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Status updated successfully",
  "data": {
    "id": 1,
    "status": "contacted",
    "contacted_at": "2026-01-15T14:00:00.000000Z",
    "contacted_by": 1,
    ...
  }
}
```

---

### 5. Add Admin Note (Protected)

**Endpoint:** `POST /api/v1/incomplete-orders/{id}/add-note`

**Description:** Add or update admin note for an incomplete order

**Headers:**
```
Authorization: Bearer {your-jwt-token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "admin_note": "Customer wants to order next week. Follow up on Monday."
}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Note added successfully",
  "data": {
    "id": 1,
    "admin_note": "Customer wants to order next week. Follow up on Monday.",
    ...
  }
}
```

---

### 6. Mark as Contacted (Protected)

**Endpoint:** `POST /api/v1/incomplete-orders/{id}/mark-contacted`

**Description:** Mark an incomplete order as contacted

**Headers:**
```
Authorization: Bearer {your-jwt-token}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Marked as contacted successfully",
  "data": {
    "id": 1,
    "status": "contacted",
    "contacted_at": "2026-01-15T14:00:00.000000Z",
    "contacted_by": 1,
    ...
  }
}
```

---

### 7. Delete Incomplete Order (Protected)

**Endpoint:** `DELETE /api/v1/incomplete-orders/{id}`

**Description:** Delete a specific incomplete order

**Headers:**
```
Authorization: Bearer {your-jwt-token}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Incomplete order deleted successfully"
}
```

---

### 8. Bulk Delete Orders (Protected)

**Endpoint:** `POST /api/v1/incomplete-orders/bulk-delete`

**Description:** Delete multiple incomplete orders at once

**Headers:**
```
Authorization: Bearer {your-jwt-token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "order_ids": [1, 2, 3, 4, 5]
}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "5 incomplete order(s) deleted successfully",
  "data": {
    "deleted_count": 5
  }
}
```

---

### 9. Bulk Update Status (Protected)

**Endpoint:** `POST /api/v1/incomplete-orders/bulk-update-status`

**Description:** Update status for multiple incomplete orders at once

**Headers:**
```
Authorization: Bearer {your-jwt-token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "order_ids": [1, 2, 3],
  "status": "contacted"
}
```

**Success Response (200 OK):**
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

---

### 10. Get Statistics (Protected)

**Endpoint:** `GET /api/v1/incomplete-orders/statistics`

**Description:** Get statistics about incomplete orders

**Headers:**
```
Authorization: Bearer {your-jwt-token}
```

**Success Response (200 OK):**
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

---

## 🔄 Status Values

| Status | Description | Badge Color |
|--------|-------------|-------------|
| `pending` | New incomplete order, needs attention | warning (yellow) |
| `contacted` | Admin has contacted the customer | info (blue) |
| `converted` | Customer completed the order | success (green) |
| `abandoned` | Customer not interested or unreachable | danger (red) |

---

## 📦 Cart Data Structure

```json
{
  "id": 1,
  "name": "Product Name",
  "qty": 2,
  "price": 1500.00,
  "image": "/uploads/products/image.jpg",
  "size": "M",
  "color": "Red"
}
```

**Fields:**
- `id` (integer): Product ID
- `name` (string): Product name
- `qty` (integer): Quantity
- `price` (decimal): Price per unit
- `image` (string): Product image path
- `size` (string|null): Product size (if applicable)
- `color` (string|null): Product color (if applicable)

---

## ❌ Error Responses

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Incomplete order not found"
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 500 Server Error
```json
{
  "success": false,
  "message": "Failed to perform action",
  "error": "Detailed error message"
}
```

---

## 🔧 Frontend Integration Examples

### JavaScript/Fetch Example

#### Create Incomplete Order (Public)
```javascript
// When customer fills checkout form
const saveIncompleteOrder = async (formData, cartItems) => {
  try {
    const response = await fetch('https://your-domain.com/api/v1/incomplete-orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: formData.name,
        phone: formData.phone,
        address: formData.address,
        cart_data: cartItems,
        total_amount: calculateTotal(cartItems)
      })
    });

    const data = await response.json();
    
    if (data.success) {
      console.log('Incomplete order saved:', data.data);
    }
  } catch (error) {
    console.error('Error saving incomplete order:', error);
  }
};

// Auto-save on form input
let saveTimeout;
document.querySelectorAll('#checkout-form input').forEach(input => {
  input.addEventListener('input', () => {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
      const formData = getFormData();
      const cartItems = getCartItems();
      saveIncompleteOrder(formData, cartItems);
    }, 2000); // Save after 2 seconds of inactivity
  });
});
```

#### Get All Orders (Protected)
```javascript
const getIncompleteOrders = async (filters = {}) => {
  const token = localStorage.getItem('jwt_token');
  const queryParams = new URLSearchParams(filters).toString();
  
  try {
    const response = await fetch(
      `https://your-domain.com/api/v1/incomplete-orders?${queryParams}`,
      {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      }
    );

    const data = await response.json();
    
    if (data.success) {
      return data.data;
    }
  } catch (error) {
    console.error('Error fetching orders:', error);
  }
};

// Usage
const orders = await getIncompleteOrders({
  status: 'pending',
  per_page: 20,
  page: 1
});
```

#### Update Status (Protected)
```javascript
const updateOrderStatus = async (orderId, status) => {
  const token = localStorage.getItem('jwt_token');
  
  try {
    const response = await fetch(
      `https://your-domain.com/api/v1/incomplete-orders/${orderId}/update-status`,
      {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status })
      }
    );

    const data = await response.json();
    
    if (data.success) {
      console.log('Status updated:', data.data);
      return data.data;
    }
  } catch (error) {
    console.error('Error updating status:', error);
  }
};

// Usage
await updateOrderStatus(1, 'contacted');
```

### React/Axios Example

```javascript
import axios from 'axios';

const API_BASE_URL = 'https://your-domain.com/api/v1';

// Create axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
});

// Add auth token to requests
api.interceptors.request.use(config => {
  const token = localStorage.getItem('jwt_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Get all incomplete orders
export const getIncompleteOrders = async (params) => {
  const response = await api.get('/incomplete-orders', { params });
  return response.data;
};

// Get single order
export const getIncompleteOrder = async (id) => {
  const response = await api.get(`/incomplete-orders/${id}`);
  return response.data;
};

// Create incomplete order (public)
export const createIncompleteOrder = async (data) => {
  const response = await axios.post(
    `${API_BASE_URL}/incomplete-orders`,
    data
  );
  return response.data;
};

// Update status
export const updateOrderStatus = async (id, status) => {
  const response = await api.post(
    `/incomplete-orders/${id}/update-status`,
    { status }
  );
  return response.data;
};

// Add note
export const addOrderNote = async (id, note) => {
  const response = await api.post(
    `/incomplete-orders/${id}/add-note`,
    { admin_note: note }
  );
  return response.data;
};

// Mark as contacted
export const markAsContacted = async (id) => {
  const response = await api.post(
    `/incomplete-orders/${id}/mark-contacted`
  );
  return response.data;
};

// Delete order
export const deleteOrder = async (id) => {
  const response = await api.delete(`/incomplete-orders/${id}`);
  return response.data;
};

// Bulk operations
export const bulkDeleteOrders = async (orderIds) => {
  const response = await api.post('/incomplete-orders/bulk-delete', {
    order_ids: orderIds
  });
  return response.data;
};

export const bulkUpdateStatus = async (orderIds, status) => {
  const response = await api.post('/incomplete-orders/bulk-update-status', {
    order_ids: orderIds,
    status
  });
  return response.data;
};

// Get statistics
export const getStatistics = async () => {
  const response = await api.get('/incomplete-orders/statistics');
  return response.data;
};
```

---

## 🧪 Testing with Postman/cURL

### Create Incomplete Order
```bash
curl -X POST https://your-domain.com/api/v1/incomplete-orders \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "phone": "01712345678",
    "address": "Dhaka, Bangladesh",
    "cart_data": [
      {
        "id": 1,
        "name": "Product 1",
        "qty": 2,
        "price": 1500.00
      }
    ],
    "total_amount": 3000.00
  }'
```

### Get All Orders (with auth)
```bash
curl -X GET "https://your-domain.com/api/v1/incomplete-orders?status=pending" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Update Status
```bash
curl -X POST https://your-domain.com/api/v1/incomplete-orders/1/update-status \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status": "contacted"}'
```

---

## 📝 Notes

1. **Rate Limiting**: API is rate-limited to 200 requests per minute
2. **Pagination**: Default page size is 15 items, maximum is 100
3. **Timestamps**: All timestamps are in UTC format
4. **Phone Numbers**: Should be unique for pending orders
5. **Cart Data**: Stored as JSON, can contain any product information
6. **Auto-Update**: If a pending order exists for a phone number, it will be updated instead of creating a new one

---

## 🔒 Security

- All admin endpoints require JWT authentication
- CSRF protection is disabled for API routes
- Input validation on all endpoints
- SQL injection protection via Eloquent ORM
- XSS protection via Laravel sanitization

---

## 📞 Support

For API issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Enable debug mode in `.env`: `APP_DEBUG=true`
- Contact technical support

---

**API Version**: 1.0.0  
**Last Updated**: January 15, 2026  
**Status**: Production Ready ✅
