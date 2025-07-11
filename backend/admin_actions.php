<?php
session_start();
require 'mongo.php';

use MongoDB\BSON\UTCDateTime;

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /GROUP-ONE/frontend/home.php?showLogin=1");
    exit();
}

$collection = $client->Railed->users;
$adminCollection = $client->Railed->admins;

function getAllUsers() {
    global $collection;
    return $collection->find()->toArray();
}

function searchUser($email) {
    global $collection;
    return $collection->findOne(["email" => $email]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                $userId = new MongoDB\BSON\ObjectId($_POST['user_id']);
                $updateData = [
                    "username" => $_POST['username'],
                    "email" => $_POST['email'],
                    "status" => $_POST['status']
                ];
                $collection->updateOne(
                    ["_id" => $userId],
                    ['$set' => $updateData]
                );
                break;
            case 'disable':
                $userId = new MongoDB\BSON\ObjectId($_POST['user_id']);
                $collection->updateOne(
                    ["_id" => $userId],
                    ['$set' => ["status" => "disabled"]]
                );
                break;
        }
    }
}

// Fetch all users for display
$users = getAllUsers();
?>