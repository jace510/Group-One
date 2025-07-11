<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Ensure a user is logged in
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../pages/login.php?auth=required");
        exit;
    }
}

/**
 * Ensure the logged-in user is an admin
 */
function requireAdmin() {
    requireLogin(); // First make sure user is logged in

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../pages/dashboard.php?error=notadmin");
        exit;
    }
}

/**
 * Check if a user is logged in (for display logic)
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if logged-in user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>
