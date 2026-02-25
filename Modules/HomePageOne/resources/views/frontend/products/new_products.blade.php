<section class="new-products">
        <div class="container">
            <!-- Section Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">নতুন পণ্য</h2>
                <a href="#" class="see-all-btn">সবগুলো দেখুন</a>
            </div>

            <!-- Product Grid -->
            <div class="row g-3">
                <!-- Product Card 1 -->
                 @foreach ($new_arrival as $key => $value)
              <div class="col-6 col-md-4 col-lg-2">
    <div class="product-card">
        <a href="{{ route('homepageone.productDetails') }}" class="text-decoration-none text-dark">
            <div class="discount-badge">20%</div>
            <div class="">
               <a href="{{ route('product', $value->slug) }}">
            <img src="{{ asset($value->image ? $value->image->image : '') }}"
                alt="{{ $value->name }}" />
        </a>
            </div>
            <div class="product-info">
                <h6 class="product-title">  <a class='text-decoration-none' href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a></h6>
                <div class="price-section">
                      @if ($value->variable_count > 0 && $value->type == 0)
                          <p class="new-price">
                    @if ($value->variable->old_price)
                        <del class="old-price">৳ {{ $value->variable->old_price }}</del>
                    @endif
                    ৳ {{ $value->variable->new_price }}
                </p>
            @else
                <p class="new-price">
                    @if ($value->old_price)
                        <del class="old-price">৳ {{ $value->old_price }}</del>
                    @endif
                    ৳ {{ $value->new_price }}
                </p>
            @endif
                    
                </div>
            </div>
        </a>

        <div class="button-section">
            <button class="order-btn" onclick="addToCart('কোরিয়ান ফাইবার পিলো 2pcs', 2350, '৳ 2,950')">
                <span>⚡</span>
                <span>অর্ডার করুন</span>
            </button>
            <div class="cart-btn-container">
                <button class="cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                </button>
                <div class="plus-icon">+</div>
            </div>
        </div>
    </div>
</div>
    @endforeach
    
         </div>
        </div>
    </section>
