<?php
require_once 'vendor/autoload.php';
require_once 'includes/auth/functions.php';
requireAdmin();

// MongoDB connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->your_database->users;

// Validate ObjectId
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

// Fetch user data
$user = $collection->findOne(['_id' => $user_id]);

if (!$user) {
    header("Location: users.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $is_active = isset($_POST['is_active']) ? true : false;

    $update = $collection->updateOne(
        ['_id' => $user_id],
        ['$set' => [
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'is_active' => $is_active
        ]]
    );

    if ($update->getModifiedCount()) {
        $_SESSION['message'] = "User updated successfully";
        header("Location: users.php");
        exit;
    } else {
        $error = "No changes made or update failed.";
    }
}

require_once 'includes/header.php';
?>

<div class="container">
    <h1>Edit User: <?= htmlspecialchars($user['username']) ?></h1>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-group">
            <label>Role:</label>
            <select name="role">
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" <?= $user['is_active'] ? 'checked' : '' ?>>
                Active User
            </label>
        </div>

        <button type="submit" class="btn-save">Save Changes</button>
        <a href="users.php" class="btn-cancel">Cancel</a>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
