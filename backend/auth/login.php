<?php
session_start(); // Start session for login

require 'connection.php';

$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    die("Please enter both email and password.");
}

// Get user by email
$sql = "SELECT id, username, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['FirstName'];
        header("Location: ../../frontend/home.php");
        echo "Login successful! Welcome, " . $user['FirstName'] . ".";
        exit();
            
    } else {
        echo " Incorrect password.";
    }
} else {
    echo "User not found.";//hapa we'll have to employ some javascript so that the user not found comes as an alert but we can finish on functionality first
}

$stmt->close();
$conn->close();
?>