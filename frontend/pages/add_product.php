<?php
require 'connection.php';

// Handle product form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = trim($_POST['product_name']);
    $category_id = intval($_POST['category_id']);

    if (!empty($product_name) && $category_id > 0) {
        $stmt = $conn->prepare("INSERT INTO products (name, category_id) VALUES (?, ?)");
        $stmt->bind_param("si", $product_name, $category_id);

        if ($stmt->execute()) {
            echo "Product added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

// Fetch categories for dropdown
$categories = $conn->query("SELECT id, name FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h2>Add New Product</h2>
    <form method="POST" action="">
        <label>Product Name:</label>
        <input type="text" name="product_name" required>

        <label>Category:</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php while($row = $categories->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
