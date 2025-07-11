<?php
<?php
session_start();
require_once '../../backend/includes/auth/functionadmin.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Railed</title>
    <link rel="stylesheet" href="../index.css">
    <style>
        .admin-dashboard {
            max-width: 700px;
            margin: 60px auto;
            padding: 2rem 3rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        }
        .admin-dashboard h1 {
            text-align: center;
            margin-bottom: 2rem;
        }
        .admin-links {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .admin-links a {
            display: block;
            padding: 1rem 1.5rem;
            background: #222;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background 0.2s;
        }
        .admin-links a:hover {
            background: #444;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <h1>Admin Dashboard</h1>
        <div class="admin-links">
            <a href="../../backend/usermanagement.php">User Management</a>
            <a href="../../backend/productmanagement.php">Product Management</a>
            <a href="../../backend/orders.php">Order Management</a>
            <a href="../../backend/categories.php">Category Management</a>
            <!-- Add more admin links as needed -->
            <a href="../../frontend/home.php" style="background:#888;">Back to Home</a>
        </div>