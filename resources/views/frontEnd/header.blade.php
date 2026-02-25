<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B Believers - Online Shopping</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
    
         .lc-top-header {
        background: #010f1c;
        color: #fff;
        font-size: 14px;
        padding: 8px 0;
       
    }

  

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Left section: Location */
    .location-section {
        display: flex;
        align-items: center;
        gap: 8px;
        animation: blink 1.4s ease infinite;
    }

    .location-section i {
        color: #ffcc00;
        font-size: 16px;
    }

    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    /* Right section: Settings */
    .settings-section {
        position: relative;
        padding-right: 15px;
        border-right: 1px solid rgba(255,255,255,0.2);
        cursor: pointer;
    }

    .settings-toggle {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #fff;
        text-decoration: none;
    }

    .settings-dropdown {
        position: absolute;
        top: 30px;
        right: 0;
        background: #fff;
        color: #000;
        border-radius: 5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        display: none;
        flex-direction: column;
        width: 150px;
        overflow: hidden;
        z-index: 10;
    }

    .settings-dropdown a {
        padding: 10px 15px;
        text-decoration: none;
        color: #000;
        font-size: 14px;
        transition: background 0.3s;
    }

    .settings-dropdown a:hover {
        background: #f1f1f1;
    }

    .settings-section.active .settings-dropdown {
        display: flex;
    }
        .header-wrapper{
            width:85%;
             margin: 0 auto;
        }
        /* Top Header Section */
        .top-header-section {
            background: #fff;
            padding: 15px 0;
        }
        .top-header-main{
            border-bottom:1px solid rgba(1, 15, 28, .1);;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-text-container {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logo-b {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }

        .logo-believers {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            padding:12px;
        }

        /* Search Section */
        .search-container {
            position: relative;
            max-width: 100%;
        }

        .search-input {
            width: 100%;
            height: 45px;
            border: 2px solid #ddd;
            border-radius: 3px;
            padding: 0 60px 0 20px;
            font-size: 14px;
            color: #999;
        }

        .search-input:focus {
            outline: none;
            border-color: #999;
        }

        .search-btn {
            position: absolute;
            right: 0;
            top: 0;
            height: 45px;
            width: 55px;
            background: #000;
            border: none;
            border-radius: 0 3px 3px 0;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-btn:hover {
            background: #333;
        }

        /* User Actions */
        .user-section {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 77px;
            margin-left:15px;
        }

        .sign-in-section {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #333;
        }

        .user-icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .sign-in-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .sign-in-label {
            font-size: 11px;
            color: #666;
        }

        .your-account {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .cart-section {
            position: relative;
        }

        .cart-icon {
            font-size: 28px;
            color: #333;
            cursor: pointer;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #e74c3c;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
        }

        /* Navigation Menu Section */
        .nav-menu-section {
            background: #fff;
            border-top: none;
            padding: 0;
        }

        .nav-menu-wrapper {
            display: flex;
            align-items: stretch;
        }

        .main-menu {
            flex: 1;
            display: flex;
            margin: 0;
            padding: 0;
            list-style: none;
            align-items:center;
        }

        .main-menu > li {
            position: relative;
        }

        .main-menu > li > a {
            display: flex;
            align-items: center;
            margin-right:25px;
            padding: 11px 0px;
            color: #010f1c;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .main-menu > li > a:hover {
            color: #0989ff;
        }

        .dropdown-arrow {
            font-size: 10px;
            margin-left: 2px;
        }

        /* Dropdown Submenu */
        .submenu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            list-style: none;
            margin: 0;
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .main-menu > li:hover .submenu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .submenu li a {
            display: block;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .submenu li a:hover {
            background: #f8f8f8;
            color: #e74c3c;
            padding-left: 25px;
        }

        /* Hotline Section */
        .hotline-section {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
        }

        .hotline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0989ff;
        }

        .hotline-info {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .hotline-label {
            font-size: 11px;
            color: #666;
        }

        .hotline-number {
            font-size: 15px;
            font-weight: bold;
            color: #333;
        }

        /* Fixed Header */
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background: white;
        }

        /* Scrolled State */
        .header-fixed.scrolled .top-header-section {
            display: none;
        }

        .header-fixed.scrolled .nav-menu-wrapper {
            padding: 0;
        }

        .header-fixed.scrolled .logo-container {
            padding: 8px 0;
        }

        /* Logo in Scrolled Menu */
        .menu-logo {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 0 20px 0 0;
            border-right: 1px solid #f0f0f0;
        }

        .header-fixed.scrolled .menu-logo {
            display: flex;
        }

        .menu-logo .logo-icon {
            width: 40px;
            height: 40px;
        }

        .menu-logo .logo-icon::before {
            width: 20px;
            height: 20px;
        }

        .menu-logo .logo-text-container {
            font-size: 22px;
        }

        /* Cart in Scrolled Menu */
        .menu-cart {
            display: none;
            padding: 0 20px;
        }

        .header-fixed.scrolled .menu-cart {
            display: flex;
            align-items: center;
        }
        .header-fixed.scrolled .hotline-section {
            display: none;
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            padding: 10px;
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 10px 15px;
            background: white;
            border-bottom: 1px solid #eee;
        }

        .mobile-logo {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .mobile-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .mobile-search-btn, .mobile-user-btn {
            background: none;
            border: none;
            font-size: 18px;
            color: #333;
            cursor: pointer;
        }

        .mobile-cart-section {
            position: relative;
        }

        .mobile-cart-count {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #e74c3c;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }

        /* Mobile Search Bar */
        .mobile-search-container {
            display: none;
            padding: 10px 15px;
            background: white;
            border-bottom: 1px solid #eee;
        }

        .mobile-search-container.active {
            display: block;
        }

        .mobile-search-input {
            width: 100%;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 3px;
            padding: 0 50px 0 15px;
            font-size: 14px;
            color: #999;
        }

        .mobile-search-btn-container {
            position: absolute;
            right: 15px;
            top: 10px;
            height: 40px;
            width: 45px;
            background: #000;
            border: none;
            border-radius: 0 3px 3px 0;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Mobile Menu */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .mobile-menu-overlay.active {
            display: block;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background: white;
            overflow-y: auto;
            transition: left 0.3s ease;
            z-index: 1001;
            box-shadow: 5px 0 15px rgba(0,0,0,0.2);
        }

        .mobile-menu.active {
            left: 0;
        }

        .mobile-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: #f8f8f8;
            border-bottom: 1px solid #eee;
        }

        .mobile-menu-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        .mobile-menu-items {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .mobile-menu-item {
            border-bottom: 1px solid #eee;
        }

        .mobile-menu-item > a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }

        .mobile-menu-item.has-submenu.active > a .dropdown-arrow {
            transform: rotate(180deg);
        }

        .mobile-submenu {
            display: none;
            background: #f8f8f8;
            padding: 0;
            list-style: none;
        }

        .mobile-menu-item.active .mobile-submenu {
            display: block;
        }

        .mobile-submenu li a {
            display: block;
            padding: 12px 15px 12px 30px;
            color: #666;
            text-decoration: none;
            border-bottom: 1px solid #e0e0e0;
        }

        .mobile-submenu li:last-child a {
            border-bottom: none;
        }

        .mobile-submenu li a:hover {
            background: #eee;
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            body {
                /*padding-top: 125px;*/
            }

            .top-header-section {
                padding: 10px 0;
            }

            .logo-b, .logo-believers {
                font-size: 26px;
            }

            .search-container {
                margin-top: 10px;
            }

            .user-section {
                gap: 15px;
            }

            .sign-in-text {
                display: none;
            }

            .mobile-toggle {
                display: block;
            }

            .main-menu {
                position: fixed;
                top: 125px;
                left: -100%;
                width: 280px;
                height: calc(100vh - 125px);
                background: white;
                flex-direction: column;
                overflow-y: auto;
                transition: left 0.3s ease;
                box-shadow: 5px 0 15px rgba(0,0,0,0.2);
                z-index: 999;
            }

            .main-menu.active {
                left: 0;
            }

            .main-menu > li {
                border-right: none;
                border-bottom: 1px solid #f0f0f0;
            }

            .main-menu > li > a {
                padding: 15px 20px;
            }

            .submenu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                border-top: none;
                background: #f8f8f8;
                display: none;
            }

            .main-menu > li.active .submenu {
                display: block;
            }

            .hotline-section {
                justify-content: center;
                border-left: none;
                border-top: 1px solid #e0e0e0;
            }

            .header-fixed.scrolled .main-menu {
                top: 60px;
                height: calc(100vh - 60px);
            }

            /* Mobile Header Styles */
            .mobile-header {
                display: flex;
            }

            .top-header-section, .nav-menu-section {
                display: none;
            }

            .header-fixed.scrolled .mobile-header {
                display: flex;
            }

            .header-fixed.scrolled .mobile-search-btn, 
            .header-fixed.scrolled .mobile-user-btn {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .logo-b, .logo-believers {
                font-size: 22px;
            }

            .hotline-info {
                display: none;
            }
            .mobile-search-btn-container
            {
                top:91px;
            }
            .mobile-header.fixed-top{
                background:white;
                top:-3px;
            }
            .mobile-logo{
                    display: flex
;
    /* align-items: center; */
    justify-content: center;
            }
        }


        .hero-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 400"><rect fill="%23667eea" width="1200" height="400"/></svg>');
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        
        
        /*bottom navbar*/
        
           .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: #fff;
      box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 8px 0;
      z-index: 1000;
    }

    .bottom-nav a {
      color: #000;
      text-decoration: none;
      text-align: center;
      font-size: 13px;
    }

    .bottom-nav i {
      display: block;
      font-size: 18px;
      margin-bottom: 3px;
    }

    /* Home button middle circle */
    .home-btn {
      position: relative;
      background: #ff4c00;
      color: #fff !important;
      border-radius: 15px; /* ৪ কোনা হালকা বৃত্ত */
      padding: 10px 18px;
      margin-top: -30px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
      border: 2px solid #fff;
      font-weight: 500;
    }

    .home-btn i {
      font-size: 18px;
    }

    /* Optional hover */
    .bottom-nav a:hover {
      color: #ff4c00;
    }

    @media (min-width: 768px) {
      .bottom-nav {
        display: none; /* শুধু ছোট ডিভাইসের জন্য */
      }
    }
    </style>
</head>
<body>
     
     
     
      
   
    <!-- Fixed Header -->
    <div class="header-fixed" id="mainHeader">
        @include('frontEnd.top-header')
        <!-- Mobile Header -->
        <div class="mobile-header">
            
            <button class="mobile-toggle" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
             <button class="mobile-search-btn" id="mobileSearchBtn">
                    <i class="fas fa-search"></i>
                </button>
            <div class="mobile-logo">
                <img src="https://i.ibb.co.com/j9nFKrfc/businessprofile-image-1-20250915184618.png">
            </div>
            <div class="mobile-actions">
                <!--<button class="mobile-search-btn" id="mobileSearchBtn">-->
                <!--    <i class="fas fa-search"></i>-->
                <!--</button>-->
                <button class="mobile-user-btn">
                   <i class="fa-regular fa-user"></i>
                </button>
                <div class="mobile-cart-section">
                   <svg width="27" height="28" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.48626 20.5H14.8341C17.9004 20.5 20.2528 19.3924 19.5847 14.9348L18.8066 8.89359C18.3947 6.66934 16.976 5.81808 15.7311 5.81808H5.55262C4.28946 5.81808 2.95308 6.73341 2.4771 8.89359L1.69907 14.9348C1.13157 18.889 3.4199 20.5 6.48626 20.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.34902 5.5984C6.34902 3.21232 8.28331 1.27803 10.6694 1.27803V1.27803C11.8184 1.27316 12.922 1.72619 13.7362 2.53695C14.5504 3.3477 15.0081 4.44939 15.0081 5.5984V5.5984" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7.70365 10.1018H7.74942" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.5343 10.1018H13.5801" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <span class="mobile-cart-count">1</span>
                </div>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div class="mobile-search-container" id="mobileSearchContainer">
            <input type="text" class="mobile-search-input" placeholder="Search for Products...">
            <button class="mobile-search-btn-container">
                <i class="fas fa-search"></i>
            </button>
        </div>
 
        <!-- Top Header -->
        <div class='top-header-main'>
            <div class="top-header-section ">
                <div class="header-wrapper">
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-2 col-6">
                            <div class="logo-container">
                                <div class="logo-text-container">
                                    <span class="logo-believers">
                                        <img src="https://i.ibb.co.com/j9nFKrfc/businessprofile-image-1-20250915184618.png">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-12 order-md-2 order-4">
                            <div class="search-container">
                                <input type="text" class="search-input" placeholder="Search for Products...">
                                <button class="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 order-md-3 order-2">
                            <div class="user-section">
                                <a href="#" class="sign-in-section">
                                    <div class="user-icon-circle">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="sign-in-text">
                                        <span class="sign-in-label">Sign In</span>
                                        <span class="your-account">Your Account</span>
                                    </div>
                                </a>
                                <div class="cart-section">
                                   <svg width="27" height="28" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.48626 20.5H14.8341C17.9004 20.5 20.2528 19.3924 19.5847 14.9348L18.8066 8.89359C18.3947 6.66934 16.976 5.81808 15.7311 5.81808H5.55262C4.28946 5.81808 2.95308 6.73341 2.4771 8.89359L1.69907 14.9348C1.13157 18.889 3.4199 20.5 6.48626 20.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.34902 5.5984C6.34902 3.21232 8.28331 1.27803 10.6694 1.27803V1.27803C11.8184 1.27316 12.922 1.72619 13.7362 2.53695C14.5504 3.3477 15.0081 4.44939 15.0081 5.5984V5.5984" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7.70365 10.1018H7.74942" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.5343 10.1018H13.5801" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    <span class="cart-count">1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="nav-menu-section shadow-sm">
            <div class="header-wrapper">
                <div class="nav-menu-wrapper">
                    <!-- Logo in Scrolled State -->
                    <div class="menu-logo">
                        <div class="logo-text-container">
                            <span class="logo-believers">
                                <img src="https://i.ibb.co.com/j9nFKrfc/businessprofile-image-1-20250915184618.png">
                            </span>
                        </div>
                    </div>

                    <!-- Mobile Toggle -->
                    <div class="">
                        <button class="mobile-toggle" id="desktopMobileMenuBtn">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    <!-- Main Menu -->
                    <ul class="main-menu" id="mainMenu">
                        <li>
                            <a href="#" class="menu-item-with-sub">
                                Attar <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li><a href="#">Series 240</a></li>
                                <li><a href="#">Wholesale</a></li>
                                <li><a href="#">Premium Attar</a></li>
                                <li><a href="#">Attar Combo</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Tupi</a></li>
                        <li>
                            <a href="#" class="menu-item-with-sub">
                                Shirt <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li><a href="#">Casual Shirt</a></li>
                                <li><a href="#">Formal Shirt</a></li>
                                <li><a href="#">Party Shirt</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="menu-item-with-sub">
                                Panjabi <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li><a href="#">Cotton Panjabi</a></li>
                                <li><a href="#">Silk Panjabi</a></li>
                                <li><a href="#">Designer Panjabi</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="menu-item-with-sub">
                                T-shirt <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li><a href="#">Round Neck</a></li>
                                <li><a href="#">Polo T-shirt</a></li>
                                <li><a href="#">V-Neck</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="menu-item-with-sub">
                                Pant & Trouser <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li><a href="#">Formal Pant</a></li>
                                <li><a href="#">Casual Pant</a></li>
                                <li><a href="#">Jeans</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Winter</a></li>
                        <li>
                            <a href="#" class="menu-item-with-sub">
                                Sneakers <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </a>
                            <ul class="submenu">
                                <li><a href="#">Sports Shoes</a></li>
                                <li><a href="#">Casual Sneakers</a></li>
                                <li><a href="#">Running Shoes</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Polo Shirt</a></li>
                        <li><a href="#">Combo Offers</a></li>
                    </ul>

                    <!-- Hotline -->
                    <div class="hotline-section">
                        <div class="hotline-icon">
                            <svg data-v-277cd938="" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.96977 3.24859C2.26945 2.75144 3.92158 0.946726 5.09889 1.00121C5.45111 1.03137 5.76246 1.24346 6.01544 1.49057H6.01641C6.59631 2.05874 8.26011 4.203 8.35352 4.65442C8.58411 5.76158 7.26378 6.39979 7.66756 7.5157C8.69698 10.0345 10.4707 11.8081 12.9908 12.8365C14.1058 13.2412 14.7441 11.9219 15.8513 12.1515C16.3028 12.2459 18.4482 13.9086 19.0155 14.4894V14.4894C19.2616 14.7414 19.4757 15.0537 19.5049 15.4059C19.5487 16.6463 17.6319 18.3207 17.2583 18.5347C16.3767 19.1661 15.2267 19.1544 13.8246 18.5026C9.91224 16.8749 3.65985 10.7408 2.00188 6.68096C1.3675 5.2868 1.32469 4.12906 1.96977 3.24859Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12.936 1.23685C16.4432 1.62622 19.2124 4.39253 19.6065 7.89874" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12.936 4.59337C14.6129 4.92021 15.9231 6.23042 16.2499 7.90726" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                        <div class="hotline-info">
                            <span class="hotline-label">Hotline:</span>
                            <span class="hotline-number">09638090000</span>
                        </div>
                    </div>

                    <!-- Cart in Scrolled State -->
                    <div class="menu-cart">
                        <div class="cart-section">
                           <svg width="27" height="28" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.48626 20.5H14.8341C17.9004 20.5 20.2528 19.3924 19.5847 14.9348L18.8066 8.89359C18.3947 6.66934 16.976 5.81808 15.7311 5.81808H5.55262C4.28946 5.81808 2.95308 6.73341 2.4771 8.89359L1.69907 14.9348C1.13157 18.889 3.4199 20.5 6.48626 20.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.34902 5.5984C6.34902 3.21232 8.28331 1.27803 10.6694 1.27803V1.27803C11.8184 1.27316 12.922 1.72619 13.7362 2.53695C14.5504 3.3477 15.0081 4.44939 15.0081 5.5984V5.5984" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7.70365 10.1018H7.74942" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M13.5343 10.1018H13.5801" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            <span class="cart-count">1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <div class="mobile-logo">believers</div>
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="mobile-menu-items">
            <li class="mobile-menu-item has-submenu">
                <a href="#">
                    Attar <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <ul class="mobile-submenu">
                    <li><a href="#">Series 240</a></li>
                    <li><a href="#">Wholesale</a></li>
                    <li><a href="#">Premium Attar</a></li>
                    <li><a href="#">Attar Combo</a></li>
                </ul>
            </li>
            <li class="mobile-menu-item">
                <a href="#">Tupi</a>
            </li>
            <li class="mobile-menu-item has-submenu">
                <a href="#">
                    Shirt <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <ul class="mobile-submenu">
                    <li><a href="#">Casual Shirt</a></li>
                    <li><a href="#">Formal Shirt</a></li>
                    <li><a href="#">Party Shirt</a></li>
                </ul>
            </li>
            <li class="mobile-menu-item has-submenu">
                <a href="#">
                    Panjabi <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <ul class="mobile-submenu">
                    <li><a href="#">Cotton Panjabi</a></li>
                    <li><a href="#">Silk Panjabi</a></li>
                    <li><a href="#">Designer Panjabi</a></li>
                </ul>
            </li>
            <li class="mobile-menu-item has-submenu">
                <a href="#">
                    T-shirt <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <ul class="mobile-submenu">
                    <li><a href="#">Round Neck</a></li>
                    <li><a href="#">Polo T-shirt</a></li>
                    <li><a href="#">V-Neck</a></li>
                </ul>
            </li>
            <li class="mobile-menu-item has-submenu">
                <a href="#">
                    Pant & Trouser <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <ul class="mobile-submenu">
                    <li><a href="#">Formal Pant</a></li>
                    <li><a href="#">Casual Pant</a></li>
                    <li><a href="#">Jeans</a></li>
                </ul>
            </li>
            <li class="mobile-menu-item">
                <a href="#">Winter</a>
            </li>
            <li class="mobile-menu-item has-submenu">
                <a href="#">
                    Sneakers <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <ul class="mobile-submenu">
                    <li><a href="#">Sports Shoes</a></li>
                    <li><a href="#">Casual Sneakers</a></li>
                    <li><a href="#">Running Shoes</a></li>
                </ul>
            </li>
            <li class="mobile-menu-item">
                <a href="#">Polo Shirt</a>
            </li>
            <li class="mobile-menu-item">
                <a href="#">Combo Offers</a>
            </li>
        </ul>
    </div>

    <!--bottom navber-->
    <div style="padding-bottom:60px">
        <div class="bottom-nav">
    <a href="#"><i class="fa-solid fa-bars"></i>Category</a>
    <a href="#"><i class="fa-solid fa-message"></i>Message</a>
    <a href="#" class="home-btn"><i class="fa-solid fa-house"></i>Home</a>
    <a href="#"><i class="fa-solid fa-cart-shopping"></i>Cart (0)</a>
    <a href="#"><i class="fa-solid fa-user"></i>Login</a>
  </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sticky Header
        window.addEventListener('scroll', function() {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
                document.body.style.paddingTop = '60px';
            } else {
                header.classList.remove('scrolled');
                document.body.style.paddingTop = window.innerWidth <= 991 ? '125px' : '145px';
            }
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const mobileMenuClose = document.getElementById('mobileMenuClose');

        function openMobileMenu() {
            mobileMenu.classList.add('active');
            mobileMenuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('active');
            mobileMenuOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Close all submenus
            document.querySelectorAll('.mobile-menu-item').forEach(item => {
                item.classList.remove('active');
            });
        }

        mobileMenuBtn.addEventListener('click', openMobileMenu);
        mobileMenuClose.addEventListener('click', closeMobileMenu);
        mobileMenuOverlay.addEventListener('click', closeMobileMenu);

        // Mobile Submenu Toggle
        document.querySelectorAll('.mobile-menu-item.has-submenu > a').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.classList.toggle('active');
            });
        });

        // Mobile Search Toggle
        const mobileSearchBtn = document.getElementById('mobileSearchBtn');
        const mobileSearchContainer = document.getElementById('mobileSearchContainer');

        mobileSearchBtn.addEventListener('click', function() {
            mobileSearchContainer.classList.toggle('active');
        });

        // Close search when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.mobile-search-container') && !e.target.closest('.mobile-search-btn')) {
                mobileSearchContainer.classList.remove('active');
            }
        });

        // Desktop Mobile Menu (for smaller desktop screens)
        const desktopMobileMenuBtn = document.getElementById('desktopMobileMenuBtn');
        const mainMenu = document.getElementById('mainMenu');

        if (desktopMobileMenuBtn) {
            desktopMobileMenuBtn.addEventListener('click', function() {
                mainMenu.classList.toggle('active');
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            });
        }

        // Desktop Submenu Toggle
        if (window.innerWidth <= 991) {
            document.querySelectorAll('.menu-item-with-sub').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.parentElement.classList.toggle('active');
                });
            });
        }

        // Close menu on outside click (desktop)
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-menu-wrapper') && !e.target.closest('#desktopMobileMenuBtn')) {
                mainMenu.classList.remove('active');
                const icon = document.querySelector('#desktopMobileMenuBtn i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    </script>
</body>
</html>