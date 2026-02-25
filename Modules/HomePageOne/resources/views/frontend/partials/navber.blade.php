 <style>
       .icons {
            display: flex;
            gap: 20px;
            font-size: 20px;
        }

        .cart-icon-container {
            position: relative;
            cursor: pointer;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #fff;
            color: #e91e63;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
        }
       /* Cart Sidebar Styles */
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .cart-sidebar.open {
            right: 0;
        }

        .cart-header {
            background: rgb(255, 0, 165);;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header h4 {
            margin: 0;
            font-weight: bold;
        }

        .close-cart {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .cart-items {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #f1f2f6, #ddd);
            border-radius: 8px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .item-price {
            color: #e91e63;
            font-weight: bold;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
        }

        .qty-btn {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: #e91e63;
            color: white;
            border-color: #e91e63;
        }

        .remove-item {
                color: #ff4757;
                cursor: pointer;
                margin-left: 10px;
                background: white;
                outline: none;
                text-decoration: none;
                border: 1px solid #e91e63;
                border-radius: 50%;

        }
        .remove-item i{

               font-size: 14px

        }


        .cart-footer {
            padding: 20px;
            border-top: 2px solid #eee;
            background: #f8f9fa;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .checkout-btn {
            width: 100%;
            background: rgb(255, 0, 165);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.3);
        }

        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .cart-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-cart i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-sidebar {
                width: 100%;
                right: -100%;
            }

            .search-bar {
                max-width: 200px;
            }

            .top-navbar .container {
                padding: 0 15px;
            }
        }

        /* Animation for adding items */
        .item-added {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .success-message {
            position: fixed;
            top: 100px;
            right: 20px;
            background: #4cd137;
            color: white;
            padding: 15px 20px;
            border-radius: 25px;
            font-weight: bold;
            z-index: 1001;
            opacity: 0;
            transform: translateX(100px);
            transition: all 0.3s ease;
        }

        .success-message.show {
            opacity: 1;
            transform: translateX(0);
        }
 </style>

 <div class="top-navbar">
            <span class="menu-icon" onclick="openNav()"><i class="fas fa-bars"></i></span>

            <div class="logo">
                <a href='{{route('homepageone.index')}}'>
                    <img src="https://coloursshopbd.com/frd-data/img/brandlogu/1710569132_frd.png" alt="Colours Shop">
                </a>
            </div>

            <div class="search-bar d-flex">
                <input type="text" class="form-control" placeholder="পণ্য খুঁজুন এখানে">
                <button class="search-icon"><i class="fas fa-search"></i></button>
            </div>

           <div class="icons">
                <div class="cart-icon-container" onclick="openCart()">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </div>
                <i class="fas fa-user"></i>
            </div>
        </div>
 <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h4><i class="fas fa-shopping-cart me-2"></i>আপনার কার্ট</h4>
            <button class="close-cart" onclick="closeCart()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="cart-items" id="cartItems">
            <!-- Cart items will be dynamically added here -->
        </div>

        <div class="cart-footer">
            <div class="cart-total">
                <span>সর্বমোট:</span>
                <span id="cartTotal">৳ 0</span>
            </div>
            <button class="checkout-btn" onclick="goToCheckout()">
                <i class="fas fa-credit-card me-2"></i>চেকআউট করুন
            </button>
        </div>
    </div>

    <!-- Cart Overlay -->
    <div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>

    <!-- Success Message -->
    <div class="success-message" id="successMessage">
        <i class="fas fa-check-circle me-2"></i>
        পণ্য সফলভাবে কার্টে যোগ হয়েছে!
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cart data storage
        let cart = [];
        let cartCount = 0;
        let cartTotal = 0;

        // Add item to cart
        function addToCart(name, price, oldPrice) {
            // Check if item already exists in cart
            const existingItemIndex = cart.findIndex(item => item.name === name);

            if (existingItemIndex > -1) {
                // If item exists, increase quantity
                cart[existingItemIndex].quantity += 1;
                cart[existingItemIndex].totalPrice = cart[existingItemIndex].price * cart[existingItemIndex].quantity;
            } else {
                // If new item, add to cart
                const newItem = {
                    id: Date.now(), // Simple ID generation
                    name: name,
                    price: price,
                    oldPrice: oldPrice,
                    quantity: 1,
                    totalPrice: price
                };
                cart.push(newItem);
            }

            updateCartUI();
            showSuccessMessage();

            // Auto open cart sidebar for better UX
            setTimeout(() => {
                openCart();
            }, 500);
        }

        // Update cart UI
        function updateCartUI() {
            // Update cart count
            cartCount = cart.reduce((total, item) => total + item.quantity, 0);
            document.getElementById('cartCount').textContent = cartCount;

            // Update cart total
            cartTotal = cart.reduce((total, item) => total + item.totalPrice, 0);
            document.getElementById('cartTotal').textContent = '৳ ' + cartTotal.toLocaleString();

            // Update cart items display
            updateCartItems();
        }

        // Update cart items in sidebar
        function updateCartItems() {
            const cartItemsContainer = document.getElementById('cartItems');

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <h5>আপনার কার্ট খালি</h5>
                        <p>কিছু পণ্য যোগ করুন</p>
                    </div>
                `;
                return;
            }

            cartItemsContainer.innerHTML = cart.map(item => `
                <div class="cart-item item-added" data-id="${item.id}">
                    <div class="item-image">IMG</div>
                    <div class="item-details">
                        <div class="item-name">${item.name}</div>
                        <div class="item-price">৳ ${item.price.toLocaleString()}</div>
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="decreaseQuantity(${item.id})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="quantity">${item.quantity}</span>
                            <button class="qty-btn" onclick="increaseQuantity(${item.id})">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="remove-item" onclick="removeFromCart(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Increase quantity
        function increaseQuantity(itemId) {
            const itemIndex = cart.findIndex(item => item.id === itemId);
            if (itemIndex > -1) {
                cart[itemIndex].quantity += 1;
                cart[itemIndex].totalPrice = cart[itemIndex].price * cart[itemIndex].quantity;
                updateCartUI();
            }
        }

        // Decrease quantity
        function decreaseQuantity(itemId) {
            const itemIndex = cart.findIndex(item => item.id === itemId);
            if (itemIndex > -1) {
                if (cart[itemIndex].quantity > 1) {
                    cart[itemIndex].quantity -= 1;
                    cart[itemIndex].totalPrice = cart[itemIndex].price * cart[itemIndex].quantity;
                } else {
                    cart.splice(itemIndex, 1);
                }
                updateCartUI();
            }
        }

        // Remove item from cart
        function removeFromCart(itemId) {
            const itemIndex = cart.findIndex(item => item.id === itemId);
            if (itemIndex > -1) {
                cart.splice(itemIndex, 1);
                updateCartUI();
            }
        }

        // Open cart sidebar
        function openCart() {
            document.getElementById('cartSidebar').classList.add('open');
            document.getElementById('cartOverlay').classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent body scroll
        }

        // Close cart sidebar
        function closeCart() {
            document.getElementById('cartSidebar').classList.remove('open');
            document.getElementById('cartOverlay').classList.remove('show');
            document.body.style.overflow = 'auto'; // Restore body scroll
        }

        // Show success message
        function showSuccessMessage() {
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.add('show');

            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 3000);
        }

        // Go to checkout
        // Go to checkout
        function goToCheckout() {
            if (cart.length === 0) {
                alert('আপনার কার্টে কোন পণ্য নেই!');
                return;
            }

            // Store cart data in sessionStorage for checkout page
            sessionStorage.setItem('cartData', JSON.stringify(cart));
            sessionStorage.setItem('cartTotal', cartTotal);
            sessionStorage.setItem('cartCount', cartCount);

            // Redirect to checkout page
            window.location.href = "{{ route('homepageone.checkout') }}";
        }

        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartUI();
        });


        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartUI();
        });

        // Handle ESC key to close cart
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCart();
            }
        });
    </script>
