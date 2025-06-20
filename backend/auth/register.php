<?php
require 'connection.php';

$firstname=$_POST['fname'];
$lastname=$_POST['lname'];
$email=$_POST['email'];
$password=$_POST['password'];

$hashed_password=password_hash($password,PASSWORD_DEFAULT);

$sql="insert into users (firstName,lastName,email,password) values(?,?,?,?)";

$stmt=$conn->prepare($sql);

$stmt->bind_param("ssss",$firstname,$lastname,$email,$hashed_password);

if ($stmt->execute()) {
    header("Location: login.html");
    exit();

}else{
    echo "Error: ".$stmt->error;
}


$stmt->close();
$conn->close();

?>