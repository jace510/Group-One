<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            echo "Category added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please enter a category name.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
</head>
<body>
    <h2>Add New Category</h2>
    <form method="POST" action="">
        <label>Category Name:</label>
        <input type="text" name="category_name" required>
        <button type="submit">Add Category</button>
    </form>
</body>
</html>
