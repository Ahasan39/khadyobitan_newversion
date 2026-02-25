@extends('homepageone::frontend.layouts.app')

@section('content')


    <!-- Banner Slider (Bootstrap Carousel) -->
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
               @foreach ($sliders as $key => $value)
            <div class="carousel-item active">
                <img src="{{ asset($value->image) }}" class="d-block w-100"
                    alt=" " height='300'>
            </div>
            @endforeach
          
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>

        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"
                aria-current="true"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
        </div>
    </div>



    <!-- Popular Category Section -->
    <section class="popular-category my-5">
        <div class="container">
            <!-- Section Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="section-title">জনপ্রিয় ক্যাটাগরি</h3>
                <a href="#" class="see-all-btn">সবগুলো দেখুন</a>
            </div>

            <!-- Owl Carousel -->
            <div class="owl-carousel category-carousel text-center">
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/5.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Deluxe Mattress Topper</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/3.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Comforter Set & AC Katha</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/2.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">HOME TEX Bedsheet</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/4.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Home Decor</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/1.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Deluxe Mattress Topper</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/6.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Comforter Set & AC Katha</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/6.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Comforter Set & AC Katha</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/6.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Comforter Set & AC Katha</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/6.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Comforter Set & AC Katha</p>
                </div>
                <div class="item">
                    <img src="{{ asset('/public/modules/homepageone/frontend/img/category/6.jpg') }}" class="img-fluid"
                        alt="Category">
                    <p class="category-name">Comforter Set & AC Katha</p>
                </div>
            </div>
        </div>
    </section>
    <!-- New Products Section -->
    @include('homepageone::frontend.products.new_products')
    <!-- Top Products Section -->
    @include('homepageone::frontend.products.top_products')
@foreach ($homecategory as $homecat)
    {{-- Comforter Set Product section --}}
    @include('homepageone::frontend.products.comforter_products')
 @endforeach
    {{-- Dining Table Cloth Product section --}}
    @include('homepageone::frontend.products.dining_cloth_products')
    {{-- 3D print Dining Table Cover Product section --}}
    @include('homepageone::frontend.products.treeD_print')
    {{-- Comforter Set & AC Katha Product section --}}
    @include('homepageone::frontend.products.comforter_ac_katha')

       {{-- HOME TEX Bedsheet 2 Product section --}}
   @include('homepageone::frontend.products.home_tex')

      {{-- HOME TEX Bedsheet Product section --}}
   @include('homepageone::frontend.products.home_tex_bedsheet')

     {{-- Premium Quality Bed Sheet Product section --}}
@include('homepageone::frontend.products.premium_bed')
     {{-- Deluxe Mattress Topper Product section --}}
   @include('homepageone::frontend.products.deluxe')


 <div class="counter-carousel owl-carousel owl-theme">
   @include('homepageone::frontend.partials.counter')
</div>


<div class="feature-carousel owl-carousel owl-theme">
    <div class="feature-item">
        <img src="https://cdn-icons-png.flaticon.com/512/3313/3313830.png" alt="Secure Payment">
        <h4>নিরাপদ মূল্যপরিশোধ</h4>
        <p>আমাদের অ্যাপস পেমেন্ট করুন বিশ্বের জনপ্রিয় ও নিরাপদ পেমেন্ট পদ্ধতিতে।</p>
    </div>
    <div class="feature-item">
        <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Secure Shopping">
        <h4>আস্থার সাথে কিনুন</h4>
        <p>নিরাপদ কেনাকাটা করুন পণ্য হাতে পাওয়া থেকে ঘরে ডেলিভারি পর্যন্ত।</p>
    </div>
    <div class="feature-item">
        <img src="https://cdn-icons-png.flaticon.com/512/126/126509.png" alt="Customer Support">
        <h4>সার্বক্ষণিক কাস্টমার সেবা</h4>
        <p>যেকোনো সমস্যার সমাধানের জন্য আছে সার্বক্ষণিক কাস্টমার সেবা।</p>
    </div>
    <div class="feature-item">
        <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png" alt="Best Price">
        <h4>সাশ্রয়ী মূল্য</h4>
        <p>আমরা আমাদের ক্রেতাদের সেরা সাশ্রয়ী মূল্য অফার করি।</p>
    </div>
    <div class="feature-item">
        <img src="https://coloursshopbd.com/frd-data/img/hp/gain_cust_trust/5.gif" alt="Fast Delivery">
        <h4>দেশব্যাপী ডেলিভারি</h4>
        <p>আমরা আমাদের পণ্য সারাদেশে দ্রুততার সাথে ডেলিভারি দিয়ে থাকি।</p>
    </div>
</div>





@endsection
