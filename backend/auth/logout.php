<?php
session_start(); // Start the session

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Optional: redirect to home page or login page
header("Location: /Group-One/frontend/home.php");
exit();
?>