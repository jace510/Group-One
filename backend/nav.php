<?php
require 'mongo.php'; // your MongoDB connection

$categoryCollection = $client->Railed->categories;

try {
    $topCategories = $categoryCollection->find(['parent_id' => null])->toArray();

    if (empty($topCategories)) {
        echo "<!-- DEBUG: No top-level categories found in DB -->";
    }
} catch (Exception $e) {
    echo "<!-- DEBUG: MongoDB error: " . $e->getMessage() . " -->";
}
?>
