<?php
session_start();
require_once 'config/database.php';

// Fetch all products from the database
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - E-commerce Store</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f5f5f5;
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        
        .product-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .product-price {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .view-details-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .view-details-btn:hover {
            background: #34495e;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            color: #7f8c8d;
            font-size: 16px;
        }
        
        .no-products {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
            font-size: 18px;
        }
        
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
        }
        
        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #3498db;
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }
            
            .page-title {
                font-size: 24px;
            }
            
            .navbar-content {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="products.php" class="navbar-brand">E-commerce Store</a>
            <div class="nav-links">
                <a href="products.php">Products</a>
                <a href="shopping-basket.html">Shopping Basket</a>
                <a href="purchase.html">Purchase</a>
            </div>
        </div>
    </nav>

    <div class="products-container">
        <div class="page-header">
            <h1 class="page-title">Our Products</h1>
            <p class="page-subtitle">Discover our amazing collection of products</p>
        </div>
        
        <?php if (empty($products)): ?>
            <div class="no-products">
                <p>No products available at the moment.</p>
                <p>Please check back later!</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/300x200?text=Product+Image'); ?>" 
                             class="product-image" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='https://via.placeholder.com/300x200?text=Product+Image'">
                        <div class="product-info">
                            <h3 class="product-name">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h3>
                            <p class="product-description">
                                <?php 
                                $description = htmlspecialchars($product['description'] ?? 'No description available');
                                echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                ?>
                            </p>
                            <div class="product-price">
                                $<?php echo number_format($product['price'] ?? 0, 2); ?>
                            </div>
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" 
                               class="view-details-btn">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="background: #34495e; color: white; text-align: center; padding: 20px; margin-top: 50px;">
        <p>&copy; 2024 E-commerce Store. All rights reserved.</p>
    </footer>
</body>
</html>
