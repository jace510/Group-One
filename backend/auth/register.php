<?php
require '../mongo.php'; // this should initialize $client

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// Basic validation
if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
    die("Please fill in all fields.");
}

if ($password !== $password_confirm) {
    die("Passwords do not match.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Select your collection
$collection = $client->Railed->users;
    
// Check if email exists
$existingUser = $collection->findOne(["email" => $email]);

if ($existingUser) {
    die("Email is already registered.");
}

// Prepare new user document
$newUser = [
    "username" => $username,
    "email" => $email,
    "password" => $hashed_password,
    "created_at" => new UTCDateTime()
];

// Insert into MongoDB
$insertResult = $collection->insertOne($newUser);

if ($insertResult->getInsertedCount() === 1) {
    header("Location: ../../frontend/home.php?auth=register_success&show=login");
    exit();
} else {
    echo "Error: User registration failed.";
}
?>
