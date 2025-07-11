<?php 
require_once 'includes/db.php';
requireAdmin();
require_once 'includes/header.php';

// Search functionality
$search = '';
$where = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $where = "WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
}
?>

<div class="container">
    <h1>User Management</h1>
    
    <div class="search-bar">
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by username or email" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM users $where ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
            <tr class="<?= $row['is_active'] ? '' : 'inactive' ?>">
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                <td><?= $row['is_active'] ? 'Active' : 'Disabled' ?></td>
                <td><?= ucfirst($row['role']) ?></td>
                <td class="actions">
                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                    <a href="toggle_user.php?id=<?= $row['id'] ?>" 
                       class="btn-toggle">
                        <?= $row['is_active'] ? 'Disable' : 'Enable' ?>
                    </a>
                </td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="7">No users found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>