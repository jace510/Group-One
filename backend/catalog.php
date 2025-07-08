<?php
require 'mongo.php';

$categorySlug = $_GET['category'] ?? null;

if (!$categorySlug) {
    die('Category not specified');
}

$categories = $client->Railed->categories;
$products = $client->Railed->products;

// Find category by slug
$category = $categories->findOne(['slug' => $categorySlug]);

if (!$category) {
    die('Category not found');
}

$categoryId = $category['_id'];

// Fetch products in this category
$cursor = $products->find([
    'category_id' => $categoryId,
    'status' => 'available'
]);

$items = iterator_to_array($cursor);

// Count
$itemCount = count($items);
?>
