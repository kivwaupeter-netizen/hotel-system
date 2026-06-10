<?php
$adminPageTitle = 'Reports & Analytics';
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/flash.php';
require_once '../includes/functions.php';
require_once 'includes/admin-header.php';

$revenueByType = $conn->query("
    SELECT r.type, COUNT(b.id) as total_bookings, SUM(b.total_price) as total_revenue
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE b.status != 'cancelled'
    GROUP BY r.type
")->fetch_all(MYSQLI_ASSOC);

$monthlyBookings = $conn->query("
    SELECT MONTH(created_at) as month_num, MONTHNAME(created_at) as month_name, COUNT(*) as total
    FROM bookings
    WHERE YEAR(created_at) = YEAR(NOW())
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at) ASC
")->fetch_all(MYSQLI_ASSOC);

$topRooms = $conn->query("
    SELECT r.name, r.type, COUNT(b.id) as total_bookings, SUM(b.total_price) as total_revenue
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE b.status != 'cancelled'
    GROUP BY b.room_id
    ORDER BY total_bookings DESC
    LIMIT 3
")->fetch_all(MYSQLI_ASSOC);

$statusRows = $conn->query("
    SELECT status, COUNT(*) as total FROM bookings GROUP BY status
")->fetch_all(MYSQLI_ASSOC);

$statusCounts = ['pending' => 0, 'confirmed' => 0, 'cancelled' => 0, 'total' => 0];
foreach ($statusRows as $row) {
    $statusCounts[$row['status']] = $row['total'];
    $statusCounts['total'] += $row['total'];
}
?>

<h3 style="margin-bottom:24px; font-size:20px; font-weight:700; color:#264653;">Reports &amp; Analytics</h3>

<h4 style="margin-bottom:16px; font-size:17px; font-weight:700; color:#264653;">Bookings by Status</h4>
<div class="stats-grid" style="margin-bottom:40px;">
    <div class="stat-card">
        <div class="stat-number"><?php echo $statusCounts['total']; ?></div>
        <div class="stat-label">Total Bookings</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $statusCounts['pending']; ?></div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $statusCounts['confirmed']; ?></div>
        <div class="stat-label">Confirmed</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $statusCounts['cancelled']; ?></div>
        <div class="stat-label">Cancelled</div>
    </div>
</div>

<h4 style="margin-bottom:16px; font-size:17px; font-weight:700; color:#264653;">Revenue by Room Type</h4>
<div class="admin-table-wrapper" style="margin-bottom:40px;">
    <table>
        <thead>
            <tr>
                <th>Room Type</th>
                <th>Total Bookings</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($revenueByType)): ?>
            <tr>
                <td colspan="3" style="text-align:center; color:#888;">No data available.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($revenueByType as $row): ?>
            <tr>
                <td>
                    <span class="<?php echo getTypeBadgeClass($row['type']); ?>">
                        <?php echo getTypeLabel($row['type']); ?>
                    </span>
                </td>
                <td><?php echo $row['total_bookings']; ?></td>
                <td><?php echo formatKES($row['total_revenue']); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<h4 style="margin-bottom:16px; font-size:17px; font-weight:700; color:#264653;">Monthly Bookings (This Year)</h4>
<div class="admin-table-wrapper" style="margin-bottom:40px;">
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Number of Bookings</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($monthlyBookings)): ?>
            <tr>
                <td colspan="2" style="text-align:center; color:#888;">No bookings recorded this year.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($monthlyBookings as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['month_name']); ?></td>
                <td><?php echo $row['total']; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<h4 style="margin-bottom:16px; font-size:17px; font-weight:700; color:#264653;">Top 3 Most Booked Rooms</h4>
<div class="admin-table-wrapper" style="margin-bottom:40px;">
    <table>
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Type</th>
                <th>Total Bookings</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($topRooms)): ?>
            <tr>
                <td colspan="4" style="text-align:center; color:#888;">No data available.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($topRooms as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <span class="<?php echo getTypeBadgeClass($row['type']); ?>">
                        <?php echo getTypeLabel($row['type']); ?>
                    </span>
                </td>
                <td><?php echo $row['total_bookings']; ?></td>
                <td><?php echo formatKES($row['total_revenue']); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/admin-footer.php'; ?>