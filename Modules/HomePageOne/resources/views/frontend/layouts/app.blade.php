<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Colours Shop</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/modules/homepageone/frontend/css/style.css">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
</head>

<body>

      <section class="container p-0">
        <!-- Sidebar -->
        @include('homepageone::frontend.partials.sidebar')

        <!-- Top Navbar -->
       @include('homepageone::frontend.partials.navber')
    </section>

    <!-- Headline (marquee) -->
    <div class="headline text-center">
        <marquee behavior="scroll" direction="left" scrollamount="5">
            এই শীতে বিশেষ অফার! Colours Shop নিয়ে এলো দারুণ সব ডিজাইন ও কালার কম্বিনেশনের কালেকশন
        </marquee>
    </div>

    @yield('content')


<!-- Footer Section -->
<footer class="footer">
    <div class="container ">
        <div class="row">
            <div class="col-lg-6">
                <p><i class="fa-solid fa-phone"></i> 01753-563153</p>
            </div>
            <div class="col-lg-6">
                <p><i class="fa-solid fa-envelope"></i> info@coloursshopbd.com</p>
            </div>
        </div>

    </div>


    <div class="address">
        <p><i class="fa-solid fa-house"></i> House-21, Road-22, Block-D, Mirpur-6.</p>
    </div>

    <div class="social-icons">
        <a href="#" class="facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" class="youtube"><i class="fa-brands fa-youtube"></i></a>
        <a href="#" class="whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
    </div>

    <div class="copyright">
        <p>কপিরাইট কপিরাইট © 2025 <span>Colours Shop BD</span></p>
        <p class="developer">নির্মাতা: <a href="#">Spider eCommerce</a></p>
    </div>
</footer>
<!-- Floating Buttons -->
<div class="scroll-top"><i class="fa-solid fa-arrow-up"></i></div>
<div class="messenger-btn"><i class="fa-brands fa-facebook-messenger"></i></div>

    <script>
        // Scroll to Top
        document.querySelector(".scroll-top").addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>





    <!-- Sidebar open/close + Bootstrap JS bundle (includes Popper) -->
    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }
    </script>
    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Bootstrap JS (bundle)  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".counter-carousel").owlCarousel({
                loop: true,
                margin: 30,
                autoplay: true,
                autoplayTimeout: 2500,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".feature-carousel").owlCarousel({
                loop: true,
                margin: 30,
                autoplay: true,
                autoplayTimeout: 2500,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    }
                }
            });
        });
    </script>
    <!-- Optional: explicit JS init  -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.querySelector('#bannerCarousel');
            if (el && window.bootstrap) {
                // auto-init with options (interval in ms)
                new bootstrap.Carousel(el, {
                    interval: 4000,
                    pause: 'hover',
                    wrap: true
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".category-carousel").owlCarousel({
                loop: true,
                margin: 15,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 2
                    },
                    576: {
                        items: 3
                    },
                    768: {
                        items: 4
                    },
                    992: {
                        items: 5
                    },
                    1200: {
                        items: 8
                    }
                }
            });
        });
    </script>

</body>

</html>
