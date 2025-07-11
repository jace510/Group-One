<?php
require_once 'includes/db.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = $_GET['id'];

// Toggle user status
$stmt = $conn->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

header("Location: users.php");
exit;
?>