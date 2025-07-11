<?php 
require_once 'includes/db.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = $_GET['id'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: users.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $updateStmt = $conn->prepare("UPDATE users SET username=?, email=?, role=?, is_active=? WHERE id=?");
    $updateStmt->bind_param("ssssi", $username, $email, $role, $is_active, $user_id);
    
    if ($updateStmt->execute()) {
        $_SESSION['message'] = "User updated successfully";
        header("Location: users.php");
        exit;
    } else {
        $error = "Error updating user: " . $conn->error;
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