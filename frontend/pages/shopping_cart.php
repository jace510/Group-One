<?php
//redirect if not logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /Group-One/backend/auth/login.php");
    exit();
}

include '../../backend/auth/header.php';
?>


