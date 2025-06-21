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
                <a href="/Group-One/frontend/pages/start-selling.php" class="sell-btn">SELL</a>
            <?php else: ?>
                <a href="#" class="nav-link" onclick="openModal('loginModal')">Login</a>
                <a href="#" class="nav-link" onclick="openModal('registerModal')">Register</a>
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