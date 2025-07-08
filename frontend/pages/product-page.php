<?php
include '../../backend/auth/header.php';
include '../../backend/nav.php';

include '../modal.php';

require '../../backend/mongo.php';
use MongoDB\BSON\ObjectId;

$productId = $_GET['id'] ?? null;

if (!$productId) {
    die('Product ID not specified');
}

// Fetch product
$product = $client->Railed->products->findOne([
    '_id' => new MongoDB\BSON\ObjectId($productId)
]);

if (!$product) {
    die('Product not found');
}

// Fetch category (no nesting)
$category = $client->Railed->categories->findOne([
    '_id' => $product['category_id']
]);

// Exclude the current product from the results
$relatedCursor = $client->Railed->products->find(
    [
        '_id' => ['$ne' => $product['_id']],
        'status' => 'available' // optional filter
    ],
    [
        'limit' => 4, // Limit how many to show
        'sort' => ['created_at' => -1] // or use $sample later if you want full randomness
    ]
);

$relatedItems = iterator_to_array($relatedCursor);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supreme Box Logo Hoodie Black - Railed</title>
    <link rel="stylesheet" href="../index.css">
    <style>
        /* Header - Same as base */

        /* Breadcrumb */
        .breadcrumb {
            background: #f8f8f8;
            padding: 15px 20px;
            border-bottom: 1px solid #e5e5e5;
        }

        .breadcrumb-content {
            max-width: 1200px;
            margin: 0 auto;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #000;
        }

        /* Product Detail Container */
        .product-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
        }

        /* Product Images */
        .product-images {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .main-image {
            width: 100%;
            height: 600px;
            background: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
            color: #ccc;
            position: relative;
            overflow: hidden;
            cursor: zoom-in;
        }

        .product-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #00aa44;
            color: #fff;
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .thumbnail-container {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* fills container and crops image */
            display: block;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            background: #f0f0f0;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
            flex-shrink: 0;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: #000;
        }

        /* Product Info */
        .product-info {
            padding: 20px 0;
        }

        .product-brand {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .product-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #000;
            line-height: 1.2;
        }

        .product-price {
            font-size: 28px;
            font-weight: 700;
            color: #000;
            margin-bottom: 30px;
        }

        .product-details {
            margin-bottom: 40px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e5e5e5;
            font-size: 14px;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #000;
            font-weight: 600;
        }

        .condition-badge {
            background: #00aa44;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Size Selection */
        .size-section {
            margin-bottom: 30px;
        }

        .size-label {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #000;
        }

        .size-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .size-option {
            padding: 12px 20px;
            border: 2px solid #ddd;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .size-option:hover {
            border-color: #000;
        }

        .size-option.selected {
            background: #000;
            color: #fff;
            border-color: #000;
        }

        .size-option.unavailable {
            background: #f5f5f5;
            color: #ccc;
            cursor: not-allowed;
            border-color: #e5e5e5;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 40px;
        }

        .buy-btn {
            background: #000;
            color: #fff;
            padding: 18px 24px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .buy-btn:hover {
            background: #333;
            transform: translateY(-2px);
        }

        .buy-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .wishlist-btn {
            background: #fff;
            color: #000;
            padding: 18px 24px;
            border: 2px solid #000;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .wishlist-btn:hover {
            background: #000;
            color: #fff;
        }

        /* Product Description */
        .product-description {
            margin-bottom: 40px;
        }

        .description-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #000;
        }

        .description-text {
            font-size: 14px;
            line-height: 1.6;
            color: #666;
        }

        /* Seller Info */
        .seller-info {
            background: #f8f8f8;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 40px;
        }

        .seller-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .seller-avatar {
            width: 50px;
            height: 50px;
            background: #ddd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #666;
        }

        .seller-details h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .seller-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            color: #666;
        }

        .stars {
            color: #ffa500;
        }

        .seller-stats {
            display: flex;
            gap: 30px;
            font-size: 14px;
            color: #666;
        }

        /* Authentication Badge */
        .auth-badge {
            background: #000;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-badge h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .auth-badge p {
            font-size: 14px;
            opacity: 0.8;
        }

        /* Related Products */
        .related-products {
            max-width: 1200px;
            margin: 80px auto 0;
            padding: 0 20px;
        }

        .related-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .related-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
            border: 1px solid #e5e5e5;
            cursor: pointer;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .related-image {
            width: 100%;
            height: 250px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #ccc;
        }

        .related-info {
            padding: 20px;
        }

        .related-brand {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .related-title-text {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #000;
        }

        .related-price {
            font-size: 16px;
            font-weight: 700;
            color: #000;
        }

        /* Footer */
        .footer {
            background: #000;
            color: #fff;
            padding: 60px 20px 30px;
            margin-top: 80px;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .product-detail-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .main-image {
                height: 500px;
            }

            .product-title {
                font-size: 28px;
            }

            .product-price {
                font-size: 24px;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                justify-content: space-between;
                padding: 15px 20px;
            }


            .main-image {
                height: 400px;
                font-size: 80px;
            }

            .product-detail-container {
                padding: 30px 20px;
            }

            .action-buttons {
                position: sticky;
                bottom: 20px;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            }

            .related-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
            }
        }
    </style>
</head>

<body>


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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="breadcrumb-content">
            <a href="/group-one/frontend/home.php">Home</a> /
            <a href="catalog.php?category=<?= urlencode($category['slug']) ?>">
                <?= htmlspecialchars($category['name']) ?>
            </a> /
            <?= htmlspecialchars($product['title']) ?>
        </div>
    </div>


    <!-- Product Detail Container -->
    <div class="product-detail-container">
        <div class="product-images">
            <div class="main-image" id="mainImage">
                <?php if (!empty($product['condition']['name'])): ?>
                    <div class="product-badge"><?= htmlspecialchars($product['condition']) ?></div>
                <?php endif; ?>
                <img src="<?= htmlspecialchars($product['photos'][0]['url'] ?? 'default.jpg') ?>" alt="Main Image">
            </div>

            <div class="thumbnail-container">
                <?php foreach ($product['photos'] ?? [] as $image): ?>
                    <div class="thumbnail" data-image="<?= htmlspecialchars($image['url']) ?>">
                        <img src="<?= htmlspecialchars($image['thumbnail_url'] ?? $image['url']) ?>" alt="Thumb">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="product-info">
            <div class="product-brand"><?= htmlspecialchars($product['brand']) ?></div>
            <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>
            <div class="product-price">$<?= number_format($product['pricing']['asking_price'], 2) ?></div>


            <div class="product-details">
                <div class="detail-row">
                    <span class="detail-label">Size</span>
                    <span class="detail-value"><?= htmlspecialchars($product['size'] ?? '-') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Condition</span>
                    <span class="detail-value"><span
                            class="condition-badge"><?= htmlspecialchars($product['condition'] ?? '-') ?></span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Color</span>
                    <span class="detail-value"><?= htmlspecialchars($product['color'] ?? '-') ?></span>
                </div>
            </div>


            <div class="size-section">
                <div class="size-label">Available Sizes</div>
                <div class="size-options">
                    <?php foreach ($product['stock'] ?? [] as $stock): ?>
                        <div class="size-option <?= !$stock['is_available'] ? 'unavailable' : '' ?>">
                            <?= htmlspecialchars($stock['size']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="action-buttons">
                <button class="buy-btn" id="buyBtn">Buy Now</button>
                <button class="wishlist-btn" id="wishlistBtn">Add to Cart</button>
            </div>

            <div class="product-description">
                <h3 class="description-title">Description</h3>
                <p class="description-text"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>


            <div class="seller-info">
                <div class="seller-header">
                    <div class="seller-avatar">👤</div>
                    <div class="seller-details">
                        <h4><?= htmlspecialchars($product['seller_info']['username'] ?? 'Unknown') ?></h4>
                        <div class="seller-rating">
                            <span class="stars">★★★★★</span>
                            <span><?= htmlspecialchars($product['seller_info']['rating'] ?? 0) ?>
                                (<?= htmlspecialchars($product['seller_info']['review_count'] ?? 0) ?> reviews)</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="auth-badge">
                <h3>🔐 Authentication Guaranteed</h3>
                <p>Every item is verified by our team of experts before shipping</p>
            </div>
        </div>
    </div>

    <section class="related-products">
        <h2 class="related-title">You Might Also Like</h2>
        <div class="related-grid">
            <?php foreach ($relatedItems as $rel): ?>
                <?php
                $img = !empty($rel['photos'][0]['url']) ? $rel['images'][0]['url'] : 'default.jpg';
                $price = number_format($rel['pricing']['asking_price'] ?? 0, 2);
                ?>
                <a href="product.php?id=<?= $rel['_id'] ?>" class="related-card">
                    <div class="related-image">
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($rel['title']) ?>">
                    </div>
                    <div class="related-info">
                        <div class="related-brand"><?= htmlspecialchars($rel['brand']) ?></div>
                        <div class="related-title-text"><?= htmlspecialchars($rel['title']) ?></div>
                        <div class="related-price">$<?= $price ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>


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

    <script src="../main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Thumbnail image switching
            const thumbnails = document.querySelectorAll('.thumbnail');
            const mainImage = document.getElementById('mainImage');

            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function () {
                    // Remove active class from all thumbnails
                    thumbnails.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked thumbnail
                    this.classList.add('active');

                    // Update main image
                    const imageData = this.dataset.image;
                    mainImage.innerHTML = `<div class="product-badge">NEW</div>${imageData}`;
                });
            });

            // Size selection
            const sizeOptions = document.querySelectorAll('.size-option:not(.unavailable)');
            sizeOptions.forEach(size => {
                size.addEventListener('click', function () {
                    // Remove selected class from all sizes
                    sizeOptions.forEach(s => s.classList.remove('selected'));
                    // Add selected class to clicked size
                    this.classList.add('selected');
                });
            });

            // Buy button functionality
            const buyBtn = document.getElementById('buyBtn');
            buyBtn.addEventListener('click', function () {
                const selectedSize = document.querySelector('.size-option.selected');
                if (selectedSize) {
                    alert(`Added ${selectedSize.textContent} to cart!`);
                } else {
                    alert('Please select a size first.');
                }
            });

            // Wishlist button functionality
            const wishlistBtn = document.getElementById('wishlistBtn');
            let isWishlisted = false;

            wishlistBtn.addEventListener('click', function () {
                isWishlisted = !isWishlisted;
                if (isWishlisted) {
                    this.textContent = 'Remove from Wishlist ♥';
                    this.style.background = '#ff4444';
                    this.style.color = '#fff';
                    this.style.borderColor = '#ff4444';
                } else {
                    this.textContent = 'Add to Wishlist ♡';
                    this.style.background = '#fff';
                    this.style.color = '#000';
                    this.style.borderColor = '#000';
                }
            });

            // Related product click
            const relatedCards = document.querySelectorAll('.related-card');
            relatedCards.forEach(card => {
                card.addEventListener('click', function () {
                    console.log('Navigate to related product');
                    // Add navigation logic here
                });
            });

            // Main image zoom effect
            mainImage.addEventListener('click', function () {
                this.style.transform = this.style.transform === 'scale(1.5)' ? 'scale(1)' : 'scale(1.5)';
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                console.log('Search query:', this.value);
                // Add search logic here
            });
        });
    </script>
</body>

</html>