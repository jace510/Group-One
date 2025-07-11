<?php
// cart_operations.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require 'mongo.php'; // must define $client (MongoDB\Client)

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

session_start();
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized. Please login.']));
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$productId = $_POST['product_id'] ?? $_GET['product_id'] ?? '';

$productsCollection = $client->selectCollection("Railed", "products");
$cartsCollection = $client->selectCollection("Railed", "carts");

switch ($action) {
    case 'add_to_cart':
        addToCart($cartsCollection, $productsCollection, $_SESSION['user_id'], $productId);
        break;
    
    case 'remove_from_cart':
        removeFromCart($cartsCollection, $_SESSION['user_id'], $productId);
        break;
    
    case 'get_cart':
        getCart($cartsCollection, $productsCollection, $_SESSION['user_id']);
        break;
    
    case 'update_quantity':
        $quantity = (int) ($_POST['quantity'] ?? 1);
        updateQuantity($cartsCollection, $_SESSION['user_id'], $productId, $quantity);
        break;
    
    case 'clear_cart':
        clearCart($cartsCollection, $_SESSION['user_id']);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

// ===== CART OPERATIONS =====

function addToCart($cartsCollection, $productsCollection, $userId, $productId) {
    try {
        // Validate product exists and is available
        $product = $productsCollection->findOne([
            '_id' => new ObjectId($productId),
            'status' => 'available'
        ]);
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found or not available']);
            return;
        }
        
        // Check if user is trying to add their own product
        if ($product['seller_id']->__toString() === $userId) {
            echo json_encode(['success' => false, 'message' => 'You cannot add your own product to cart']);
            return;
        }
        
        // Check if user already has a cart
        $cart = $cartsCollection->findOne(['user_id' => new ObjectId($userId)]);
        
        if ($cart) {
            // Check if product already in cart
            $productExists = false;
            foreach ($cart['items'] as $item) {
                if ($item['product_id']->__toString() === $productId) {
                    $productExists = true;
                    break;
                }
            }
            
            if ($productExists) {
                echo json_encode(['success' => false, 'message' => 'Product already in cart']);
                return;
            }
            
            // Add product to existing cart
            $cartsCollection->updateOne(
                ['user_id' => new ObjectId($userId)],
                [
                    '$push' => [
                        'items' => [
                            'product_id' => new ObjectId($productId),
                            'quantity' => 1,
                            'added_at' => new UTCDateTime()
                        ]
                    ],
                    '$set' => ['updated_at' => new UTCDateTime()]
                ]
            );
        } else {
            // Create new cart
            $cartsCollection->insertOne([
                'user_id' => new ObjectId($userId),
                'items' => [
                    [
                        'product_id' => new ObjectId($productId),
                        'quantity' => 1,
                        'added_at' => new UTCDateTime()
                    ]
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error adding to cart: ' . $e->getMessage()]);
    }
}

function removeFromCart($cartsCollection, $userId, $productId) {
    try {
        $result = $cartsCollection->updateOne(
            ['user_id' => new ObjectId($userId)],
            [
                '$pull' => ['items' => ['product_id' => new ObjectId($productId)]],
                '$set' => ['updated_at' => new UTCDateTime()]
            ]
        );
        
        if ($result->getModifiedCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error removing from cart: ' . $e->getMessage()]);
    }
}

function getCart($cartsCollection, $productsCollection, $userId) {
    try {
        $cart = $cartsCollection->findOne(['user_id' => new ObjectId($userId)]);
        
        if (!$cart) {
            echo json_encode(['success' => true, 'cart' => ['items' => [], 'total' => 0]]);
            return;
        }
        
        $cartItems = [];
        $total = 0;
        
        foreach ($cart['items'] as $item) {
            $product = $productsCollection->findOne(['_id' => $item['product_id']]);
            
            if ($product && $product['status'] === 'available') {
                $itemTotal = $product['pricing']['asking_price'] * $item['quantity'];
                $total += $itemTotal;
                
                $cartItems[] = [
                    'product_id' => $item['product_id']->__toString(),
                    'quantity' => $item['quantity'],
                    'product' => [
                        'photo'=> $product['photos'][0] ?? null,
                        'title' => $product['title'],
                        'brand' => $product['brand'],
                        'size' => $product['size'],
                        'color' => $product['color'],
                        'condition' => $product['condition'],   
                        'price' => $product['pricing']['asking_price'],
                    ],
                    'item_total' => $itemTotal
                ];
            }
        }
        
        echo json_encode([
            'success' => true, 
            'cart' => [
                'items' => $cartItems,
                'total' => $total,
                'item_count' => count($cartItems)
            ]
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching cart: ' . $e->getMessage()]);
    }
}

function updateQuantity($cartsCollection, $userId, $productId, $quantity) {
    try {
        if ($quantity < 1) {
            removeFromCart($cartsCollection, $userId, $productId);
            return;
        }
        
        $result = $cartsCollection->updateOne(
            [
                'user_id' => new ObjectId($userId),
                'items.product_id' => new ObjectId($productId)
            ],
            [
                '$set' => [
                    'items.$.quantity' => $quantity,
                    'updated_at' => new UTCDateTime()
                ]
            ]
        );
        
        if ($result->getModifiedCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Quantity updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error updating quantity: ' . $e->getMessage()]);
    }
}

function clearCart($cartsCollection, $userId) {
    try {
        $cartsCollection->deleteOne(['user_id' => new ObjectId($userId)]);
        echo json_encode(['success' => true, 'message' => 'Cart cleared']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error clearing cart: ' . $e->getMessage()]);
    }
}
?>