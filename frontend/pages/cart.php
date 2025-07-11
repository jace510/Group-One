<?php
//redirect if not logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /Group-One/backend/auth/login.php");
    exit();
}

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../backend/mongo.php'; // must define $client (MongoDB\Client)

use MongoDB\BSON\ObjectId;

// Get cart data from MongoDB
$cartsCollection = $client->selectCollection("Railed", "carts");
$productsCollection = $client->selectCollection("Railed", "products");

// Fetch cart data using the same logic as cart_operations.php
function getCartData($cartsCollection, $productsCollection, $userId)
{
    try {
        $cart = $cartsCollection->findOne(['user_id' => new ObjectId($userId)]);

        if (!$cart) {
            return ['items' => [], 'total' => 0, 'item_count' => 0];
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart['items'] as $item) {
            $product = $productsCollection->findOne(['_id' => $item['product_id']]);

            if ($product && $product['status'] === 'available') {
                $itemTotal = $product['pricing']['asking_price'] * $item['quantity'];
                $total += $itemTotal;

                $cartItems[] = [
                    'id' => $item['product_id']->__toString(),
                    'name' => $product['title'],
                    'brand' => $product['brand'],
                    'price' => $product['pricing']['asking_price'],
                    'quantity' => $item['quantity'],
                    'size' => $product['size'],
                    'color' => $product['color'],
                    'condition' => $product['condition'],
                    'photo' => isset($product['photos'][0]) ? (string) $product['photos'][0]['url'] : null,
                    'item_total' => $itemTotal
                ];
            }
        }

        return [
            'items' => $cartItems,
            'total' => $total,
            'item_count' => count($cartItems)
        ];

    } catch (Exception $e) {
        return ['items' => [], 'total' => 0, 'item_count' => 0];
    }
}

// Get cart data
$cartData = getCartData($cartsCollection, $productsCollection, $_SESSION['user_id']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'address', 'city', 'state', 'zip', 'country', 'card_number', 'expiry', 'cvv', 'cardholder_name'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }

    if (empty($errors) && !empty($cartData['items'])) {
        // Process the order (in a real app, you'd save to database, process payment, etc.)
        $order_id = 'ORD' . date('Ymd') . rand(1000, 9999);
        $_SESSION['last_order'] = [
            'order_id' => $order_id,
            'items' => $cartData['items'],
            'total' => calculateTotal($cartData['items']),
            'date' => date('Y-m-d H:i:s')
        ];

        // Clear cart after successful order using cart_operations.php logic
        try {
            $cartsCollection->deleteOne(['user_id' => new ObjectId($_SESSION['user_id'])]);
        } catch (Exception $e) {
            // Handle error if needed
        }

        // Redirect to success page
        header("Location: order_success.php?order_id=" . $order_id);
        exit();
    }
}

// Calculate totals
function calculateTotal($cartItems)
{
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shipping = 25.00;
    $tax = $subtotal * 0.08;
    return $subtotal + $shipping + $tax;
}

function calculateSubtotal($cartItems)
{
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    return $subtotal;
}

$subtotal = calculateSubtotal($cartData['items']);
$shipping = 25.00;
$tax = $subtotal * 0.08;
$total = $subtotal + $shipping + $tax;

include '../../backend/auth/header.php';
include '../modal.php';
include '../../backend/nav.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Railed</title>
    <link rel="stylesheet" href="../index.css">
    <style>
        /* Checkout Content */
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 60px;
        }

        .checkout-main {
            background: #fff;
        }

        .checkout-sidebar {
            background: #f8f8f8;
            padding: 30px;
            border-radius: 8px;
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #000;
        }

        .checkout-section {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e5e5e5;
        }

        .checkout-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #000;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #000;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #000;
        }

        .form-input.error {
            border-color: #dc3545;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
            cursor: pointer;
            box-sizing: border-box;
        }

        .form-select:focus {
            outline: none;
            border-color: #000;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .checkbox {
            width: 16px;
            height: 16px;
        }

        .checkbox-label {
            font-size: 14px;
            color: #666;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            color: #28a745;
            font-size: 14px;
            margin-top: 10px;
        }

        /* Order Summary */
        .order-summary h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #000;
        }

        .order-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e5e5;
        }

        .order-item:last-of-type {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            background: #f0f0f0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .item-details {
            flex: 1;
        }

        .item-brand {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .item-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #000;
        }

        .item-size {
            font-size: 12px;
            color: #666;
            margin-bottom: 2px;
        }

        .item-quantity {
            font-size: 12px;
            color: #666;
        }

        .item-price {
            font-size: 16px;
            font-weight: 700;
            color: #000;
            text-align: right;
        }

        .order-totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .total-row.final {
            font-size: 18px;
            font-weight: 700;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e5e5;
        }

        .complete-order-btn {
            width: 100%;
            background: #000;
            color: #fff;
            padding: 16px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 20px;
        }

        .complete-order-btn:hover {
            background: #333;
        }

        .complete-order-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .back-to-cart {
            display: inline-block;
            color: #000;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .back-to-cart:hover {
            border-color: #000;
            background: #f8f8f8;
        }

        .security-info {
            margin-top: 15px;
            padding: 15px;
            background: #f0f8ff;
            border-radius: 4px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

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

        .btn-primary {
            background: #000;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #333;
        }

        /* Footer */
        .footer {
            background: #000;
            color: #fff;
            padding: 60px 20px 30px;
            margin-top: 60px;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .checkout-sidebar {
                order: -1;
                position: relative;
                top: auto;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="main-nav-content">
            <?php foreach ($topCategories as $cat): ?>
                <div class="nav-category">
                    <a href="browse.php?category=<?= urlencode($cat['slug']) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Checkout Content -->
    <div class="checkout-container">
        <?php if (empty($cartData['items'])): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h3>Your cart is empty</h3>
                <p>You need items in your cart to checkout.</p>
                <a href="../home.php" class="btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="checkout-main">
                <h1 class="page-title">Checkout</h1>

                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="error-message">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <!-- Shipping Address -->
                    <div class="checkout-section">
                        <h2 class="section-title">Shipping Address</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-input" placeholder="Enter first name"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-input" placeholder="Enter last name"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-input" placeholder="Street address" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="address_2" class="form-input"
                                placeholder="Apartment, suite, etc. (optional)">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-input" placeholder="City" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">State</label>
                                <select name="state" class="form-select" required>
                                    <option value="">Select state</option>
                                    <option value="CA">California</option>
                                    <option value="NY">New York</option>
                                    <option value="TX">Texas</option>
                                    <option value="FL">Florida</option>
                                    <option value="IL">Illinois</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" name="zip" class="form-input" placeholder="ZIP code" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <select name="country" class="form-select" required>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="UK">United Kingdom</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-section">
                        <h2 class="section-title">Payment Method</h2>
                        <div class="form-group">
                            <label class="form-label">Card Number</label>
                            <input type="text" name="card_number" class="form-input" placeholder="1234 5678 9012 3456"
                                maxlength="19" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Expiry Date</label>
                                <input type="text" name="expiry" class="form-input" placeholder="MM/YY" maxlength="5"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">CVV</label>
                                <input type="text" name="cvv" class="form-input" placeholder="123" maxlength="4" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cardholder Name</label>
                            <input type="text" name="cardholder_name" class="form-input" placeholder="Name on card"
                                required>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" class="checkbox" id="billing-same" name="billing_same" value="1">
                            <label for="billing-same" class="checkbox-label">Billing address same as shipping</label>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="checkout-section">
                        <h2 class="section-title">Order Notes (Optional)</h2>
                        <div class="form-group">
                            <textarea name="order_notes" class="form-input" rows="4"
                                placeholder="Special delivery instructions or notes..."></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="complete_order" value="1">
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="checkout-sidebar">
                <div class="order-summary">
                    <h3>Order Summary</h3>

                    <?php foreach ($cartData['items'] as $item): ?>
                        <div class="order-item">
                            <div class="item-image">
                                <?php if (is_string($item['photo']) && strpos($item['photo'], 'http') === 0): ?>
                                    <img src="<?php echo htmlspecialchars($item['photo']); ?>"
                                        alt="<?php echo htmlspecialchars($item['name']); ?>"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($item['photo']); ?>"
                                        alt="<?php echo htmlspecialchars($item['name']); ?>"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                <?php endif; ?>

                            </div>
                            <div class="item-details">
                                <div class="item-brand"><?php echo htmlspecialchars($item['brand']); ?></div>
                                <div class="item-title"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="item-size">Size <?php echo htmlspecialchars($item['size']); ?></div>
                                <div class="item-quantity">Qty: <?php echo $item['quantity']; ?></div>
                            </div>
                            <div class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="order-totals">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span>Shipping</span>
                            <span>$<?php echo number_format($shipping, 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span>Tax</span>
                            <span>$<?php echo number_format($tax, 2); ?></span>
                        </div>
                        <div class="total-row final">
                            <span>Total</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>

                    <button type="submit" form="checkout-form" class="complete-order-btn" id="complete-order-btn">
                        Complete Order
                    </button>

                    <div class="security-info">
                        🔒 Your payment information is secure and encrypted
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const completeOrderBtn = document.getElementById('complete-order-btn');

            // Add form ID for button reference
            if (form) {
                form.id = 'checkout-form';
            }

            // Form validation and interaction
            const inputs = document.querySelectorAll('.form-input, .form-select');
            inputs.forEach(input => {
                input.addEventListener('focus', function () {
                    this.style.borderColor = '#000';
                    this.classList.remove('error');
                });

                input.addEventListener('blur', function () {
                    if (!this.value && this.required) {
                        this.classList.add('error');
                    } else {
                        this.style.borderColor = '#ddd';
                    }
                });
            });

            // Card number formatting
            const cardInput = document.querySelector('input[name="card_number"]');
            if (cardInput) {
                cardInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                    e.target.value = formattedValue;
                });
            }

            // Expiry date formatting
            const expiryInput = document.querySelector('input[name="expiry"]');
            if (expiryInput) {
                expiryInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }

            // CVV input restriction
            const cvvInput = document.querySelector('input[name="cvv"]');
            if (cvvInput) {
                cvvInput.addEventListener('input', function (e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }

            // Complete order button
            if (completeOrderBtn) {
                completeOrderBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Validate form
                    const requiredInputs = form.querySelectorAll('[required]');
                    let isValid = true;

                    requiredInputs.forEach(input => {
                        if (!input.value.trim()) {
                            input.classList.add('error');
                            isValid = false;
                        } else {
                            input.classList.remove('error');
                        }
                    });

                    if (isValid) {
                        // Add loading state
                        this.innerHTML = 'Processing...';
                        this.disabled = true;

                        // Submit form
                        setTimeout(() => {
                            form.submit();
                        }, 1000);
                    } else {
                        alert('Please fill in all required fields');
                    }
                });
            }

            // Billing same as shipping checkbox
            const billingSameCheckbox = document.getElementById('billing-same');
            if (billingSameCheckbox) {
                billingSameCheckbox.addEventListener('change', function () {
                    // In a real implementation, this would copy shipping address to billing
                    console.log('Billing same as shipping:', this.checked);
                });
            }
        });
    </script>
</body>

</html>