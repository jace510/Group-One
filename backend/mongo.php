<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php'; // Adjust path if you're not in root

use MongoDB\Driver\ServerApi;
use MongoDB\Client;

$uri = 'mongodb+srv://achar:12345@railed.pbwj9tw.mongodb.net/?retryWrites=true&w=majority&appName=Railed';

$apiVersion = new ServerApi(ServerApi::V1);
$client = new Client($uri, [], ['serverApi' => $apiVersion]);

try {
    $client->selectDatabase('Railed')->command(['ping' => 1]);
    // echo "Pinged your deployment. You successfully connected to MongoDB!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
    