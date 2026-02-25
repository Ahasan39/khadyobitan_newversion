@extends('homepageone::frontend.layouts.app')


@section('content')
    <div class="container my-5">
        <style>
            body {
                font-family: 'SolaimanLipi', Arial, sans-serif;
                /* background: #fff; */
                color: #333;
            }

            .price-old {
                text-decoration: line-through;
                color: gray;
                font-size: 14px;
            }

            .price-new {
                font-size: 28px;
                font-weight: bold;
                color: #e4007f;
            }

            .stock-info {
                font-size: 14px;
                color: #666;
            }

            .btn-order {
                background: #e4007f;
                color: #fff;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .btn-cart {
                background: #ff0099;
                color: #fff;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .btn-whatsapp {
                background: #25d366;
                color: #fff;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .call-order {
                background: #ff0099;
                color: #fff;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .call-number {
                font-size: 15px;
                font-weight: bold;
                color: #e4007f;
                text-align: center;
                margin-top: 20px;
            }

            .btn-order,
            .btn-cart,
            .btn-whatsapp,
            .call-order {
                border: none !important;
                box-shadow: none !important;
                transition: all 0.3s ease-in-out;
                padding: 10px;
                border-radius: 5px;
            }

            /* hover এ শুধু custom effect */
            .btn-order:hover {
                background: #c7006c !important;
                color: #fff !important;
                transform: scale(1.05);
            }

            .btn-cart:hover {
                background: #cc007a !important;
                color: #fff !important;
                transform: scale(1.05);
            }

            .btn-whatsapp:hover {
                background: #1ebc57 !important;
                color: #fff !important;
                transform: scale(1.05);
            }

            .call-order:hover {
                background: #cc007a !important;
                color: #fff !important;
                transform: scale(1.05);
            }

            .btn-cart,
            .btn-whatsapp,
            .call-order {
                transition: all 0.3s ease-in-out;
            }

            .btn-cart:hover,
            .btn:hover,
            .btn-whatsapp:hover,
            .call-order:hover {
                transform: scale(1.05);
            }

            .rcsp_1 a {
                font-size: 15px;
                color: #000000
            }

            .protitle {
                font-size: 25px;
                font-weight: 600;
            }


            .glyphicon {
                position: relative;
                top: 1px;
                display: inline-block;
                font-family: 'Glyphicons Halflings';
                font-style: normal;
                font-weight: 400;
                line-height: 1;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            .delivery-info {
                background: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 20px #ff0099;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                height: 100%;
            }

            .delivery-info img {
                max-width: 50px;
                margin-right: 10px;
            }

            .delivery-info p {
                margin: 0;
                font-size: 16px;
                font-weight: 500;
            }
            .delivery-info ul li{
                
                text-align: start;
                 font-size: 14px;
                font-weight: 500;
            }
        </style>


        <div class="container my-4">


            <div class="container mb-2">
                <div class="row">
                    <!-- PRODUCT TITLE  -->
                    <div class="col-md-12 ">
                        <h4><span class='rcsp_1'> <a href='{{ route('homepageone.index') }}'> হোম </a> / <a href='#'>
                                    Deluxe Mattress Topper</a> </span>
                            <h4>
                                <h1 class='protitle'>কোরিয়ার ফাইবার পিলো 2ps</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Product Image -->
                <div class="col-md-6 text-center">
                    <img src="https://i.ibb.co.com/F4rFvZ58/2-frd-1714541227.jpg" class="img-fluid border" alt="পিলো">

                </div>

                <!-- Product Info -->
                <div class="col-md-6">
                    <h4 class="mb-3">কোরিয়ান ফাইবার পিলো 2ps</h4>
                    <span class="price-old">২,৯৫০৳</span>
                    <span class="price-new">২,৩৫০৳</span>
                    <p class="stock-info mt-2">প্রোডাক্ট আইডি: 2<br>স্টকে আছে</p>

                    <!-- Buttons -->
                    <!-- Buttons -->
                    <button class="btn-order w-100">⚡ অর্ডার করুন</button>
                    <button class="btn-cart w-100">🛒 ব্যাগে যোগ</button>
                    <button class="btn-whatsapp w-100">📲 হোয়াটসঅ্যাপে অর্ডার</button>
                    <button class="call-order w-100">📞 কল অর্ডার 01753-563153</button>

                    <!-- Description -->
                    <h5 class="mt-4">কোরিয়ান ফাইবার পিলো 2ps</h5>
                    <p style='    color: rgb(51, 51, 51);
    font-size: 14px;'>
                        কোরিয়ান ফাইবার ৫ স্টার হোটেল মাইক্রো ফাইবার ডিলাক্স পিলো।<br>
                        আরামদায়ক ঘুমের জন্য Deluxe Pillow ব্যবহার করুন। এই Pillow খুবই আরামদায়ক এবং সহজেই ধোয়া যায়।
                    </p>
                    <ul style='    color: rgb(51, 51, 51);
    font-size: 14px;'>
                        <li>Length: ১৮ × ২৬ ইঞ্চি</li>
                        <li>Materials: Micro fiber</li>
                        <li>Fabric: 100% Cotton</li>
                    </ul>
                </div>
            </div>

            <!-- Call Order Footer -->
            <div class="call-number">
                📞 কল অর্ডার দিতে কল করুন <br>
                01753-563153
            </div>


            {{-- related Product section --}}

            <div>
                <div class="related-title mb-4">
                    <h3> <span class="glyphicon glyphicon-hand-right pip_pip_1s"></span><i
                            class="fa-regular fa-hand-point-right"></i> একই ধরনের আরো পণ্য </h3>
                </div>


                <section class="new-products">
                    <div class="">
                        <!-- Section Header -->


                        <!-- Product Grid -->
                        <div class="row g-3">
                            <!-- Product Card 1 -->
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="product-card">
                                    <a href="{{ route('homepageone.productDetails') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="discount-badge">20%</div>
                                        <div class="demo-image">
                                            Product Image
                                        </div>
                                        <div class="product-info">
                                            <h6 class="product-title">কোরিয়ান ফাইবার পিলো 2pcs</h6>
                                            <div class="price-section">
                                                <p class="old-price">৳ 2,950</p>
                                                <p class="new-price">2,350 To 2,350 ৳</p>
                                            </div>
                                        </div>
                                    </a>

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

            </div>

            {{-- related Product section end --}}
            <div class="my-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="delivery-info">
                            <div>
                                <img class='mb-2'
                                    src="https://coloursshopbd.com/frd-public/theme/asset/img/icon_2_dcidc.png"
                                    alt="">
                                <p>ঢাকা সিটির ভিতরে ডেলিভারি চার্জ ৮০ টাকা</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="delivery-info">
                            <div>
                                <img class='mb-2'
                                    src="https://coloursshopbd.com/frd-public/theme/asset/img/icon_2_dcidc.png"
                                    alt="">
                                <p>ঢাকা সিটির বাহিরে ডেলিভারি চার্জ ১৫০ টাকা</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="delivery-info">
                            <ul>
                                <li>ঢাকা মেট্রো এরিয়ার মধ্যে ফুল ক্যাশ অন ডেলিভারীতে পন্য অর্ডার করতে পারবেন। (হোম ডেলিভারী)
                                </li>
                                <li>
                                    অর্ডার কনফার্ম করতে ডেলিভারী চার্জ বিকাশ / নগদ / রকেট এর মাধ্যমে অগ্রীম দিতে হবে।

                                </li>
                                <li> আমরা সারা বাংলাদেশ হোম ডেলিভারীতে পন্য ডেলিভারী করি সুতরাং পন্য গ্রহন করার সময় চেক করে
                                    নিতে পারবেন।
                                </li>
                                <li>অর্ডার করা পন্য ডেলিভারীর পর এক্সচেঞ্জ করতে চাইলে অবশ্যই পন্য গ্রহন করার ২৪ ঘন্টার মধ্যে আমাদের কল করে অবগত করবেন।
</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
