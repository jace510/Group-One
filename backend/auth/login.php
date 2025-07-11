<?php
session_start();
require '../mongo.php'; // this should initialize $client

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$user_type = $_POST['user_type'] ?? 'customer'; // Default to customer
$redirect = $_POST['redirect'] ?? 'home.php';

// Basic validation
if (empty($email) || empty($password)) {
    die("Please fill in all fields.");
}

// Validate user type
if (!in_array($user_type, ['customer', 'admin'])) {
    die("Invalid user type.");
}

// Select appropriate collection based on user type
if ($user_type === 'admin') {
    $collection = $client->Railed->admins;
} else {
    $collection = $client->Railed->users;
}

// Find user by email
$user = $collection->findOne(["email" => $email]);

if (!$user) {
    die("Invalid email or password.");
}

// Verify password
if (!password_verify($password, $user->password)) {
    die("Invalid email or password.");
}

// Check if user type matches (extra security)
if (isset($user->user_type) && $user->user_type !== $user_type) {
    die("Invalid login credentials for this user type.");
}

// Check if admin account is active (if you're using status field)
if ($user_type === 'admin' && isset($user->status) && $user->status !== 'active') {
    die("Admin account is not active. Please contact system administrator.");
}

// Set session variables
$_SESSION['user_id'] = (string)$user->_id;
$_SESSION['username'] = $user->username;
$_SESSION['email'] = $user->email;
$_SESSION['user_type'] = $user_type;

// Set additional session data based on user type
if ($user_type === 'admin') {
    $_SESSION['role'] = 'admin';
    $_SESSION['permissions'] = $user->permissions ?? [];
    
    // Update last login time for admin
    $collection->updateOne(
        ["_id" => $user->_id],
        ['$set' => ["last_login" => new UTCDateTime()]]
    );
    
    // Redirect to admin dashboard
    header("Location: ../../frontend/admin_dashboard.php");
} else {
    $_SESSION['role'] = 'customer';
    
    // Update last login time for customer
    $collection->updateOne(
        ["_id" => $user->_id],
        ['$set' => ["last_login" => new UTCDateTime()]]
    );
    
    // Redirect to customer area
    if ($redirect && $redirect !== 'home.php') {
        header("Location: ../../frontend/" . $redirect);
    } else {
        header("Location: ../../frontend/home.php");
    }
}

exit();
?>