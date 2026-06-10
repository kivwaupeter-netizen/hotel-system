<?php
$adminPageTitle = 'Manage Users';
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/flash.php';
require_once '../includes/functions.php';
require_once 'includes/admin-header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_user_id'], $_POST['new_role'])) {
    $toggle_id = (int) sanitize($_POST['toggle_user_id'], $conn);
    $new_role  = sanitize($_POST['new_role'], $conn);

    if ($toggle_id > 0 && in_array($new_role, ['user', 'admin']) && $toggle_id !== (int)$_SESSION['user_id']) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param('si', $new_role, $toggle_id);
        $stmt->execute();
        $stmt->close();
        setFlash('success', 'User role updated.');
    }

    redirectTo(BASE_URL . '/admin/users.php');
}

$users      = $conn->query("SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h3 style="font-size:20px; font-weight:700; color:#264653;">Manage Users</h3>
    <span style="background:#e9c46a; color:#264653; padding:8px 18px; border-radius:8px; font-weight:700; font-size:15px;">
        Total: <?php echo $totalUsers; ?> users
    </span>
</div>

<div class="table-search-bar">
    <input type="text" id="table-search" placeholder="Search users...">
</div>

<div class="admin-table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['phone'] ?? '—'); ?></td>
                <td>
                    <?php if ($user['role'] === 'admin'): ?>
                        <span style="background:#f8d7da; color:#721c24; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600;">Admin</span>
                    <?php else: ?>
                        <span style="background:#d4edda; color:#155724; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600;">User</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                <td>
                    <?php if ((int)$user['id'] === (int)$_SESSION['user_id']): ?>
                        <span style="color:#888; font-size:13px; font-style:italic;">(You)</span>
                    <?php else: ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/users.php">
                            <input type="hidden" name="toggle_user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="new_role"
                                   value="<?php echo $user['role'] === 'admin' ? 'user' : 'admin'; ?>">
                            <button type="submit"
                                    class="<?php echo $user['role'] === 'admin' ? 'btn-delete' : 'btn-edit'; ?>">
                                <?php echo $user['role'] === 'admin' ? 'Set as User' : 'Set as Admin'; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/admin-footer.php'; ?>