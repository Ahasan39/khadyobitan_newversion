@extends('frontEnd.layouts.master')


@push('css')
<style>

body{
    background:#FFFFFF;
}
   .warrper-container {
            width: 85%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 10px 20px;
            overflow: hidden;
        }
        .slider-container {
            position: relative;
            width: 100%;
            height: 60vh;
            border-radius: 5px;
            overflow: hidden;
        }
        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .slide.active {
            opacity: 1;
        }
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        /* Arrows */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #333;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .slider-arrow:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-50%) scale(1.1);
        }
        .slider-arrow.prev {
            left: 20px;
        }
        .slider-arrow.next {
            right: 20px;
        }
        .slider-container:hover .slider-arrow {
            opacity: 1;
        }
        
        /* Indicators */
        .slider-indicators {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            z-index: 10;
        }
        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .indicator.active {
            background-color: rgba(255, 255, 255, 1);
        }
        
        @media (max-width: 768px) {
            .slider-container {
                height: 50vh; /
                border-radius: 15px;
            }
            .slider-arrow {
                width: 40px;
                height: 40px;
                font-size: 20px;
                opacity: 0.7; 
            }
            .slider-arrow.prev {
                left: 10px;
            }
            .slider-arrow.next {
                right: 10px;
            }
            .slide img {
                object-fit: cover; 
                border-radius: 10px;
            }
            .slider-indicators {
                bottom: 10px;
            }
            .indicator {
                width: 10px;
                height: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .slider-container {
                height: 20vh;
            }
            .warrper-container {
                width: 95%;
                padding: 5px 10px;
            }
        }
/*categroy section */
.category-section {
    max-width: 1400px;
    margin: 0 auto;
}

/* Section Title */
.section-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;
    margin-bottom: 40px;
}

.title-line {
    flex: 1;
    height: 1px;
    background-color: #d0d0d0;
    max-width: 430px;
}

.title-text {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    letter-spacing: 2px;
    white-space: nowrap;
    margin: 0;
}

/* Category Grid */
.category-grid {
    display: grid;
    grid-template-columns: repeat(9, 1fr); /* 9 items per row for large screens */
    gap: 15px; /* fixed gap */
    /*justify-items: center;*/
    /*padding: 0 10px;*/
}

.category-item {
    text-align: center;
}

.category-item a {
    text-decoration: none;
    color: inherit;
    display: block;
}

.category-image {
    /*width: 110px;*/
    /*height: 110px;*/
    margin: 0 ;
    border-radius: 7px;
    overflow: hidden;
    /*box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);*/
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.category-item:hover .category-image {
    /*transform: translateY(-5px);*/
    /*box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);*/
}

.category-item:hover .category-image img {
    transform: scale(1.1);
}

.category-name {
    margin-top: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    transition: color 0.3s ease;
}

.category-item:hover .category-name {
    color: #007bff;
}

/* responsive Settings */

/* Medium: 6 per row */
@media (max-width: 992px) {
    .category-grid {
        grid-template-columns: repeat(6, 1fr);
        gap: 15px;
    }

    .category-image {
        /*width: 100px;*/
        /*height: 100px;*/
    }
}

/* Small: 3 per row */
@media (max-width: 576px) {
    .warrper-container{
        width:98%;
        margin:0 1%;
    }
    .category-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .category-image {
        /*width: 90px;*/
        /*height: 90px;*/
        border-radius: 8px;
    }

    .title-text {
        font-size: 20px;
        letter-spacing: 1px;
    }
}

/*product section start*/
.product-category-img img{
    border-radius:7px;
}

.product-card {
    border-radius: 10px;
    overflow: hidden;
    background: #FFFFFF;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-card:hover {
    /*transform: translateY(-5px);*/
    /*box-shadow: 0 6px 18px rgba(0,0,0,0.15);*/
}
.product-img {
    overflow: hidden;
    border-radius:8px;
    position: relative;
}
.product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    /*border-radius: 10px 10px 0 0;*/
    transition: transform 0.4s ease;
}
.product-card:hover img {
    transform: scale(1.1);
}
.discount-tag {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #0b0b0b;
    color: #fff;
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    z-index: 5; 
    pointer-events: none;  
}
.product-info {
    text-align: center;
    padding: 15px;
}
.product-info h5 {
    font-size: 16px;
    font-weight: 600;
    color:inherit;
    margin-top: 10px;
    cursor:pointer;
}
.product-info h5:hover {
    color:#0989ff;
    
}
.product-info .old-price {
    text-decoration: line-through;
    color: #999;
    margin-right: 6px;
}
.product-info .new-price {
    color: #000;
    font-weight: bold;
}
.buy-btn {
    display: block;
    width: 100%;
    background:#212529;
    color: #fff;
    font-weight: 600;
    text-align: center;
    border: none;
    padding: 10px;
    border-radius:4px;
    transition: 0.3s;
}
.buy-btn:hover {
    background: #333;
    color: #fff;
}
.footer-area {
    background: #000;
    color: #fff;
    font-family: 'Poppins', sans-serif;
}
.list-unstyled{
   padding-left:10px !important; 
}
.footer-links li {
    margin-bottom: 6px;
    display:block;
}
.footer-links li a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s;
    font-size: 14px;
}
.footer-links li a:hover {
    color: #ccc;
}
.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    border-radius: 6px;
    font-size: 16px;
    transition: 0.3s;
}
.social-link:hover {
    opacity: 0.8;
}
.footer-area h6 {
    font-size: 15px;
}
.accoutn-border{
    width: 75px;
    height: 2px;
    background: white;
}
.info-border{
    width: 106px;
    height: 2px;
    background: white;
}
.talk-border{
    width: 85px;
    height: 2px;
    background: white;
}
</style>
@endpush


@section('content')
<!--Header section-->

@include('frontEnd.header')


 <!-- Hero Section -->
    <section class="warrper-container">
        <div class="slider-container">
            <!-- Slides -->
            <div class="slide active">
                <img src="https://i.ibb.co.com/hxSh5f2S/banner-image-3-20251004075710.jpg" alt="Slider Image 1">
            </div>
            <div class="slide">
                <img src="https://i.ibb.co.com/YF5g53QP/banner-image-1-20251004075657.jpg" alt="Slider Image 2">
            </div>
            
            <!-- Indicators -->
            <div class="slider-indicators">
                <div class="indicator active" onclick="currentSlide(0)"></div>
                <div class="indicator" onclick="currentSlide(1)"></div>
            </div>
            
            <!-- Arrows -->
            <button class="slider-arrow prev" onclick="moveSlide(-1)">‹</button>
            <button class="slider-arrow next" onclick="moveSlide(1)">›</button>
        </div>
    </section>

<section class="warrper-container">
    <div class="category-section">
        <!-- Section Title -->
        <div class="section-title">
            <span class="title-line"></span>
            <h2 class="title-text">TOP CATEGORIES</h2>
            <span class="title-line"></span>
        </div>

        <!-- Category Grid -->
        <div class="category-grid">
            <!-- Category Item 1 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=300&fit=crop" alt="Electronics">
                    </div>
                  
                </a>
            </div>

            <!-- Category Item 2 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=300&h=300&fit=crop" alt="Fashion">
                    </div>
                  
                </a>
            </div>

            <!-- Category Item 3 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1484154218962-a197022b5858?w=300&h=300&fit=crop" alt="Home & Kitchen">
                    </div>
                   
                </a>
            </div>

            <!-- Category Item 4 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop" alt="Accessories">
                    </div>
                   
                </a>
            </div>

            <!-- Category Item 5 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=300&h=300&fit=crop" alt="Footwear">
                    </div>
                    
                </a>
            </div>

            <!-- Category Item 6 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&h=300&fit=crop" alt="Books">
                    </div>
                   
                </a>
            </div>

            <!-- Category Item 7 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=300&h=300&fit=crop" alt="Sports">
                    </div>
                   
                </a>
            </div>

            <!-- Category Item 8 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1585399000684-d2f72660f092?w=300&h=300&fit=crop" alt="Toys">
                    </div>
                  
                </a>
            </div>
            <!-- Category Item 9 -->
            <div class="category-item">
                <a href="#">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1585399000684-d2f72660f092?w=300&h=300&fit=crop" alt="Toys">
                    </div>
                  
                </a>
            </div>
        </div>
    </div>
</section>

<section class="warrper-container">
    <div class="product-category-img">
        <img src="https://i.ibb.co.com/9mpLv6Vy/category-image-3-20251008131629.jpg">
    </div>
    <section class="py-5">
    <div class="row g-4">
        @foreach(range(1,4) as $i)
        <div class="col-6 col-xl-3 col-lg-3 col-sm-6">
            <div class="product-card">
                <div class="product-img">
                    <span class="discount-tag">20%</span>
                    <img src="https://i.ibb.co.com/RGfyyW5s/product-hover-15-20250925101008.jpg?text=Product+{{$i}}" alt="product">
                </div>
                <div class="product-info">
                    <h5>Product {{$i}}</h5>
                    <p><span class="old-price">৳1200</span> <span class="new-price">৳960</span></p>
                </div>
                <button class="buy-btn">Buy Now</button>
            </div>
        </div>
        @endforeach
    </div>
</section>
</section>
<section class="warrper-container">
    <div class="product-category-img">
        <img src="https://i.ibb.co.com/9mpLv6Vy/category-image-3-20251008131629.jpg">
    </div>
    <section class="container py-5">
    <div class="row g-4">
        @foreach(range(1,4) as $i)
        <div class="col-6 col-xl-3 col-lg-3 col-sm-6">
            <div class="product-card">
                <div class="product-img">
                    <span class="discount-tag">20%</span>
                    <img src="https://i.ibb.co.com/RGfyyW5s/product-hover-15-20250925101008.jpg?text=Product+{{$i}}" alt="product">
                </div>
                <div class="product-info">
                    <h5>Product {{$i}}</h5>
                    <p><span class="old-price">৳1200</span> <span class="new-price">৳960</span></p>
                </div>
                <button class="buy-btn">Buy Now</button>
            </div>
        </div>
        @endforeach
    </div>
</section>
</section>
<section class="warrper-container">
    <div class="product-category-img">
        <img src="https://i.ibb.co.com/9mpLv6Vy/category-image-3-20251008131629.jpg">
    </div>
    <section class="container py-5">
    <div class="row g-4">
        @foreach(range(1,4) as $i)
        <div class="col-6 col-xl-3 col-lg-3 col-sm-6">
            <div class="product-card">
                <div class="product-img">
                    <span class="discount-tag">20%</span>
                    <img src="https://i.ibb.co.com/RGfyyW5s/product-hover-15-20250925101008.jpg?text=Product+{{$i}}" alt="product">
                </div>
                <div class="product-info">
                    <h5>Product {{$i}}</h5>
                    <p><span class="old-price">৳1200</span> <span class="new-price">৳960</span></p>
                </div>
                <button class="buy-btn">Buy Now</button>
            </div>
        </div>
        @endforeach
    </div>
</section>
</section>
<section class="warrper-container">
    <div class="product-category-img">
        <img src="https://i.ibb.co.com/9mpLv6Vy/category-image-3-20251008131629.jpg">
    </div>
    <section class="container py-5">
    <div class="row g-4">
        @foreach(range(1,4) as $i)
        <div class="col-6 col-xl-3 col-lg-3 col-sm-6">
            <div class="product-card">
                <div class="product-img">
                    <span class="discount-tag">20%</span>
                    <img src="https://i.ibb.co.com/RGfyyW5s/product-hover-15-20250925101008.jpg?text=Product+{{$i}}" alt="product">
                </div>
                <div class="product-info">
                    <h5>Product {{$i}}</h5>
                    <p><span class="old-price">৳1200</span> <span class="new-price">৳960</span></p>
                </div>
                <button class="buy-btn">Buy Now</button>
            </div>
        </div>
        @endforeach
    </div>
</section>
</section>


<footer class="footer-area text-white pt-5" style="background:#000;">
    <div class="container pb-4 border-bottom border-secondary">
        <div class="row gy-4">
            <!-- Brand & Description -->
            <div class="col-md-4 text-center text-md-start">
                <div class="mb-3">
                    <img src="https://i.ibb.co.com/GfVJ85NT/businessprofile-image-1-20250915191650.png" alt="logo" style="width:50px;">
                </div>
                <p class="text-light mb-3" style="font-size:15px; line-height:1.6;">
                    One of the largest Islamic <br>
                    Lifestyle brands in Bangladesh
                </p>
                <div class="d-flex justify-content-center justify-content-md-start gap-2">
                    <a href="#" class="social-link bg-primary text-white">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link bg-danger text-white">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Account -->
            <div class="col-md-3">
                <h6 class="fw-bold  text-white text-uppercase">Account</h6>
                <div class='accoutn-border mb-3'></div>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Track My Order</a></li>
                    <li><a href="#">Join As Affiliate</a></li>
                    <li><a href="#">Complain Box</a></li>
                </ul>
            </div>

            <!-- Information -->
            <div class="col-md-3">
                <h6 class="fw-bold text-white text-uppercase">Information</h6>
                <div class='info-border mb-3'></div>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Our Showrooms</a></li>
                    <li><a href="#">Refund & Returned</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-md-2">
                <h6 class="fw-bold text-white text-uppercase">Talk To Us</h6>
                  <div class='talk-border mb-3'></div>
                <p class="mb-1 text-white ">Got Questions? Call us</p>
                <h5 class="fw-bold text-white mb-2">09638090000</h5>
                <p class="mb-1 text-white "><i class="far fa-envelope me-2"></i> cc.believerssign@gmail.com</p>
                <p class="mb-0 text-white "><i class="fas fa-map-marker-alt me-2"></i> Shop-3/1, Eastern Plaza, <br> Hatirpool, Dhaka, Bangladesh</p>
            </div>
        </div>
    </div>

    <div class="text-center py-3" style="font-size:14px; background:#000;">
        © 2025 Webleez. All Rights Reserved
    </div>
</footer>






<script>
let currentIndex = 0;
let slides = document.querySelectorAll('.slide');
let totalSlides = slides.length;
let autoSlideInterval;

// Show slide by index
function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
}

// Move slide manually
function moveSlide(direction) {
    stopAutoSlide();
    currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
    showSlide(currentIndex);
    startAutoSlide();
}

// Auto slide
function autoSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    showSlide(currentIndex);
}

// Start/stop auto sliding
function startAutoSlide() {
    autoSlideInterval = setInterval(autoSlide, 4000);
}

function stopAutoSlide() {
    clearInterval(autoSlideInterval);
}

// Initialize
startAutoSlide();

// Pause on hover
const sliderContainer = document.querySelector('.slider-container');
sliderContainer.addEventListener('mouseenter', stopAutoSlide);
sliderContainer.addEventListener('mouseleave', startAutoSlide);
</script>
<script>
    const settingsMenu = document.getElementById('settingsMenu');
    settingsMenu.addEventListener('click', () => {
        settingsMenu.classList.toggle('active');
    });

    // Click outside to close dropdown
    document.addEventListener('click', (e) => {
        if (!settingsMenu.contains(e.target)) {
            settingsMenu.classList.remove('active');
        }
    });
</script>

@endsection
