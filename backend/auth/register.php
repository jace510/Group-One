<?php
require '../mongo.php'; // this should initialize $client

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$user_type = $_POST['user_type'] ?? 'customer'; // Default to customer if not specified

// Basic validation
if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
    die("Please fill in all fields.");
}

if ($password !== $password_confirm) {
    die("Passwords do not match.");
}

// Validate user type
if (!in_array($user_type, ['customer', 'admin'])) {
    die("Invalid user type.");
}

// Optional: Add admin registration restrictions
// You might want to require an admin key or restrict admin registration
if ($user_type === 'admin') {
    // Option 1: Require admin key
    $admin_key = $_POST['admin_key'] ?? '';
    $valid_admin_key = 'your_secret_admin_key_here'; // Store this securely, preferably in environment variables
    
    if ($admin_key !== $valid_admin_key) {
        die("Invalid admin registration key.");
    }
    
    // Option 2: Or restrict admin registration to specific domains
    // $allowed_admin_domains = ['yourdomain.com', 'admin.yourdomain.com'];
    // $email_domain = substr(strrchr($email, "@"), 1);
    // if (!in_array($email_domain, $allowed_admin_domains)) {
    //     die("Admin registration is restricted to authorized domains.");
    // }
    
    // Option 3: Or require existing admin approval (set status to pending)
    // $status = 'pending_approval';
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Select appropriate collection based on user type
if ($user_type === 'admin') {
    $collection = $client->Railed->admins;
} else {
    $collection = $client->Railed->users; // Keep customers in the existing users collection
}

// Check if email exists in both collections to prevent duplicate emails
$customerCollection = $client->Railed->users;
$adminCollection = $client->Railed->admins;

$existingCustomer = $customerCollection->findOne(["email" => $email]);
$existingAdmin = $adminCollection->findOne(["email" => $email]);

if ($existingCustomer || $existingAdmin) {
    die("Email is already registered.");
}

// Prepare new user document
$newUser = [
    "username" => $username,
    "email" => $email,
    "password" => $hashed_password,
    "user_type" => $user_type,
    "created_at" => new UTCDateTime()
];

// Add role-specific fields
if ($user_type === 'admin') {
    $newUser["role"] = "admin";
    $newUser["permissions"] = [
        "manage_users",
        "manage_products", 
        "view_analytics",
        "system_settings"
    ]; // Default admin permissions
    $newUser["status"] = "active"; // or "pending_approval" if you want manual approval
} else {
    $newUser["role"] = "customer";
    $newUser["status"] = "active";
    $newUser["profile"] = [
        "first_name" => "",
        "last_name" => "",
        "phone" => "",
        "address" => [
            "street" => "",
            "city" => "",
            "state" => "",
            "zip" => "",
            "country" => ""
        ]
    ];
    $newUser["preferences"] = [
        "notifications" => true,
        "newsletter" => false
    ];
}

// Insert into MongoDB
$insertResult = $collection->insertOne($newUser);

if ($insertResult->getInsertedCount() === 1) {
    // Different redirect based on user type
    if ($user_type === 'admin') {
        header("Location: ../../frontend/admin/dashboard.php?auth=register_success&show=login");
    } else {
        header("Location: ../../frontend/home.php?auth=register_success&show=login");
    }
    exit();
} else {
    echo "Error: User registration failed.";
}
?>