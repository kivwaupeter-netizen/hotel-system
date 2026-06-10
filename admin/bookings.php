<?php
$adminPageTitle = 'Manage Bookings';
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/flash.php';
require_once '../includes/functions.php';
require_once 'includes/admin-header.php';

$validStatuses = ['pending', 'confirmed', 'cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking_id'], $_POST['new_status'])) {
    $update_id  = (int) sanitize($_POST['update_booking_id'], $conn);
    $new_status = sanitize($_POST['new_status'], $conn);

    if ($update_id > 0 && in_array($new_status, $validStatuses)) {
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $new_status, $update_id);
        $stmt->execute();
        $stmt->close();
        setFlash('success', 'Booking status updated.');
    }

    redirectTo(BASE_URL . '/admin/bookings.php');
}

$status_filter = sanitize($_GET['status_filter'] ?? '', $conn);

if ($status_filter && in_array($status_filter, $validStatuses)) {
    $stmt = $conn->prepare("
        SELECT b.*, u.name as user_name, u.email as user_email,
               r.name as room_name, r.type
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        WHERE b.status = ?
        ORDER BY b.created_at DESC
    ");
    $stmt->bind_param('s', $status_filter);
    $stmt->execute();
    $bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $bookings = $conn->query("
        SELECT b.*, u.name as user_name, u.email as user_email,
               r.name as room_name, r.type
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        ORDER BY b.created_at DESC
    ")->fetch_all(MYSQLI_ASSOC);
}
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h3 style="font-size:20px; font-weight:700; color:#264653;">Manage Bookings</h3>
</div>

<form method="GET" action="<?php echo BASE_URL; ?>/admin/bookings.php"
      style="display:flex; gap:12px; align-items:center; margin-bottom:20px; flex-wrap:wrap;">
    <select name="status_filter" style="padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:15px;">
        <option value="">All Statuses</option>
        <option value="pending"   <?php echo $status_filter === 'pending'   ? 'selected' : ''; ?>>Pending</option>
        <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
        <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
    </select>
    <button type="submit" class="btn-primary">Filter</button>
    <a href="<?php echo BASE_URL; ?>/admin/bookings.php" class="btn-outline">Clear</a>
</form>

<div class="table-search-bar">
    <input type="text" id="table-search" placeholder="Search bookings...">
</div>

<div class="admin-table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Ref</th>
                <th>Guest</th>
                <th>Email</th>
                <th>Room</th>
                <th>Type</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Nights</th>
                <th>Guests</th>
                <th>Total KES</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo 'PRR-' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></td>
                <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                <td><?php echo htmlspecialchars($booking['user_email']); ?></td>
                <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                <td>
                    <span class="<?php echo getTypeBadgeClass($booking['type']); ?>">
                        <?php echo getTypeLabel($booking['type']); ?>
                    </span>
                </td>
                <td><?php echo date('d M Y', strtotime($booking['check_in'])); ?></td>
                <td><?php echo date('d M Y', strtotime($booking['check_out'])); ?></td>
                <td><?php echo $booking['num_nights']; ?></td>
                <td><?php echo $booking['num_guests']; ?></td>
                <td><?php echo formatKES($booking['total_price']); ?></td>
                <td>
                    <span class="status-<?php echo $booking['status']; ?>">
                        <?php echo ucfirst($booking['status']); ?>
                    </span>
                </td>
                <td>
                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/bookings.php"
                          style="display:flex; gap:6px; align-items:center;">
                        <input type="hidden" name="update_booking_id" value="<?php echo $booking['id']; ?>">
                        <select name="new_status"
                                style="padding:5px 8px; border:1px solid #ddd; border-radius:6px; font-size:13px;">
                            <option value="pending"   <?php echo $booking['status'] === 'pending'   ? 'selected' : ''; ?>>Pending</option>
                            <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="btn-edit">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/admin-footer.php'; ?>