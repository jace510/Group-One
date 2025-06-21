<?php
session_start(); // Start session for login

require '../connection.php';

$redirect = $_POST['redirect'] ?? 'home.php'; // Default to home if none provided

$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    // Redirect with auth failure
    header("Location: ../../frontend/home.php?auth=failed&redirect=" . urlencode($redirect));
    exit();
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
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];

        // Redirect to originally requested page
        header("Location: ../../frontend/" . $redirect);
        $stmt->close();
        $conn->close();
        exit();

    } else {
        // Incorrect password, redirect back with auth failed
        $stmt->close();
        $conn->close();
        header("Location: ../../frontend/home.php?auth=failed&redirect=" . urlencode($redirect));
        exit();
    }
} else {
    // No user found, redirect back with auth failed
    $stmt->close();
    $conn->close();
    header("Location: ../../frontend/home.php?auth=failed&redirect=" . urlencode($redirect));
    exit();
}
?>