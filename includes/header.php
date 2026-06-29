<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/flash.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Phantom Ridge Resort' : 'Phantom Ridge Resort'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/responsive.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?php echo BASE_URL; ?>/index.php">Phantom Ridge Resort</a>
        <span>Experience Luxury in Kenya</span>
    </div>

    <button class="hamburger" id="hamburger-btn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="nav-links" id="nav-links">
        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
        <a href="<?php echo BASE_URL; ?>/rooms.php">Rooms</a>
        <a href="<?php echo BASE_URL; ?>/about.php">About</a>
        <a href="<?php echo BASE_URL; ?>/contact.php">Contact</a>
    </div>

<div class="nav-auth" id="nav-auth">
            <?php if (!isLoggedIn()): ?>
            <a href="<?php echo BASE_URL; ?>/auth/login.php" class="btn-login">Login</a>
            <a href="<?php echo BASE_URL; ?>/auth/register.php" class="btn-register">Register</a>
        <?php else: ?>
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="<?php echo BASE_URL; ?>/booking/my-bookings.php">My Bookings</a>
            <a href="<?php echo BASE_URL; ?>/auth/logout.php">Logout</a>
            <?php if (isAdmin()): ?>
                <a href="<?php echo BASE_URL; ?>/admin/index.php" class="btn-admin">Admin Panel</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</nav>

<div class="flash-container">
    <?php displayFlash(); ?>
</div>