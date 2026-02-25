# Pathao Bulk Courier Integration

## Overview
This feature adds one-click bulk order submission to Pathao courier service, similar to the existing Steadfast integration.

## Changes Made

### 1. Route Added (`routes/web.php`)
```php
Route::post('bulk-pathao', [OrderController::class, 'bulk_pathao'])->name('admin.bulk_pathao');
```

### 2. Controller Method (`app/Http/Controllers/Admin/OrderController.php`)
- Added `bulk_pathao()` method
- Auto-detects first available Pathao store
- Uses Dhaka (City ID: 1) as default city
- Auto-fetches first zone and area from Pathao API
- Sends orders with customer's shipping address

### 3. Frontend (`resources/views/backEnd/order/index.blade.php`)
- Added Pathao button in toolbar (same as Steadfast)
- Removed modal popup requirement
- One-click order submission
- Proper error handling with toastr notifications

## Features
- ✅ One-click bulk order submission
- ✅ Automatic location detection (Dhaka default)
- ✅ Customer address automatically included
- ✅ Success/error notifications
- ✅ Individual order error tracking

## Usage
1. Select orders from the list
2. Click "Pathao" button (yellow)
3. Orders are automatically sent to Pathao
4. Success message shows count of orders sent
5. Tracking IDs saved to orders

## Requirements
- Pathao API must be configured in Admin → API Integration
- At least one store must exist in Pathao merchant panel
- Orders must have valid shipping information

## Developer
**Ahasan39**

## Date
January 2025
