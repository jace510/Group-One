<?php
include '../../backend/auth/header.php';
include '../modal.php';
include '../../backend/nav.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Your Fashion - Railed</title>
    <link rel="stylesheet" href="../index.css">
    <style>
        /* Sell Page Hero */
        .sell-hero {
            background: linear-gradient(135deg, #000 0%, #333 100%);
            color: #fff;
            padding: 80px 20px;
            text-align: center;
        }

        .sell-hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .sell-hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .sell-hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.9;
            line-height: 1.5;
        }

        .cta-button {
            display: inline-block;
            background: #fff;
            color: #000;
            padding: 18px 40px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
            background: #f0f0f0;
        }

        /* Sell Steps */
        .sell-steps {
            padding: 80px 20px;
            background: #f8f8f8;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 60px;
            color: #000;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-bottom: 60px;
        }

        .step-card {
            background: #fff;
            padding: 50px 40px;
            text-align: center;
            border-radius: 12px;
            transition: all 0.3s;
            border: 1px solid #e5e5e5;
            position: relative;
            overflow: hidden;
        }

        .step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #000 0%, #333 100%);
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .step-card:hover::before {
            transform: scaleX(1);
        }

        .step-icon {
            font-size: 56px;
            margin-bottom: 25px;
        }

        .step-number {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: #000;
            color: #fff;
            border-radius: 50%;
            line-height: 50px;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 20px;
        }

        .step-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #000;
        }

        .step-description {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
        }

        .steps-cta {
            text-align: center;
            margin-top: 40px;
        }

        .secondary-cta {
            display: inline-block;
            background: #000;
            color: #fff;
            padding: 16px 35px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .secondary-cta:hover {
            background: #333;
            transform: translateY(-2px);
        }

        /* Seller Benefits */
        .seller-benefits {
            padding: 80px 20px;
            background: #000;
            color: #fff;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 50px;
            margin-top: 60px;
        }

        .benefit-card {
            text-align: center;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: all 0.3s;
        }

        .benefit-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
        }

        .benefit-icon {
            font-size: 56px;
            margin-bottom: 25px;
        }

        .benefit-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
        }

        .benefit-description {
            font-size: 16px;
            color: #ccc;
            line-height: 1.6;
        }

        .sell-hero h1 {
            font-size: 36px;
        }

        .sell-hero p {
            font-size: 18px;
        }

        .steps-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .step-card {
            padding: 40px 30px;
        }

        .benefits-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    </style>
</head>

<body>


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
    <!-- Sell Hero Section -->
    <section class="sell-hero">
        <div class="sell-hero-content">
            <h1>Start Selling on Railed</h1>
            <p>Turn your closet into cash. Sell authentic fashion pieces to buyers worldwide with our trusted
                marketplace.</p>
            <a href="#" class="cta-button" onclick="goToListingPage()">Start
                Selling Now</a>
        </div>
    </section>

    <!-- How It Works -->
    <section class="sell-steps">
        <div class="section-content">
            <h2 class="section-title">How Selling Works</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">📸</div>
                    <div class="step-title">List Your Item</div>
                    <div class="step-description">Upload photos and details of your authentic fashion pieces. Our team
                        will verify authenticity to ensure buyer confidence.</div>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">💰</div>
                    <div class="step-title">Set Your Price</div>
                    <div class="step-description">Price competitively based on condition and market demand. We provide
                        pricing guidance to help maximize your earnings.</div>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-title">Ship & Get Paid</div>
                    <div class="step-icon">📦</div>
                    <div class="step-description">Once sold, ship your item with our prepaid label and get paid within
                        24 hours of delivery confirmation.</div>
                </div>
            </div>
            <div class="steps-cta">
                <a href="#" class="secondary-cta" onclick="goToListingPage()">List Your First Item</a>
            </div>
        </div>
    </section>

    <!-- Seller Benefits -->
    <section class="seller-benefits">
        <div class="section-content">
            <h2 class="section-title" style="color: #fff;">Why Sell on Railed</h2>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">🔒</div>
                    <div class="benefit-title">Secure Transactions</div>
                    <div class="benefit-description">All payments are processed securely and held until the buyer
                        confirms receipt, protecting both parties.</div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">✅</div>
                    <div class="benefit-title">Authentication Service</div>
                    <div class="benefit-description">Our experts authenticate every item to ensure buyer confidence and
                        maintain marketplace integrity.</div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">🌍</div>
                    <div class="benefit-title">Global Reach</div>
                    <div class="benefit-description">Access buyers from around the world with our international shipping
                        options and localized support.</div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">📈</div>
                    <div class="benefit-title">Competitive Fees</div>
                    <div class="benefit-description">Keep more of your earnings with our transparent and competitive
                        seller fees - no hidden charges.</div>
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
    
    <script src="../main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Search input focus effect
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('focus', function () {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                searchInput.addEventListener('blur', function () {
                    this.parentElement.style.transform = 'scale(1)';
                });
            }

            // Step card hover effects
            const stepCards = document.querySelectorAll('.step-card');
            stepCards.forEach(card => {
                // card.addEventListener('mouseenter', function () {
                //     this.style.cursor = 'pointer';
                // });
            });

            // Animate elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe step cards for animation
            stepCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });

        function goToListingPage() {
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

            if (isLoggedIn) {
                window.location.href = "/Group-One/frontend/pages/listing-item.php";
            } else {
                openModal('loginModal'); // Make sure this modal function exists
            }
        }
    </script>

</body>

</html>