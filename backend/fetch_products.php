<?php
require 'mongo.php'; // MongoDB client setup

$collection = $client->Railed->products;

// Fetch latest 8 available items
$products = $collection->find(
    ['status' => 'available'],
    [
        'limit' => 8,
        'sort' => ['created_at' => -1]
    ]
);
?>

<section class="trending-items">
    <div class="section-content">
        <h2 class="section-title">Trending Now</h2>
        <div class="product-grid">

            <?php foreach ($products as $product): ?>
                <?php
                // Get primary photo
                $primaryPhoto = 'default.jpg'; // fallback image
                if (!empty($product['photos'])) {
                    foreach ($product['photos'] as $photo) {
                        if (!empty($photo['is_primary'])) {
                            $primaryPhoto = $photo['url'];
                            break;
                        }
                    }
                }
                
                $img = !empty($product['photos']) ? $product['photos'][0]['url'] : 'default.jpg';

                // Format price
                $price = number_format($product['pricing']['asking_price'] ?? 0, 2);
                ?>

                <div class="product-card">
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($primaryPhoto) ?>" alt="Product Image">
                    </div>
                    <div class="product-info">
                        <div class="product-brand"><?= htmlspecialchars($product['brand']) ?></div>
                        <div class="product-title"><?= htmlspecialchars($product['title']) ?></div>
                        <div class="product-price">$<?= $price ?></div>
                        <div class="product-size">Size <?= htmlspecialchars($product['size']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>