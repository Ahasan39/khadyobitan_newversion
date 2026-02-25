@extends('homepageone::frontend.layouts.app')
  <style>
    body {
      background: #fff;
      font-family: 'SolaimanLipi', Arial, sans-serif;
    }
    .checkout-box {
      max-width: 450px;
      margin: 50px auto;
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(255, 0, 153, 0.5);
      border: 1px solid #ffe6f5;
    }
    .checkout-box h5 {
      text-align: center;
      color: #ff0099;
      margin-bottom: 20px;
      font-weight: bold;
    }
    .checkout-box input, 
    .checkout-box textarea {
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .checkout-box .form-check {
      margin-bottom: 10px;
    }
    .product-summary {
      border: 1px solid #000;
      margin-top: 15px;
    }
    .product-summary img {
      max-width: 80px;
    }
    .order-btn {
      background: #ff0099;
      color: #fff;
      font-size: 18px;
      font-weight: bold;
      margin-top: 20px;
      border-radius: 6px;
      padding: 12px;
      transition: all 0.3s ease-in-out;
    }
    .order-btn:hover {
      background: #c7007f;
      transform: scale(1.03);
    }
    .footer-note {
      text-align: center;
      font-size: 13px;
      color: #555;
      margin-top: 15px;
    }
  </style>
@section('content')

<div class="checkout-box">
  <h5>✈ ডেলিভারি পেতে নিচের তথ্যগুলি দিন</h5>
  
  <form>
    <!-- Name -->
    <div class="mb-3">
      <label class="form-label">সম্পূর্ণ নাম *</label>
      <input type="text" class="form-control" placeholder="সম্পূর্ণ নাম">
    </div>
    
    <!-- Phone -->
    <div class="mb-3">
      <label class="form-label">মোবাইল নাম্বার *</label>
      <input type="text" class="form-control" placeholder="মোবাইল নাম্বার">
    </div>
    
    <!-- Address -->
    <div class="mb-3">
      <label class="form-label">ডেলিভারি ঠিকানা *</label>
      <textarea class="form-control" rows="2" placeholder="ডেলিভারি ঠিকানা"></textarea>
    </div>
    
    <!-- Note -->
    <div class="mb-3">
      <label class="form-label">নোট</label>
      <textarea class="form-control" rows="2" placeholder="নোট"></textarea>
    </div>
    
    <!-- Delivery charge -->
    <label class="form-label">ডেলিভারি এরিয়া এবং চার্জ নির্ধারণ করুন</label>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="delivery" id="outside" checked>
      <label class="form-check-label" for="outside">
        ঢাকা সিটির বাইরে [150.00 ৳]
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="delivery" id="inside">
      <label class="form-check-label" for="inside">
        ঢাকা সিটির ভিতরে [80.00 ৳]
      </label>
    </div>
    
    <!-- Product summary -->
    <div class="product-summary p-2">
      <div class="d-flex align-items-center">
        <img src="https://via.placeholder.com/80" alt="Product" class="me-2">
        <div>
          <p class="mb-1">৭ পিস এর 3D প্রিন্টেড ডাইনিং টেবিল কভার</p>
          <small>মূল্য: 1,250৳ X 1</small><br>
          <strong>৳ 1,250.00</strong>
        </div>
      </div>
      <hr>
      <div class="d-flex justify-content-between">
        <span>মোট:</span>
        <strong>৳ 3,600.00</strong>
      </div>
    </div>
    
    <!-- Submit -->
    <button type="submit" class="w-100 order-btn">অর্ডার সম্পূর্ণ করুন ৳ 3,600 ➝</button>
  </form>
  
  <div class="footer-note">
    নগদ-ইত্যাদি/রকেট/বিকাশ করতে এখানে ক্লিক করুন
  </div>
</div>
@endsection