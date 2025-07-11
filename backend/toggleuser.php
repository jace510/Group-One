<?php
require_once 'vendor/autoload.php';
require_once 'includes/auth/functions.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

try {
    $user_id = new MongoDB\BSON\ObjectId($_GET['id']);
} catch (Exception $e) {
    header("Location: users.php");
    exit;
}

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->your_database->users;

// Fetch current user
$user = $collection->findOne(['_id' => $user_id]);

if (!$user) {
    header("Location: users.php");
    exit;
}

// Flip is_active
$newStatus = isset($user['is_active']) && $user['is_active'] === true ? false : true;

$collection->updateOne(
    ['_id' => $user_id],
    ['$set' => ['is_active' => $newStatus]]
);

header("Location: users.php");
exit;
?>
