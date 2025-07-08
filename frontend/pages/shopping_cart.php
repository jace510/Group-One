<?php
//redirect if not logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /Group-One/backend/auth/login.php");
    exit();
}

include '../../backend/auth/header.php';
include '../modal.php';
include '../../backend/nav.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Railed</title>
    <link rel="stylesheet" href="../index.css">
    <style>
        /* Shopping Cart Main Content */
        .account-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: grid;
            gap: 60px;
        }

        .account-content {
            background: #fff;
        }

        .content-header {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e5e5;
        }

        .content-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #000;
        }

        .content-header p {
            font-size: 16px;
            color: #666;
        }

        /* Cart Sections */
        .settings-section {
            margin-bottom: 50px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 40px;
            transition: all 0.3s;
        }

        .settings-section:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #000;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section-icon {
            font-size: 24px;
        }

        /* Cart Items */
        .cart-item {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 20px;
            padding: 25px 0;
            border-bottom: 1px solid #e5e5e5;
            align-items: center;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 120px;
            height: 120px;
            background: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details h3 {
            font-size: 18px;
            font-weight: 600;
            color: #000;
            margin-bottom: 8px;
        }

        .item-details p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .item-price {
            font-size: 18px;
            font-weight: 700;
            color: #000;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            border-color: #000;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-end;
        }

        /* Buttons */
        .btn-primary {
            background: #000;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #333;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #fff;
            color: #000;
            padding: 12px 30px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s;
            margin-right: 15px;
        }

        .btn-secondary:hover {
            border-color: #000;
            transform: translateY(-2px);
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #666;
        }

        .btn-small:hover {
            border-color: #000;
            color: #000;
        }

        .btn-remove {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 12px;
            cursor: pointer;
            text-decoration: underline;
        }

        .btn-remove:hover {
            color: #c82333;
        }

        /* Cart Summary */
        .cart-summary {
            background: #f8f8f8;
            padding: 30px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .summary-row.total {
            border-top: 2px solid #000;
            padding-top: 15px;
            font-weight: 700;
            font-size: 20px;
        }

        .promo-code {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .promo-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .promo-btn {
            padding: 12px 20px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-cart-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #000;
        }

        .empty-cart p {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        /* Footer */
        .footer {
            background: #000;
            color: #fff;
            padding: 60px 20px 30px;
            margin-top: 80px;
        }

        @media (max-width: 768px) {
            .account-container {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 20px;
            }

            .settings-section {
                padding: 25px 20px;
            }

            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }

            .item-image {
                width: 80px;
                height: 80px;
            }

            .item-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: space-between;
                margin-top: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="main-nav-content">
            <?php foreach ($topCategories as $cat): ?>
                <div class="nav-category">
                    <a href="catalog.php?category=<?= urlencode($cat['slug']) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Shopping Cart Container -->
    <div class="account-container">
        <!-- Main Content -->
        <main class="account-content">
            <div class="content-header">
                <h1>Shopping Cart</h1>
                <p>Review your items and proceed to checkout</p>
            </div>

            <!-- Cart Items Section -->
            <section class="settings-section" id="cart">
                <h2 class="section-title">
                    <span class="section-icon">🛒</span>
                    Cart Items (3)
                </h2>

                <div class="cart-item">
                    <div class="item-image">
                        <img src="https://via.placeholder.com/120x120/f0f0f0/666?text=Jacket" alt="Designer Leather Jacket">
                    </div>
                    <div class="item-details">
                        <h3>Designer Leather Jacket</h3>
                        <p><strong>Brand:</strong> Saint Laurent</p>
                        <p><strong>Size:</strong> Medium</p>
                        <p><strong>Color:</strong> Black</p>
                        <p><strong>Condition:</strong> Excellent</p>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                            <input type="number" class="quantity-input" value="1" min="1" max="5">
                            <button class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                        </div>
                    </div>
                    <div class="item-actions">
                        <div class="item-price">$1,250.00</div>
                        <button class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                </div>

                <div class="cart-item">
                    <div class="item-image">
                        <img src="https://via.placeholder.com/120x120/f0f0f0/666?text=Sneakers" alt="Designer Sneakers">
                    </div>
                    <div class="item-details">
                        <h3>Designer Sneakers</h3>
                        <p><strong>Brand:</strong> Golden Goose</p>
                        <p><strong>Size:</strong> US 9</p>
                        <p><strong>Color:</strong> White/Gold</p>
                        <p><strong>Condition:</strong> Very Good</p>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                            <input type="number" class="quantity-input" value="1" min="1" max="5">
                            <button class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                        </div>
                    </div>
                    <div class="item-actions">
                        <div class="item-price">$425.00</div>
                        <a href="#" class="btn-small">Save for Later</a>
                        <button class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                </div>

                <div class="cart-item">
                    <div class="item-image">
                        <img src="https://via.placeholder.com/120x120/f0f0f0/666?text=Handbag" alt="Vintage Handbag">
                    </div>
                    <div class="item-details">
                        <h3>Vintage Handbag</h3>
                        <p><strong>Brand:</strong> Chanel</p>
                        <p><strong>Size:</strong> Medium</p>
                        <p><strong>Color:</strong> Black Quilted</p>
                        <p><strong>Condition:</strong> Good</p>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                            <input type="number" class="quantity-input" value="1" min="1" max="5">
                            <button class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                        </div>
                    </div>
                    <div class="item-actions">
                        <div class="item-price">$2,850.00</div>
                        <a href="#" class="btn-small">Save for Later</a>
                        <button class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="promo-code">
                        <input type="text" class="promo-input" placeholder="Enter promo code">
                        <button class="promo-btn">Apply</button>
                    </div>
                    
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">$4,525.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$25.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax:</span>
                        <span id="tax">$364.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Discount:</span>
                        <span id="discount">-$0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="total">$4,914.00</span>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <button class="btn-primary" style="width: 100%; margin-bottom: 10px;">Proceed to Checkout</button>
                        <button class="btn-secondary" style="width: 100%;">Continue Shopping</button>
                    </div>
                </div>
            </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>Company</h3>
                    <ul class="footer-links">
                        <li><a href="#">About</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">Authentication</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Selling</h3>
                    <ul class="footer-links">
                        <li><a href="#">Start Selling</a></li>
                        <li><a href="#">Seller Fees</a></li>
                        <li><a href="#">Seller Protection</a></li>
                        <li><a href="#">Seller Guide</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Legal</h3>
                    <ul class="footer-links">
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">Prohibited Items</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Railed. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../main.js"></script>
    <script>
        // Quantity update functionality
        function updateQuantity(button, change) {
            const input = button.parentElement.querySelector('.quantity-input');
            let currentValue = parseInt(input.value);
            let newValue = currentValue + change;
            
            if (newValue >= 1 && newValue <= 5) {
                input.value = newValue;
                updateCartTotal();
            }
        }

        // Remove item functionality
        function removeItem(button) {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                const cartItem = button.closest('.cart-item');
                cartItem.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    cartItem.remove();
                    updateCartTotal();
                    updateCartCount();
                }, 300);
            }
        }

        // Update cart totals
        function updateCartTotal() {
            const cartItems = document.querySelectorAll('.cart-item');
            let subtotal = 0;
            
            cartItems.forEach(item => {
                const priceText = item.querySelector('.item-price').textContent;
                const price = parseFloat(priceText.replace('$', '').replace(',', ''));
                const quantity = parseInt(item.querySelector('.quantity-input').value);
                subtotal += price * quantity;
            });
            
            const shipping = 25.00;
            const taxRate = 0.08;
            const tax = subtotal * taxRate;
            const discount = 0.00;
            const total = subtotal + shipping + tax - discount;
            
            document.getElementById('subtotal').textContent = `$${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            document.getElementById('tax').textContent = `$${tax.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            document.getElementById('discount').textContent = `-$${discount.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            document.getElementById('total').textContent = `$${total.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        }

        // Update cart count in header
        function updateCartCount() {
            const cartItems = document.querySelectorAll('.cart-item');
            const cartCount = cartItems.length;
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
                
                // Update section title
                const sectionTitle = document.querySelector('#cart .section-title');
                if (sectionTitle) {
                    sectionTitle.innerHTML = `<span class="section-icon">🛒</span>Cart Items (${cartCount})`;
                }
                
                // Show empty cart message if no items
                if (cartCount === 0) {
                    showEmptyCart();
                }
            }
        }

        // Show empty cart state
        function showEmptyCart() {
            const cartSection = document.getElementById('cart');
            cartSection.innerHTML = `
                <h2 class="section-title">
                    <span class="section-icon">🛒</span>
                    Cart Items (0)
                </h2>
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't added any items to your cart yet.</p>
                    <button class="btn-primary" onclick="window.location.href='../home.php'">Start Shopping</button>
                </div>
            `;
        }

        // Promo code functionality
        document.addEventListener('DOMContentLoaded', function() {
            const promoBtn = document.querySelector('.promo-btn');
            const promoInput = document.querySelector('.promo-input');
            
            if (promoBtn && promoInput) {
                promoBtn.addEventListener('click', function() {
                    const promoCode = promoInput.value.trim().toUpperCase();
                    applyPromoCode(promoCode);
                });
                
                promoInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const promoCode = promoInput.value.trim().toUpperCase();
                        applyPromoCode(promoCode);
                    }
                });
            }
        });

        // Apply promo code
        function applyPromoCode(code) {
            const validCodes = {
                'SAVE10': 0.10,
                'WELCOME15': 0.15,
                'FIRST20': 0.20,
                'STUDENT': 0.15
            };
            
            const subtotalElement = document.getElementById('subtotal');
            const discountElement = document.getElementById('discount');
            const promoInput = document.querySelector('.promo-input');
            
            if (validCodes[code]) {
                const subtotal = parseFloat(subtotalElement.textContent.replace('$', '').replace(',', ''));
                const discountAmount = subtotal * validCodes[code];
                
                discountElement.textContent = `-$${discountAmount.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                
                // Update total
                const shipping = 25.00;
                const tax = subtotal * 0.08;
                const total = subtotal + shipping + tax - discountAmount;
                document.getElementById('total').textContent = `$${total.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                
                // Show success message
                promoInput.style.borderColor = '#28a745';
                promoInput.value = `${code} Applied!`;
                setTimeout(() => {
                    promoInput.style.borderColor = '#ddd';
                    promoInput.value = '';
                    promoInput.placeholder = 'Promo code applied successfully!';
                }, 2000);
            } else {
                // Show error
                promoInput.style.borderColor = '#dc3545';
                promoInput.placeholder = 'Invalid promo code';
                setTimeout(() => {
                    promoInput.style.borderColor = '#ddd';
                    promoInput.placeholder = 'Enter promo code';
                }, 2000);
            }
        }

        // CSS Animation for fade out
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(-20px); }
            }
        `;
        document.head.appendChild(style);

        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartTotal();
            updateCartCount();
        });
    </script>

</body>
</html>