<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-sidebar">
    <div class="sidebar-brand">
        <strong>Phantom Ridge Resort</strong>
        <span>Admin Panel</span>
    </div>

    <ul class="sidebar-nav">
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/index.php"
               class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                🏠 Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/rooms.php"
               class="<?php echo $currentPage === 'rooms.php' ? 'active' : ''; ?>">
                🛏 Manage Rooms
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/add-room.php"
               class="<?php echo $currentPage === 'add-room.php' ? 'active' : ''; ?>">
                ➕ Add Room
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/bookings.php"
               class="<?php echo $currentPage === 'bookings.php' ? 'active' : ''; ?>">
                📋 Manage Bookings
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/users.php"
               class="<?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">
                👥 Manage Users
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/admin/reports.php"
               class="<?php echo $currentPage === 'reports.php' ? 'active' : ''; ?>">
                📊 Reports
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="logout">
                🚪 Logout
            </a>
        </li>
    </ul>
</div>