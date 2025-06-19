<?php
session_start(); // Start session for login

require 'connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Get user by email
$sql = "SELECT * FROM users1 WHERE Email = ?";
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

        echo "Login successful! Welcome, " . $user['FirstName'] . ".";
        // You can redirect to dashboard e.g echo header("location: browse.html"); or whichever file comes next, I've left it due to the file structure tunatumia
        // header("Location: dashboard.php");
    } else {
        echo " Incorrect password.";
    }
} else {
    echo "User not found.";//hapa we'll have to employ some javascript so that the user not found comes as an alert but we can finish on functionality first
}

$stmt->close();
$conn->close();
?>