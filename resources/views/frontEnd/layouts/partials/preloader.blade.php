<style>
        .preloader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .preloader-container.show {
            display: flex;
        }

        .preloader-content {
            position: relative;
            width: 200px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .preloader-ring {
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: conic-gradient(
                from 0deg,
                #FF6B6B,
                #4ECDC4,
                #45B7D1,
                #FFA07A,
                #98D8C8,
                #FF6B6B
            );
            animation: rotate 3s linear infinite;
            padding: 4px;
        }

        .preloader-ring::before {
            content: '';
            position: absolute;
            top: 4px;
            left: 4px;
            right: 4px;
            bottom: 4px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 50%;
        }

        .logo {
            position: relative;
            z-index: 2;
            width: 140px;
            height: 140px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            box-shadow: 0 0 30px rgba(102, 126, 234, 0.5);
            animation: pulse 2s ease-in-out infinite;
        }
        .logo img{
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 30px rgba(102, 126, 234, 0.5);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 60px rgba(102, 126, 234, 0.8);
                transform: scale(1.05);
            }
        }

        .loading-text {
            position: absolute;
            bottom: -50px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 3px;
            animation: textFade 1.5s ease-in-out infinite;
        }

        @keyframes textFade {
            0%, 100% {
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
        }

        .demo-btn {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .demo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .demo-btn:active {
            transform: translateY(0);
        }

        .main-content {
            text-align: center;
            color: white;
            z-index: 1;
        }

        .main-content h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .main-content p {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.9;
        }
    </style>

    <!-- প্রিলোডার -->
    <div class="preloader-container" id="preloader">
        <div class="preloader-content">
            <div class="preloader-ring"></div>
            <div class="logo"><img src="{{ asset($generalsetting->dark_logo ?? ' ' ) }}"
                            alt="" /></div>
            <!--<div class="loading-text">লোডিং...</div>-->
        </div>
    </div>

    <script>
        const preloader = document.getElementById('preloader');
        const demoBtn = document.getElementById('demoBtn');

        // পেজ লোড হওয়ার সময় প্রিলোডার দেখানো
        window.addEventListener('load', function() {
            // প্রিলোডার ২ সেকেন্ড দেখানোর পর লুকিয়ে ফেলা
            setTimeout(function() {
                preloader.classList.remove('show');
            }, 2000);
        });

        // যখন পেজ লোড শুরু হয়, প্রিলোডার দেখান
        window.addEventListener('beforeunload', function() {
            preloader.classList.add('show');
        });

        // ডেমো বাটন - নতুন পেজে নিয়ে যাবে
        if (demoBtn) {
            demoBtn.addEventListener('click', function() {
                // এটা একটা ডেমো লিংক, আপনার সাইটের পেজ লিংক দিন
                window.location.href = window.location.href;
            });
        }

        // প্রথম লোডের সময় প্রিলোডার দেখান
        preloader.classList.add('show');
    </script>
