<?php
include '../../backend/auth/header.php';
include '../modal.php';
include '../../backend/nav.php';

require '../../backend/mongo.php'; // Adjust path if needed
$collection = $client->Railed->products;

$categorySlug = $_GET['category'] ?? null;
$category = null;
$items = [];
$itemCount = 0;

if ($categorySlug) {
    $categories = $client->Railed->categories;
    $products = $client->Railed->products;

    // Find category
    $category = $categories->findOne(['slug' => $categorySlug]);

    if ($category) {
        $cursor = $products->find([
            'category_id' => $category['_id'],
            'status' => 'available'
        ]);

        $items = iterator_to_array($cursor);
        $itemCount = count($items);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Railed: Browse Catalog</title>
    <link rel="stylesheet" href="../index.css">
    <style>
        .nav-category.active>a {
            color: #000;
            border-bottom: 2px solid #000;
            padding-bottom: 13px;
        }

        /* Catalog Container */
        .catalog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 40px;
        }

        /* Filters Sidebar */
        .filters-sidebar {
            background: #f8f8f8;
            padding: 30px;
            border-radius: 8px;
            height: fit-content;
            position: sticky;
            top: 120px;
        }

        .filters-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #000;
        }

        .filter-group {
            margin-bottom: 30px;
        }

        .filter-group-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            transition: color 0.2s;
        }

        .filter-option:hover {
            color: #000;
        }

        .filter-checkbox {
            width: 16px;
            height: 16px;
        }

        .price-range {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .price-input {
            width: 80px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .clear-filters {
            background: none;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            margin-top: 20px;
        }

        .clear-filters:hover {
            border-color: #000;
            background: #f8f8f8;
        }

        /* Catalog Content */
        .catalog-content {
            min-height: 100vh;
        }

        .catalog-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e5e5;
        }

        .catalog-title {
            font-size: 28px;
            font-weight: 700;
            color: #000;
        }

        .catalog-info {
            font-size: 14px;
            color: #666;
        }

        .sort-options {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .sort-label {
            font-size: 14px;
            color: #666;
        }

        .sort-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
        }

        .view-toggle {
            display: flex;
            gap: 5px;
        }

        .view-btn {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
        }

        .view-btn.active {
            background: #000;
            color: #fff;
            border-color: #000;
        }

        .product-grid.list-view {
            grid-template-columns: 1fr;
        }

        .product-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
            border: 1px solid #e5e5e5;
            cursor: pointer;
        }

        .product-image {
            width: 100%;
            height: 300px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #ccc;
            position: relative;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #000;
            color: #fff;
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 4px;
            font-weight: 600;
        }

        .product-badge.sold {
            background: #ff4444;
        }

        .product-badge.new {
            background: #00aa44;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #000;
            margin-bottom: 5px;
        }

        .product-size {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }

        .product-condition {
            font-size: 12px;
            color: #666;
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        /* List View Specific Styles */
        .product-card.list-item {
            display: grid;
            grid-template-columns: 200px 1fr;
            height: 200px;
        }

        .product-card.list-item .product-image {
            height: 100%;
        }

        .product-card.list-item .product-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 30px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 60px;
            padding: 40px 0;
        }

        .pagination-btn {
            padding: 10px 15px;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 4px;
        }

        .pagination-btn:hover {
            border-color: #000;
        }

        .pagination-btn.active {
            background: #000;
            color: #fff;
            border-color: #000;
        }




        /* Responsive */
        @media (max-width: 968px) {
            .catalog-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .filters-sidebar {
                position: static;
                order: 2;
            }

            .catalog-header {
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
            }

            .sort-options {
                width: 100%;
                justify-content: space-between;
            }
        }


        .product-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .product-card.list-item {
            grid-template-columns: 1fr;
            height: auto;
        }

        .filters-sidebar {
            padding: 20px;
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

    <!-- Catalog Container -->
    <div class="catalog-container">
        <!-- Filters Sidebar -->
        <aside class="filters-sidebar">
            <h3 class="filters-title">Filters</h3>

            <div class="filter-group">
                <h4 class="filter-group-title">Category</h4>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        T-Shirts & Polos
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Hoodies & Sweatshirts
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Jackets & Coats
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Jeans & Denim
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Sneakers
                    </label>
                </div>
            </div>

            <div class="filter-group">
                <h4 class="filter-group-title">Brand</h4>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Supreme
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Chrome Hearts
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Rick Owens
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Stone Island
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Balenciaga
                    </label>
                </div>
            </div>

            <div class="filter-group">
                <h4 class="filter-group-title">Size</h4>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        XS
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        S
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        M
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        L
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        XL
                    </label>
                </div>
            </div>

            <div class="filter-group">
                <h4 class="filter-group-title">Price Range</h4>
                <div class="price-range">
                    <input type="number" class="price-input" placeholder="Min" min="0">
                    <span>-</span>
                    <input type="number" class="price-input" placeholder="Max" min="0">
                </div>
            </div>

            <div class="filter-group">
                <h4 class="filter-group-title">Condition</h4>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        New with Tags
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        New without Tags
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Very Good
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" class="filter-checkbox">
                        Good
                    </label>
                </div>
            </div>

            <button class="clear-filters">Clear All Filters</button>
        </aside>

        <!-- Catalog Content -->
        <main class="catalog-content">
            <div class="catalog-header">
                <div>
                    <h1 class="catalog-title">
                        <?= isset($category['name']) ? htmlspecialchars($category['name']) : 'Unknown Category' ?>
                    </h1>
                    <p class="catalog-info"><?= $itemCount ?? 0 ?> items available</p>
                </div>
            </div>

            <div class="product-grid" id="productGrid">
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $title = htmlspecialchars($item['title'] ?? 'Untitled');
                        $brand = htmlspecialchars($item['brand'] ?? 'Unknown');
                        $price = number_format($item['pricing']['asking_price'] ?? 0, 2);
                        $size = htmlspecialchars($item['size'] ?? '-');
                        $condition = htmlspecialchars($item['condition']['name'] ?? 'Unknown');
                        $img = !empty($item['photos'][0]['url']) ? htmlspecialchars($item['photos'][0]['url']) : 'default.jpg';
                        $badge = !empty($item['condition']['name'])
                            ? '<div class="product-badge">' . htmlspecialchars($item['condition']['name']) . '</div>'
                            : '';
                        ?>

                        <a href="product-page.php?id=<?= $item['_id'] ?>" div class="product-card">
                            <div class="product-image">
                                <?= $badge ?>
                                <img src="<?= $img ?>" alt="<?= $title ?>" />
                            </div>
                            <div class="product-info">
                                <div class="product-brand"><?= $brand ?></div>
                                <div class="product-title"><?= $title ?></div>
                                <div class="product-price">$<?= $price ?></div>
                                <div class="product-size">Size <?= $size ?></div>
                                <div class="product-condition"><?= $condition ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products found in this category.</p>
                <?php endif; ?>
            </div>
        </main>

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

    <?php include '../modal.php' ?>
    <script src="../main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // View toggle functionality
            const viewButtons = document.querySelectorAll('.view-btn');
            const productGrid = document.getElementById('productGrid');

            viewButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    // Remove active class from all buttons
                    viewButtons.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');

                    const view = this.dataset.view;
                    if (view === 'list') {
                        productGrid.classList.add('list-view');
                        // Add list-item class to all product cards
                        document.querySelectorAll('.product-card').forEach(card => {
                            card.classList.add('list-item');
                        });
                    } else {
                        productGrid.classList.remove('list-view');
                        // Remove list-item class from all product cards
                        document.querySelectorAll('.product-card').forEach(card => {
                            card.classList.remove('list-item');
                        });
                    }
                });
            });

            // Filter functionality
            const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
            filterCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    // Add filter logic here
                    console.log('Filter changed:', this.parentElement.textContent.trim());
                });
            });

            // Clear filters
            const clearFiltersBtn = document.querySelector('.clear-filters');
            clearFiltersBtn.addEventListener('click', function () {
                filterCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                document.querySelectorAll('.price-input').forEach(input => {
                    input.value = '';
                });
            });

            // Sort functionality
            const sortSelect = document.querySelector('.sort-select');
            sortSelect.addEventListener('change', function () {
                console.log('Sort changed to:', this.value);
                // Add sorting logic here
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function () {
                console.log('Search query:', this.value);
                // Add search logic here
            });

            // Product card click
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.addEventListener('click', function () {
                    // Navigate to product detail page
                    console.log('Navigate to product detail');
                });
            });

            // Pagination
            const paginationBtns = document.querySelectorAll('.pagination-btn');
            paginationBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    if (!isNaN(this.textContent)) {
                        // Remove active from all
                        paginationBtns.forEach(b => b.classList.remove('active'));
                        // Add active to clicked
                        this.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>

</html>