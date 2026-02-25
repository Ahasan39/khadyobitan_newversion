<section class="top-products ">
        <div class="container">
            <!-- Section Header -->

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">{{$homecat->name}} </h2>
                <a href="#" class="see-all-btn">সবগুলো দেখুন</a>
            </div>
            <div class="mt-2 mb-4">
               
            @if($value->banner)
    <img width="100%" src="{{ asset($value->banner) }}" alt="">
@endif

            </div>
            <!-- Product Grid -->
            <div class="row g-3">
                <!-- Product Card 1 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="product-card">
                        <div class="discount-badge">20%</div>
                        <div >
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

                <!-- Product Card 2 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="product-card">
                        <div class="discount-badge">54%</div>
                        <div class="demo-image">
                            Product Image
                        </div>
                        <div class="product-info">
                            <h6 class="product-title">৭ পিস এর 3D ডিজাইন টেবিল কভার</h6>
                            <div class="price-section">
                                <p class="old-price">৳ 2,700</p>
                                <p class="new-price">1,250 ৳</p>
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

                <!-- Product Card 3 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="product-card">
                        <div class="discount-badge">42%</div>
                        <div class="demo-image">
                            Product Image
                        </div>
                        <div class="product-info">
                            <h6 class="product-title">Premium কোরিয়ান ফাইবার Side Pillow</h6>
                            <div class="price-section">
                                <p class="old-price">৳ 1,800</p>
                                <p class="new-price">1,050 ৳</p>
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

                <!-- Product Card 4 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="product-card">
                        <div class="discount-badge">18%</div>
                        <div class="demo-image">
                            Product Image
                        </div>
                        <div class="product-info">
                            <h6 class="product-title">Luxury 3D Design Cotton King Size Classical</h6>
                            <div class="price-section">
                                <p class="old-price">৳ 4,250</p>
                                <p class="new-price">3,500 ৳</p>
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

                <!-- Product Card 5 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="product-card">
                        <div class="discount-badge">25%</div>
                        <div class="demo-image">
                            Product Image
                        </div>
                        <div class="product-info">
                            <h6 class="product-title">Luxury 3D Design Cotton King Size HOME TEX</h6>
                            <div class="price-section">
                                <p class="old-price">৳ 2,200</p>
                                <p class="new-price">1,650 To 1,950 ৳</p>
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

                <!-- Product Card 6 -->
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="product-card">
                    <div class="product-card">
                        <div class="discount-badge">25%</div>
                        <div class="demo-image">
                            Product Image
                        </div>
                        <div class="product-info">
                            <h6 class="product-title">Luxury 3D Design Cotton King Size HOME TEX</h6>
                            <div class="price-section">
                                <p class="old-price">৳ 2,200</p>
                                <p class="new-price">1,650 ৳</p>
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
            </div>
        </div>
    </section>
