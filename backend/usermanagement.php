<?php
require_once 'vendor/autoload.php';
require_once 'includes/auth/functions.php';
requireAdmin();
require_once 'includes/header.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->your_database->users;

// Handle search
$search = $_GET['search'] ?? '';
$filter = [];

if (!empty($search)) {
    $regex = new MongoDB\BSON\Regex($search, 'i'); // Case-insensitive
    $filter = ['$or' => [
        ['username' => $regex],
        ['email' => $regex]
    ]];
}

// Fetch users
$users = $collection->find($filter, ['sort' => ['created_at' => -1]]);
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
            $hasUsers = false;
            foreach ($users as $user):
                $hasUsers = true;
            ?>
            <tr class="<?= $user['is_active'] ? '' : 'inactive' ?>">
                <td><?= htmlspecialchars((string)$user['_id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= isset($user['created_at']) ? $user['created_at']->toDateTime()->format('M d, Y') : 'N/A' ?></td>
                <td><?= $user['is_active'] ? 'Active' : 'Disabled' ?></td>
                <td><?= ucfirst($user['role']) ?></td>
                <td class="actions">
                    <a href="edit_user.php?id=<?= $user['_id'] ?>" class="btn-edit">Edit</a>
                    <a href="toggle_user.php?id=<?= $user['_id'] ?>" class="btn-toggle">
                        <?= $user['is_active'] ? 'Disable' : 'Enable' ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (!$hasUsers): ?>
            <tr>
                <td colspan="7">No users found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
