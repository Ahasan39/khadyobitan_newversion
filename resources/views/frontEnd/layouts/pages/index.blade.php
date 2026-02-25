@extends('frontEnd.layouts.master') 
@section('title', $generalsetting->meta_title ?? ' ') 
@push('seo')
<meta name="app-url" content="" />
<meta name="robots" content="index, follow" />
<meta name="description" content="{{$generalsetting->meta_description ?? ' '}}" />
<meta name="keywords" content="{{$generalsetting->meta_keyword ?? ' '}}" />
<!-- Open Graph data -->
<meta property="og:title" content="{{$generalsetting->meta_title ?? ' '}}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="" />
<meta property="og:image" content="{{ asset($generalsetting->white_logo ?? ' ') }}" />
<meta property="og:description" content="{{$generalsetting->meta_description ?? ' '}}" />
@endpush 
@push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />


@endpush 
@section('content')
<section class="slider-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="home-slider-container">
                    <div class="main_slider owl-carousel">
                        @foreach ($sliders as $key => $value)
                            <div class="slider-item">
                               <a href="{{$value->link}}">
                                    <img src="{{ asset($value->image) }}" alt="" />
                               </a>
                            </div>
                            <!-- slider item -->
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- col-end -->
            <div class="col-sm-4">
                <div class="banner-right">
                    @foreach($sliderrightads as $key=>$value)
                    <div class="banner-right-item item-{{$key+1}}">
                        <a href="{{$value->link}}">
                            <img src="{{asset($value->image)}}" alt="">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- slider end -->
<div class="home-category">
    @if(\App\Models\BusinessSetting::getValue('show_category'))
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="category-title">
                    <h3>Top Categories</h3>
                </div>
                <div class="category-slider owl-carousel">
                    @foreach($categories as $key=>$value)
                    <div class="cat-item">
                        <div class="cat-img">
                            <a href="{{route('category',$value->slug)}}">
                                <img src="{{asset($value->image)}}" alt="">
                            </a>
                        </div>
                        <div class="cat-name">
                            <a href="{{route('category',$value->slug)}}">
                                {{$value->name}}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!--new arrival start-->
 @if(\App\Models\BusinessSetting::getValue('show_new_arrival'))
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3> <a href="{{route('newArrival')}}">New Arrival</a></h3>
                    <a href="{{route('newArrival')}}" class="view_all">View All</a>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($new_arrival as $key => $value)
                        <div class="product_item wist_item">
                            @include('frontEnd.layouts.partials.product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--new arrival end-->
<!--top rated start-->
 @if(\App\Models\BusinessSetting::getValue('show_top_rated'))
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3> <a href="{{route('topRated')}}">Top Rated</a></h3>
                    <a href="{{route('topRated')}}" class="view_all">View All</a>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($new_arrival as $key => $value)
                        <div class="product_item wist_item">
                            @include('frontEnd.layouts.partials.product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--top rated end-->
<!--top selling start-->
 @if(\App\Models\BusinessSetting::getValue('show_top_selling'))
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3> <a href="{{route('topSelling')}}">Top Selling</a></h3>
                    <a href="{{route('topSelling')}}" class="view_all">View All</a>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($new_arrival as $key => $value)
                        <div class="product_item wist_item">
                            @include('frontEnd.layouts.partials.product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--top selling end-->
@if(empty($hotdeal_top))
<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3> <a href="{{route('bestdeals')}}">Best Deals</a></h3>
                    <a href="{{route('bestdeals')}}" class="view_all">View All</a>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($hotdeal_top as $key => $value)
                        <div class="product_item wist_item">
                            @include('frontEnd.layouts.partials.product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif



@foreach ($homecategory as $homecat)
    <section class="homeproduct">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h3><a href="{{route('category',$homecat->slug)}}">{{$homecat->name}} </a></h3>
                        <a href="{{route('category',$homecat->slug)}}" class="view_all">View All</a>
                    </div>
                </div>
                @php
                    $products = App\Models\Product::where(['status' => 1, 'category_id' => $homecat->id])
                        ->orderBy('id', 'DESC')
                        ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type','category_id')
                        ->withCount('variable')
                        ->limit(12)
                        ->get();
                @endphp
                <div class="col-sm-12">
                    <div class="product_slider owl-carousel">
                        @foreach ($products as $key => $value)
                            <div class="product_item wist_item">
                               @include('frontEnd.layouts.partials.product')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endforeach
@if(\App\Models\BusinessSetting::getValue('show_brand'))
    <div class="home-category mt-4">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="category-title">
                        <h3>Brands</h3>
                    </div>
                    <div class="category-slider owl-carousel">
                        @foreach($brands as $key=>$value)
                        <div class="brand-item">
                            <a href="{{route('brand',$value->slug)}}">
                                <img src="{{asset($value->image)}}" alt="">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!--all product start-->
@if(\App\Models\BusinessSetting::getValue('show_all_product'))
<section class="homeproduct">
    <div class="container">
        <div class="section-title d-flex justify-content-between align-items-center mb-4">
            <h3><a href="#">All Product</a></h3>
            <a href="#" class="view_all">View All</a>
        </div>

        <div class="row" id="all-products">
    @if(isset($all_products))
      @foreach ($all_products as $product)
    <div class="col-lg-3 col-md-4 col-6 mb-4 product_item wist_item">
        @include('frontEnd.layouts.partials.product', ['value' => $product])
    </div>
@endforeach
    @endif
</div>

        <div class="text-center mt-3" id="load-more-container">
            <div id="loading-spinner" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--all product end-->




    <div class="footer-gap"></div>
     @if($popup_banner)
     <section class="popup__section" id="popup">
        <div class="main__popup_data">
            <button class="popup__close" id="closePopup">&times;</button>
            <a href="{{$popup_banner->link}}">
                <img src="{{asset($popup_banner->image)}}" alt="Popup Banner">
            </a>
        </div>
    </section>
    @endif
@endsection 
@push('script')
<script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
<script>
    $(document).ready(function() {
        
        // main slider 
        $(".main_slider").owlCarousel({
            items: 1,
            loop: true,
            dots: false,
            autoplay: true,
            nav: true,
            autoplayHoverPause: false,
            margin: 0,
            mouseDrag: true,
            smartSpeed: 8000,
            autoplayTimeout: 3000,

            navText: ["<i class='fa-solid fa-angle-left'></i>",
                "<i class='fa-solid fa-angle-right'></i>"
            ],
        });

         $(".category-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            nav: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3,
                },
                600: {
                    items: 3,
                },
                1000: {
                    items: 7,
                },
            },
        });

        $(".product_slider").owlCarousel({
            margin: 10,
            items: 5,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                600: {
                    items: 5,
                    nav: false,
                },
                1000: {
                    items: 5,
                    nav: false,
                },
            },
        });
    });
</script>
 <script>
    document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('popup');
    const closePopup = document.getElementById('closePopup');
    
    if (popup && closePopup) {
        // Show popup after 3 seconds
        setTimeout(() => {
            popup.style.display = 'flex'; // Show popup
        }, 3000);
        
        // Close popup on close button click
        closePopup.addEventListener('click', () => {
            popup.style.display = 'none'; // Hide popup
        });
    }
    });
</script>

<script>
    let page = 2; 
    let loading = false;
    let hasMoreProducts = true;

    function loadMoreProducts() {
        if (loading || !hasMoreProducts) return;

        loading = true;
        document.getElementById('loading-spinner').style.display = 'block';

        fetch(`{{ url('/load-more-products') }}?page=${page}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading-spinner').style.display = 'none';

                if (data.products && data.products.length > 0) {
                    
                    // ✅ Double load এড়াতে current_page চেক
                    if (data.current_page === page) {
                        data.products.forEach(productHtml => {
                            const div = document.createElement('div');
                            div.className = 'col-lg-3 col-md-4 col-6 mb-4 product_item wist_item';
                            div.innerHTML = productHtml;
                            document.getElementById('all-products').appendChild(div);
                        });

                        page++; // পরের পেজ লোড হবে
                    }

                    loading = false;

                    // ✅ যদি আর কোনো পেজ না থাকে
                    if (data.has_more_pages === false) {
                        hasMoreProducts = false;
                        showNoMoreMessage();
                    }

                } else {
                    hasMoreProducts = false;
                    showNoMoreMessage();
                    loading = false;
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                document.getElementById('loading-spinner').style.display = 'none';
                loading = false;
            });
    }

    function showNoMoreMessage() {
        const noMore = document.createElement('p');
        noMore.className = 'text-muted mt-2';
        noMore.innerText = 'No more products to load.';
        document.getElementById('load-more-container').appendChild(noMore);
    }

    // Auto load when user scrolls to bottom
    window.addEventListener('scroll', () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
            loadMoreProducts();
        }
    });
</script>


@endpush
