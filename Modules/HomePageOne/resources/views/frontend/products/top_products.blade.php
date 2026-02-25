<section class="top-products ">
        <div class="container">
            <!-- Section Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">টপ সেলস</h2>

            </div>

            <!-- Product Grid -->
            <div class="row g-3">
                <!-- Product Card 1 -->
                  @foreach ($new_arrival as $key => $value)
                    <div class="col-6 col-md-4 col-lg-2">
                    <div class="product-card">
                        <div class="discount-badge">20%</div>
                        <div class="">
                             <a href="{{ route('product', $value->slug) }}">
            <img src="{{ asset($value->image ? $value->image->image : '') }}"
                alt="{{ $value->name }}" />
        </a>
                        </div>
                        <div class="product-info">
                            <h6 class="product-title">কোরিয়ান ফাইবার পিলো 2pcs</h6>
                            <div class="price-section">
                                <p class="old-price">৳ 2,950</p>
                                <p class="new-price">2,350 To 2,350 ৳</p>
                            </div>
                        </div>

                        <div class="button-section">
                            <button class="order-btn">
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
