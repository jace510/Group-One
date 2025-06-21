<?php include '../backend/auth/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Railed: Online Marketplace to Buy Fashion</title>
    <link rel="stylesheet" href="index.css">
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 3rem;
            margin-top: 40px;
            padding: 6rem;
        }
    </style>
</head>

<body>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="main-nav-content">
            <div class="nav-category"><a href="#">Designers</a></div>
            <div class="nav-category"><a href="#">Menswear</a></div>
            <div class="nav-category"><a href="#">Womenswear</a></div>
            <div class="nav-category"><a href="#">Sale</a></div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>The Global Marketplace for Fashion</h1>
            <p>Buy, sell and discover authenticated pieces from top brands, spanning designer, vintage, streetwear and
                more.</p>
            <div class="cta-buttons">
                <a href="#" class="cta-btn cta-primary">Shop Now</a>
                <a href="#" class="cta-btn cta-secondary">Start Selling</a>
            </div>
        </div>
    </section>

    <!-- Featured Brands -->
    <section class="featured-brands">
        <div class="section-content">
            <h2 class="section-title">Shop Popular Designers</h2>
            <div class="brands-grid">
                <div class="brand-card">
                    <div class="brand-name">Supreme</div>
                </div>
                <div class="brand-card">
                    <div class="brand-name">Chrome Hearts</div>
                </div>
                <div class="brand-card">
                    <div class="brand-name">Rick Owens</div>
                </div>
                <div class="brand-card">
                    <div class="brand-name">Balenciaga</div>
                </div>
                <div class="brand-card">
                    <div class="brand-name">Stone Island</div>
                </div>
                <div class="brand-card">
                    <div class="brand-name">Comme des Garçons</div>
                </div>
                <div class="brand-card">
                    <div class="brand-name">Maison Margiela</div>
                </div>
                <div class="brand-card">
                    <div class="brand-name">Kapital</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Items -->
    <section class="trending-items">
        <div class="section-content">
            <h2 class="section-title">Trending Now</h2>
            <div class="product-grid">
                <div class="product-card">
                    <div class="product-image">👕</div>
                    <div class="product-info">
                        <div class="product-brand">Supreme</div>
                        <div class="product-title">Box Logo Hoodie</div>
                        <div class="product-price">$450</div>
                        <div class="product-size">Size M</div>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-image">👟</div>
                    <div class="product-info">
                        <div class="product-brand">Nike</div>
                        <div class="product-title">Air Jordan 1 Retro High</div>
                        <div class="product-price">$325</div>
                        <div class="product-size">Size 10</div>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-image">🧥</div>
                    <div class="product-info">
                        <div class="product-brand">Stone Island</div>
                        <div class="product-title">Nylon Metal Jacket</div>
                        <div class="product-price">$275</div>
                        <div class="product-size">Size L</div>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-image">👖</div>
                    <div class="product-info">
                        <div class="product-brand">Chrome Hearts</div>
                        <div class="product-title">Cross Patch Jeans</div>
                        <div class="product-price">$850</div>
                        <div class="product-size">Size 32</div>
                    </div>
                </div>
            </div>
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
    <script>
        // Add some interactive behavior
        document.addEventListener('DOMContentLoaded', function () {
            // Search input focus effect
            const searchInput = document.querySelector('.search-input');
            searchInput.addEventListener('focus', function () {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            searchInput.addEventListener('blur', function () {
                this.parentElement.style.transform = 'scale(1)';
            });

            // Product card hover effects
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.cursor = 'pointer';
                });
            });

            // Brand card click effect
            const brandCards = document.querySelectorAll('.brand-card');
            brandCards.forEach(card => {
                card.addEventListener('click', function () {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'translateY(-5px)';
                    }, 100);
                });
            });
        });
    </script>

    <?php
    $openLogin = isset($_GET['auth']) && $_GET['auth'] === 'required';
    $redirectTo = isset($_GET['redirect']) ? $_GET['redirect'] : '';
    ?>

    <?php include 'modal.php'; ?>
    
    <script src="main.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const authFailed = urlParams.get('auth');

            if (authFailed === 'failed') {
                openModal('loginModal');
            }
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if ($openLogin): ?>
                openModal('loginModal');
            <?php endif; ?>
        });
    </script>

    <?php
    $loginFailed = isset($_GET['auth']) && $_GET['auth'] === 'failed';
    $redirectTo = isset($_GET['redirect']) ? $_GET['redirect'] : '';
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if ($loginFailed): ?>
                openModal('loginModal');
                alert('Login failed. Please check your credentials.');
            <?php endif; ?>
        });
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const show = params.get('show');
            if (show === 'login') {
                openModal('loginModal');
            }
        });
    </script>

    <?php if (isset($_GET['auth']) && $_GET['auth'] === 'register_success'): ?>
        <script>
            alert("Registration successful! Please log in.");
        </script>
    <?php endif; ?>

</body>

</html>