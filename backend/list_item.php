<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require 'mongo.php'; // must define $client (MongoDB\Client)

// Ensure errors are shown (for development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

// Start session
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized. Please login.");
}

// File upload paths
$uploadDir = '../../uploads/';
$publicPath = '/uploads/';
$photoUrls = [];

// Ensure upload directory exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle photo uploads
if (!empty($_FILES['photos']['tmp_name'][0])) {
    foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
        $originalName = $_FILES['photos']['name'][$index];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $uniqueName = uniqid("img_", true) . "." . $extension;
        $uploadPath = $uploadDir . $uniqueName;

        if (move_uploaded_file($tmpName, $uploadPath)) {
            $photoUrls[] = [
                'url' => $publicPath . $uniqueName,
                'is_primary' => $index === 0
            ];
        }
    }
}

// Grab form fields
$brand = $_POST['brand'] ?? '';
$category = $_POST['category'] ?? '';
$size = $_POST['size'] ?? '';
$color = $_POST['color'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$condition = $_POST['condition'] ?? '';
$askingPrice = (float) ($_POST['asking_price'] ?? 0);
$retailPrice = isset($_POST['retail_price']) ? (float) $_POST['retail_price'] : null;
$notes = $_POST['pricing_notes'] ?? null;

// Build document
$collection = $client->selectCollection("Railed", "products");

$document = [
    'seller_id' => new ObjectId($_SESSION['user_id']), // Must be ObjectId in MongoDB
    'title' => $title,
    'description' => $description,
    'brand' => $brand,
    'category_id' => $category,
    'size' => $size,
    'color' => $color,
    'condition' => $condition,
    'pricing' => [
        'asking_price' => $askingPrice,
        'original_retail_price' => $retailPrice,
        'additional_notes' => $notes    
    ],
    'photos' => $photoUrls,
    'status' => 'available',
    'created_at' => new UTCDateTime(),
    'updated_at' => new UTCDateTime()
];

$collection->insertOne($document);

// Redirect to homepage
header("Location: ../../frontend/home.php?listing=success");
exit();
