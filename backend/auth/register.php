<?php
require 'connection.php';

$username=$_POST['username'];
$email=$_POST['email'];
$password=$_POST['password'];
$password_confirm = $_POST['password_confirm'];

if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
    die("Please fill in all fields.");
}

// Check if passwords match
if ($password !== $password_confirm) {
    die("Passwords do not match.");
}

$hashed_password=password_hash($password,PASSWORD_DEFAULT);

//check if email already exists
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();   
$stmt->store_result();
if ($stmt->num_rows > 0) {
    die("Email is already registered.");
}
$stmt->close();



$sql="insert into users (username,email,password) values(?,?,?)";

$stmt=$conn->prepare($sql);

$stmt->bind_param("sss",$username,$email,$hashed_password);

if ($stmt->execute()) {
    header("Location: login.html");
    exit();

}else{
    echo "Error: ".$stmt->error;
}


$stmt->close();
$conn->close();

?>