<?php
require '../backend/admin_actions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-container { margin: 20px 0; }
    </style>
</head>
<body>
    <h2>Admin Dashboard</h2>

    <!-- Search User -->
    <div class="form-container">
        <form method="POST" action="">
            <input type="hidden" name="action" value="search">
            <input type="text" name="search_email" placeholder="Search by email" required>
            <button type="submit">Search</button>
        </form>
        <?php
        if (isset($_POST['search_email'])) {
            $user = searchUser($_POST['search_email']);
            if ($user) {
                echo "<p>User found: " . htmlspecialchars($user->username) . " (" . htmlspecialchars($user->email) . ")</p>";
            } else {
                echo "<p>No user found with that email.</p>";
            }
        }
        ?>
    </div>

    <!-- View All Users -->
    <h3>All Users</h3>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user->username); ?></td>
                <td><?php echo htmlspecialchars($user->email); ?></td>
                <td><?php echo htmlspecialchars($user->status ?? 'active'); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="user_id" value="<?php echo $user->_id; ?>">
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user->username); ?>" required>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required>
                        <select name="status">
                            <option value="active" <?php echo ($user->status ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="disabled" <?php echo isset($user->status) && $user->status === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="action" value="disable">
                        <input type="hidden" name="user_id" value="<?php echo $user->_id; ?>">
                        <button type="submit">Disable</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="logout.php">Logout</a></p>
</body>
</html> 