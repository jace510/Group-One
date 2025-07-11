<?php
session_start(); // Start session for login

require '../mongo.php'; // Should define $client = new MongoDB\Client(...)

use MongoDB\BSON\ObjectId;

$redirect = $_POST['redirect'] ?? 'home.php';

$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header("Location: ../../frontend/home.php?auth=failed&redirect=" . urlencode($redirect));
    exit();
}

$collection = $client->Railed->users;

// Find user by email
$user = $collection->findOne(['email' => $email]);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = (string) $user['_id'];
    $_SESSION['username'] = $user['username']; // Store MongoDB _id as session string

    header("Location: ../../frontend/" . $redirect);
    exit();
} else {
    // Either user not found or incorrect password
    header("Location: ../../frontend/home.php?auth=failed&redirect=" . urlencode($redirect));
    exit();
}
?>
