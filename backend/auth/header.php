<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="header">
    <div class="header-content">
        <!-- Mobile left icons -->
        <div class="mobile-icons">
            <svg class="mobile-icon" viewBox="0 0 24 24">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
            </svg>
            <svg class="mobile-icon" viewBox="0 0 24 24">
                <path
                    d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
            </svg>
            <svg class="mobile-icon"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 
                 0c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zM7.16 
                 14h9.83c.75 0 1.41-.41 1.75-1.03l3.58-6.49a.996.996 
                 0 00-.88-1.48H5.21L4.27 2H1v2h2l3.6 
                 7.59-1.35 2.45C4.52 14.37 5.48 16 7 
                 16h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L7.16 14z" />
                    </svg>
        </div>

        <!-- Logo -->
        <a href="/Group-One/frontend/home.php" class="logo">RAILED</a>

        <!-- Desktop search -->
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search for anything">
        </div>

        <!-- Desktop navigation -->
        <nav class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/Group-One/frontend/pages/account.php" class="nav-link">ACCOUNT</a>
                <a href="/Group-One/frontend/pages/orders.php" class="nav-link">ORDERS</a>
                <a href="/Group-One/backend/auth/logout.php" class="nav-link">SIGN OUT</a>
                <a href="/Group-One/frontend/pages/cart.php" class="nav-link">CART</a>
            <?php else: ?>
                <a href="#" class="nav-link" onclick="openModal('loginModal')">SIGN IN</a>
                <a href="#" class="nav-link" onclick="openModal('registerModal')">SIGN UP</a>
                <a href="/Group-One/frontend/pages/start-selling.php" class="sell-btn">SELL</a>

            <?php endif; ?>
        </nav>

        <!-- Mobile right icons -->
        <div class="mobile-icons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/Group-One/frontend/pages/account.php" class="nav-link"
                    style="font-size: 12px; margin-right: 10px;">ACCOUNT</a>
                <a href="/Group-One/frontend/pages/orders.php" class="nav-link"
                    style="font-size: 12px; margin-right: 10px;">ORDERS</a>
            <?php else: ?>
                <a href="#" class="nav-link" style="font-size: 12px; margin-right: 10px;"
                    onclick="openModal('loginModal')">Login</a>
                <a href="#" class="nav-link" style="font-size: 12px; margin-right: 10px;"
                    onclick="openModal('registerModal')">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>