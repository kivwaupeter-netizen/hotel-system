<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/flash.php';
require_once '../../includes/admin_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($adminPageTitle) ? htmlspecialchars($adminPageTitle) . ' — Phantom Ridge Resort Admin' : 'Phantom Ridge Resort Admin'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<div class="admin-layout">

    <?php require_once __DIR__ . '/admin-sidebar.php'; ?>

    <div class="admin-main">
        <div class="admin-topbar">
            <span class="page-title">
                <?php echo isset($adminPageTitle) ? htmlspecialchars($adminPageTitle) : 'Admin Panel'; ?>
            </span>
            <div class="topbar-right">
                <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
                <a href="<?php echo BASE_URL; ?>/auth/logout.php">Logout</a>
            </div>
        </div>

        <div class="admin-content">
            <?php displayFlash(); ?>